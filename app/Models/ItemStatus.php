<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemStatus extends Model
{
    use HasFactory;

    public const AVAILABLE = 1;      // Có sẵn
    public const CLOSED = 2;         // Đã đóng / Ẩn
    public const RESERVED = 3;       // Đang hẹn giao nhận / Đang trao đổi
    public const COMPLETED = 4;      // Trao đổi xong / Hoàn thành
    public const PENDING = 5;        // Chờ duyệt (Admin pre-moderation)
    public const REJECTED = 6;       // Từ chối duyệt

    protected $table = 'item_statuses';

    protected $fillable = ['name', 'color'];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'item_status_id');
    }
}
