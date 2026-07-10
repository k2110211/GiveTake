<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{roomId}', function ($user, $roomId) {
    $room = \App\Models\ChatRoom::with('itemRequest.item')->find($roomId);
    if (!$room) {
        return false;
    }
    return (int) $user->id === (int) $room->itemRequest->user_id ||
           (int) $user->id === (int) $room->itemRequest->item->user_id;
});
