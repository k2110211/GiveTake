<?php
 
namespace Tests\Feature;
 
use App\Livewire\ChatRoom as ChatRoomComponent;
use App\Models\Category;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\Item;
use App\Models\ItemRequest;
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
}
