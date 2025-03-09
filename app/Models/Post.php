<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'user_id',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('Y-m-d');
    }
    
    public function getFullDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('l, F j, Y - h:i A');
    }
    public function comments()
    {
    return $this->morphMany(Comment::class, 'commentable');
    }
    
}
