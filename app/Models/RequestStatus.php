<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RequestStatus extends Model
{
    use HasFactory;

    public const PENDING = 1;        // Chờ xử lý / Đang trò chuyện
    public const ACCEPTED = 2;       // Đã chấp nhận
    public const REJECTED = 3;       // Từ chối
    public const CANCELLED = 4;      // Đã hủy giao dịch
    public const COMPLETED = 5;      // Đã nhận / Hoàn thành

    protected $table = 'request_statuses';

    protected $fillable = ['name', 'color'];

    public function requests(): HasMany
    {
        return $this->hasMany(ItemRequest::class, 'request_status_id');
    }
}
