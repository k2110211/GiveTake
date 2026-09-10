<?php

namespace App\Console\Commands;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\ItemStatus;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

#[Signature('raffle:draw-expired')]
#[Description('Tự động quay số và chọn người may mắn cho các bài đăng quay thưởng đã đến hạn')]
class DrawExpiredRaffles extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Đang kiểm tra các bài đăng quay thưởng đã đến hạn...');

        $expiredRaffles = Item::where('type_id', 3)
            ->where('item_status_id', ItemStatus::AVAILABLE)
            ->whereNull('winner_id')
            ->whereNotNull('raffle_ends_at')
            ->where('raffle_ends_at', '<=', now())
            ->with(['user', 'requests.user'])
            ->get();

        if ($expiredRaffles->isEmpty()) {
            $this->info('Không có bài đăng quay thưởng nào cần quay số.');
            return Command::SUCCESS;
        }

        $drawnCount = 0;

        foreach ($expiredRaffles as $item) {
            $pendingRequests = $item->requests->where('request_status_id', 1);

            if ($pendingRequests->isEmpty()) {
                $this->warn("Bài đăng #{$item->id} ('{$item->title}') không có thành viên nào đăng ký tham gia. Đang bỏ qua.");
                continue;
            }

            $winningRequest = $pendingRequests->random();
            $winner = $winningRequest->user;

            DB::transaction(function () use ($item, $winningRequest, $winner) {
                // 1. Duyệt request trúng thưởng
                $winningRequest->update(['request_status_id' => 2]);

                // 2. Từ chối các request còn lại
                ItemRequest::where('item_id', $item->id)
                    ->where('id', '!=', $winningRequest->id)
                    ->where('request_status_id', 1)
                    ->update(['request_status_id' => 3]);

                // 3. Cập nhật trạng thái bài đăng sang Đang trao đổi (3) và gán winner_id
                $item->update([
                    'winner_id' => $winner->id,
                    'item_status_id' => 3,
                ]);

                // 4. Tạo phòng chat giữa người đăng và người trúng giải
                $chatRoom = ChatRoom::firstOrCreate([
                    'item_request_id' => $winningRequest->id,
                ]);

                // 5. Gửi tin nhắn hệ thống thông báo trúng giải
                ChatMessage::create([
                    'chat_room_id' => $chatRoom->id,
                    'user_id' => $item->user_id,
                    'message' => "🎉 Hệ thống Cho & Nhận: Xin chúc mừng {$winner->name} đã may mắn trúng thưởng món đồ '{$item->title}'! Hãy trao đổi chi tiết về thời gian và địa điểm nhận đồ tại đây nhé.",
                    'is_read' => false,
                ]);
            });

            $drawnCount++;
            $this->info("Đã quay số thành công cho bài đăng #{$item->id} ('{$item->title}'). Người trúng giải: {$winner->name} (ID: {$winner->id}).");
            Log::info("Raffle auto-drawn for Item #{$item->id}: Winner {$winner->name} (ID: {$winner->id})");
        }

        $this->info("Hoàn tất quay số cho {$drawnCount} bài đăng!");
        return Command::SUCCESS;
    }
}
