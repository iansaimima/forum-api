<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicLikeController extends Controller
{
    /**
     * Toggle like/unlike a topic
     */
    public function toggle($topicId)
    {
        $topic = Topic::find($topicId);

        if (!$topic) {
            return response()->json([
                'success' => false,
                'message' => 'Topic not found'
            ], 404);
        }

        $userId = auth()->id();
        $isLiked = $topic->isLikedBy($userId);

        if ($isLiked) {
            // Unlike
            $topic->likes()->detach($userId);
            $message = 'Topic unliked successfully';
            $liked = false;
        } else {
            // Like
            $topic->likes()->attach($userId);
            $message = 'Topic liked successfully';
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'liked' => $liked,
                'likes_count' => $topic->likes()->count()
            ]
        ]);
    }

    /**
     * Get users who liked a topic
     */
    public function users($topicId)
    {
        $topic = Topic::find($topicId);

        if (!$topic) {
            return response()->json([
                'success' => false,
                'message' => 'Topic not found'
            ], 404);
        }

        $users = $topic->likes()->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }
}
