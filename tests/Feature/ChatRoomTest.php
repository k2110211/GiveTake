<?php
 
namespace Tests\Feature;
 
use App\Livewire\ChatRoom as ChatRoomComponent;
use App\Models\Category;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\ItemStatus;
use App\Models\RequestStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
 
class ChatRoomTest extends TestCase
{
    use RefreshDatabase;
 
    private function createApprovedScenario(): array
    {
        $giver = User::factory()->create();
        $requester = User::factory()->create();
        $category = Category::create(['name' => 'Books', 'slug' => 'books']);
 
        $item = Item::create([
            'user_id' => $giver->id,
            'category_id' => $category->id,
            'title' => 'PHP 8 Programming Tips',
            'description' => 'A comprehensive book on PHP 8 features and best practices.',
            'images' => [],
            'type' => 'give',
            'status' => 'reserved',
            'city' => 'Hà Nội',
            'district' => 'Cầu Giấy'
        ]);
 
        $itemRequest = ItemRequest::create([
            'item_id' => $item->id,
            'user_id' => $requester->id,
            'message' => 'I would love to read this book to improve my PHP skills!',
            'status' => 'approved'
        ]);
 
        $chatRoom = ChatRoom::create([
            'item_request_id' => $itemRequest->id
        ]);
 
        return compact('giver', 'requester', 'item', 'itemRequest', 'chatRoom');
    }
 
    public function test_guest_cannot_access_chat_room(): void
    {
        $scenario = $this->createApprovedScenario();
 
        $this->get('/chat/' . $scenario['chatRoom']->id)
            ->assertRedirect(route('login'));
    }
 
    public function test_non_participant_gets_403(): void
    {
        $scenario = $this->createApprovedScenario();
        $stranger = User::factory()->create();
 
        $this->actingAs($stranger);
 
        Livewire::test(ChatRoomComponent::class, ['roomId' => $scenario['chatRoom']->id])
            ->assertStatus(403);
    }
 
    public function test_giver_can_access_chat_room(): void
    {
        $scenario = $this->createApprovedScenario();
        $this->actingAs($scenario['giver']);
 
        $this->get('/chat/' . $scenario['chatRoom']->id)
            ->assertStatus(200)
            ->assertSee('PHP 8 Programming Tips');
    }
 
    public function test_requester_can_access_chat_room(): void
    {
        $scenario = $this->createApprovedScenario();
        $this->actingAs($scenario['requester']);
 
        $this->get('/chat/' . $scenario['chatRoom']->id)
            ->assertStatus(200)
            ->assertSee('PHP 8 Programming Tips');
    }
 
    public function test_participant_can_send_message(): void
    {
        $scenario = $this->createApprovedScenario();
        $this->actingAs($scenario['giver']);
 
        Livewire::test(ChatRoomComponent::class, ['roomId' => $scenario['chatRoom']->id])
            ->set('newMessage', 'Hello! When can we meet for the exchange?')
            ->call('sendMessage')
            ->assertHasNoErrors()
            ->assertSet('newMessage', '');
 
        $this->assertDatabaseHas('chat_messages', [
            'chat_room_id' => $scenario['chatRoom']->id,
            'user_id' => $scenario['giver']->id,
            'message' => 'Hello! When can we meet for the exchange?'
        ]);
    }
 
    public function test_sending_empty_message_fails_validation(): void
    {
        $scenario = $this->createApprovedScenario();
        $this->actingAs($scenario['giver']);
 
        Livewire::test(ChatRoomComponent::class, ['roomId' => $scenario['chatRoom']->id])
            ->set('newMessage', '')
            ->call('sendMessage')
            ->assertHasErrors(['newMessage' => 'required']);
    }
 
    public function test_messages_are_marked_as_read_on_load(): void
    {
        $scenario = $this->createApprovedScenario();
 
        // Requester sends a message
        $msg = ChatMessage::create([
            'chat_room_id' => $scenario['chatRoom']->id,
            'user_id' => $scenario['requester']->id,
            'message' => 'Hi, I sent you a message!',
            'is_read' => false
        ]);
 
        // Giver opens the chat room → messages from requester should be marked as read
        $this->actingAs($scenario['giver']);
 
        Livewire::test(ChatRoomComponent::class, ['roomId' => $scenario['chatRoom']->id]);
 
        $this->assertDatabaseHas('chat_messages', [
            'id' => $msg->id,
            'is_read' => 1
        ]);
    }

    public function test_owner_can_approve_request_inside_chat_room(): void
    {
        $giver = User::factory()->create();
        $requester1 = User::factory()->create();
        $requester2 = User::factory()->create();
        $category = Category::create(['name' => 'Tech', 'slug' => 'tech']);

        $item = Item::create([
            'user_id' => $giver->id,
            'category_id' => $category->id,
            'title' => 'Mechanical Keyboard',
            'description' => 'Blue switches, great condition.',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => 1,
            'city_id' => 1,
            'district_id' => 1
        ]);

        $req1 = ItemRequest::create([
            'item_id' => $item->id,
            'user_id' => $requester1->id,
            'message' => 'Can I have this keyboard?',
            'request_status_id' => 1
        ]);

        $req2 = ItemRequest::create([
            'item_id' => $item->id,
            'user_id' => $requester2->id,
            'message' => 'I would love this keyboard as well.',
            'request_status_id' => 1
        ]);

        $chatRoom = ChatRoom::create(['item_request_id' => $req1->id]);

        $this->actingAs($giver);

        Livewire::test(ChatRoomComponent::class, ['roomId' => $chatRoom->id])
            ->call('approveRequest')
            ->assertHasNoErrors();

        // Check request 1 is approved
        $this->assertEquals(2, $req1->refresh()->request_status_id);

        // Check item is reserved
        $this->assertEquals(3, $item->refresh()->item_status_id);

        // Check other request is rejected
        $this->assertEquals(3, $req2->refresh()->request_status_id);

        // Check system message in chat
        $this->assertDatabaseHas('chat_messages', [
            'chat_room_id' => $chatRoom->id,
            'user_id' => $giver->id
        ]);
    }

    public function test_requester_cannot_approve_request(): void
    {
        $giver = User::factory()->create();
        $requester = User::factory()->create();
        $category = Category::create(['name' => 'Toys', 'slug' => 'toys']);

        $item = Item::create([
            'user_id' => $giver->id,
            'category_id' => $category->id,
            'title' => 'Puzzle',
            'description' => '1000 pieces puzzle.',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => 1,
            'city_id' => 1,
            'district_id' => 1
        ]);

        $req = ItemRequest::create([
            'item_id' => $item->id,
            'user_id' => $requester->id,
            'message' => 'I want this puzzle.',
            'request_status_id' => 1
        ]);

        $chatRoom = ChatRoom::create(['item_request_id' => $req->id]);

        $this->actingAs($requester);

        Livewire::test(ChatRoomComponent::class, ['roomId' => $chatRoom->id])
            ->call('approveRequest')
            ->assertStatus(403);
    }

    public function test_owner_can_reject_request_inside_chat_room(): void
    {
        $giver = User::factory()->create();
        $requester = User::factory()->create();
        $category = Category::create(['name' => 'Games', 'slug' => 'games']);

        $item = Item::create([
            'user_id' => $giver->id,
            'category_id' => $category->id,
            'title' => 'Board Game',
            'description' => 'Fun board game.',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => 1,
            'city_id' => 1,
            'district_id' => 1
        ]);

        $req = ItemRequest::create([
            'item_id' => $item->id,
            'user_id' => $requester->id,
            'message' => 'Can I take this?',
            'request_status_id' => 1
        ]);

        $chatRoom = ChatRoom::create(['item_request_id' => $req->id]);

        $this->actingAs($giver);

        Livewire::test(ChatRoomComponent::class, ['roomId' => $chatRoom->id])
            ->call('rejectRequest')
            ->assertHasNoErrors();

        $this->assertEquals(3, $req->refresh()->request_status_id);
    }

    public function test_participant_can_confirm_received_and_complete_transaction(): void
    {
        $giver = User::factory()->create(['karma_points' => 10]);
        $requester = User::factory()->create();
        $category = Category::create(['name' => 'Kitchen', 'slug' => 'kitchen']);

        $item = Item::create([
            'user_id' => $giver->id,
            'category_id' => $category->id,
            'title' => 'Coffee Maker',
            'description' => 'Good coffee maker',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => ItemStatus::RESERVED,
            'city_id' => 1,
            'district_id' => 1
        ]);

        $req = ItemRequest::create([
            'item_id' => $item->id,
            'user_id' => $requester->id,
            'message' => 'I would love to receive this!',
            'request_status_id' => RequestStatus::ACCEPTED
        ]);

        $chatRoom = ChatRoom::create(['item_request_id' => $req->id]);

        // Requester confirms receipt
        $this->actingAs($requester);

        Livewire::test(ChatRoomComponent::class, ['roomId' => $chatRoom->id])
            ->call('confirmReceived')
            ->assertHasNoErrors()
            ->assertSee('Xác nhận giao nhận thành công');

        // Check request status is COMPLETED
        $this->assertEquals(RequestStatus::COMPLETED, $req->refresh()->request_status_id);

        // Check item status is COMPLETED
        $this->assertEquals(ItemStatus::COMPLETED, $item->refresh()->item_status_id);

        // Check giver was awarded +15 Karma points
        $this->assertEquals(25, $giver->refresh()->karma_points);

        // Check system message was posted
        $this->assertDatabaseHas('chat_messages', [
            'chat_room_id' => $chatRoom->id,
            'user_id' => $requester->id,
        ]);
    }

    public function test_giver_can_also_confirm_received_and_complete(): void
    {
        $giver = User::factory()->create(['karma_points' => 50]);
        $requester = User::factory()->create();
        $category = Category::create(['name' => 'Tools', 'slug' => 'tools']);

        $item = Item::create([
            'user_id' => $giver->id,
            'category_id' => $category->id,
            'title' => 'Drill Set',
            'description' => 'Working drill set',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => ItemStatus::RESERVED,
            'city_id' => 1,
            'district_id' => 1
        ]);

        $req = ItemRequest::create([
            'item_id' => $item->id,
            'user_id' => $requester->id,
            'message' => 'Please give me the drill',
            'request_status_id' => RequestStatus::ACCEPTED
        ]);

        $chatRoom = ChatRoom::create(['item_request_id' => $req->id]);

        $this->actingAs($giver);

        Livewire::test(ChatRoomComponent::class, ['roomId' => $chatRoom->id])
            ->call('confirmReceived')
            ->assertHasNoErrors()
            ->assertSee('Xác nhận giao nhận thành công');

        $this->assertEquals(RequestStatus::COMPLETED, $req->refresh()->request_status_id);
        $this->assertEquals(ItemStatus::COMPLETED, $item->refresh()->item_status_id);
        $this->assertEquals(65, $giver->refresh()->karma_points);
    }

    public function test_participant_can_cancel_transaction_and_restore_item(): void
    {
        $giver = User::factory()->create();
        $requester1 = User::factory()->create();
        $requester2 = User::factory()->create();
        $category = Category::create(['name' => 'Furniture', 'slug' => 'furniture']);

        $item = Item::create([
            'user_id' => $giver->id,
            'category_id' => $category->id,
            'title' => 'Desk Lamp',
            'description' => 'Bright LED desk lamp',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => ItemStatus::RESERVED,
            'city_id' => 1,
            'district_id' => 1,
            'winner_id' => $requester1->id
        ]);

        $req1 = ItemRequest::create([
            'item_id' => $item->id,
            'user_id' => $requester1->id,
            'message' => 'I want the lamp',
            'request_status_id' => RequestStatus::ACCEPTED
        ]);

        $req2 = ItemRequest::create([
            'item_id' => $item->id,
            'user_id' => $requester2->id,
            'message' => 'I also want the lamp',
            'request_status_id' => RequestStatus::REJECTED // Was auto-rejected when req1 was accepted
        ]);

        $chatRoom = ChatRoom::create(['item_request_id' => $req1->id]);

        // Giver decides to cancel because requester1 flaked
        $this->actingAs($giver);

        Livewire::test(ChatRoomComponent::class, ['roomId' => $chatRoom->id])
            ->call('cancelTransaction')
            ->assertHasNoErrors()
            ->assertSee('Đã hủy giao dịch thành công');

        // Check req1 is CANCELLED
        $this->assertEquals(RequestStatus::CANCELLED, $req1->refresh()->request_status_id);

        // Check item is reverted to AVAILABLE and winner_id is cleared
        $this->assertEquals(ItemStatus::AVAILABLE, $item->refresh()->item_status_id);
        $this->assertNull($item->refresh()->winner_id);

        // Check req2 was restored to PENDING so giver can pick them!
        $this->assertEquals(RequestStatus::PENDING, $req2->refresh()->request_status_id);

        // Check system message was posted
        $this->assertDatabaseHas('chat_messages', [
            'chat_room_id' => $chatRoom->id,
            'user_id' => $giver->id,
        ]);
    }

    public function test_cannot_confirm_received_if_not_accepted(): void
    {
        $giver = User::factory()->create();
        $requester = User::factory()->create();
        $category = Category::create(['name' => 'Garden', 'slug' => 'garden']);

        $item = Item::create([
            'user_id' => $giver->id,
            'category_id' => $category->id,
            'title' => 'Plant Pot',
            'description' => 'Ceramic pot',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => ItemStatus::AVAILABLE,
            'city_id' => 1,
            'district_id' => 1
        ]);

        $req = ItemRequest::create([
            'item_id' => $item->id,
            'user_id' => $requester->id,
            'message' => 'Plant pot request',
            'request_status_id' => RequestStatus::PENDING
        ]);

        $chatRoom = ChatRoom::create(['item_request_id' => $req->id]);

        $this->actingAs($requester);

        Livewire::test(ChatRoomComponent::class, ['roomId' => $chatRoom->id])
            ->call('confirmReceived')
            ->assertSee('Giao dịch chưa ở trạng thái được chấp nhận');

        $this->assertEquals(RequestStatus::PENDING, $req->refresh()->request_status_id);
        $this->assertEquals(ItemStatus::AVAILABLE, $item->refresh()->item_status_id);
    }

    public function test_cannot_cancel_if_already_completed(): void
    {
        $giver = User::factory()->create();
        $requester = User::factory()->create();
        $category = Category::create(['name' => 'Music', 'slug' => 'music']);

        $item = Item::create([
            'user_id' => $giver->id,
            'category_id' => $category->id,
            'title' => 'Acoustic Guitar',
            'description' => 'Yamaha guitar',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => ItemStatus::COMPLETED,
            'city_id' => 1,
            'district_id' => 1
        ]);

        $req = ItemRequest::create([
            'item_id' => $item->id,
            'user_id' => $requester->id,
            'message' => 'Guitar request',
            'request_status_id' => RequestStatus::COMPLETED
        ]);

        $chatRoom = ChatRoom::create(['item_request_id' => $req->id]);

        $this->actingAs($giver);

        Livewire::test(ChatRoomComponent::class, ['roomId' => $chatRoom->id])
            ->call('cancelTransaction')
            ->assertSee('Chỉ có thể hủy giao dịch khi đang ở trạng thái đã chấp nhận');

        $this->assertEquals(RequestStatus::COMPLETED, $req->refresh()->request_status_id);
        $this->assertEquals(ItemStatus::COMPLETED, $item->refresh()->item_status_id);
    }

    public function test_participant_can_send_quick_reply(): void
    {
        $scenario = $this->createApprovedScenario();
        $this->actingAs($scenario['giver']);

        Livewire::test(ChatRoomComponent::class, ['roomId' => $scenario['chatRoom']->id])
            ->call('sendQuickReply', '💬 Món đồ này còn không bạn?')
            ->assertHasNoErrors()
            ->assertSet('newMessage', '');

        $this->assertDatabaseHas('chat_messages', [
            'chat_room_id' => $scenario['chatRoom']->id,
            'user_id' => $scenario['giver']->id,
            'message' => '💬 Món đồ này còn không bạn?'
        ]);
    }
}
