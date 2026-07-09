<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class Review extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'item_request_id',
        'reviewer_id',
        'reviewee_id',
        'rating',
        'comment'
    ];
 
    protected $casts = [
        'rating' => 'integer',
    ];
 
    public function itemRequest(): BelongsTo
    {
        return $this->belongsTo(ItemRequest::class);
    }
 
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
 
    public function reviewee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewee_id');
    }
}
