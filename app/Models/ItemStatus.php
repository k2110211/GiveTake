<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemStatus extends Model
{
    use HasFactory;

    protected $table = 'item_statuses';

    protected $fillable = ['name', 'color'];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'item_status_id');
    }
}
