<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = [
        'title',
        'body',
        'user_id',
        'topic_category_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(TopicCategory::class, 'topic_category_id');
    }

    public function comments()
    {
        return $this->hasMany(TopicComment::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'topic_user_likes');
    }

    public function isLikedBy($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }
}
