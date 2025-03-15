<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $fillable = ['title', 'description', 'slug', 'user_id', 'image'];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'onUpdate' => true,  // Update slug when the title changes
            ]
        ];
    }

   // Inside the Post model, setImageAttribute method
// Remove this method as it's handled in the controller
// public function setImageAttribute($value)
// {
//     if ($value) {
//         $imagePath = $value->store('images', 'public');
//         $this->attributes['image'] = $imagePath;
//     }
// }
public function deleteOldImage()
{
    if ($this->image) {
        // Delete old image from storage
        Storage::disk('public')->delete($this->image);
    }
}


    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}

