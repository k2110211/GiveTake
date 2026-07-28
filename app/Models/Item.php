<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'thumbnail',
        'images',
        'type_id',
        'exchange_wish',
        'min_karma',
        'winner_id',
        'item_status_id',
        'city_id',
        'district_id'
    ];

    protected $casts = [
        'images' => 'array',
        'min_karma' => 'integer',
    ];

    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ItemStatus::class, 'item_status_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(ItemRequest::class);
    }
}
