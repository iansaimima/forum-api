<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Topic;

class TopicLikeController extends Controller
{
    /**
     * Toggle like/unlike a topic
     */
    public function toggle($topicId)
    {
        $topic = Topic::find($topicId);

        if (! $topic) {
            return response()->json([
                'success' => false,
                'message' => 'Topic not found',
            ], 404);
        }

        $userId  = auth()->id();
        $isLiked = $topic->isLikedBy($userId);

        if ($isLiked) {
            // Unlike
            $topic->likes()->detach($userId);
            $message = 'Topic unliked successfully';
            $liked   = false;
        } else {
            // Like
            $topic->likes()->attach($userId);
            $message = 'Topic liked successfully';
            $liked   = true;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => [
                'liked'       => $liked,
                'likes_count' => $topic->likes()->count(),
            ],
        ]);
    }

    /**
     * Get users who liked a topic
     */
    public function users($topicId)
    {
        $topic = Topic::find($topicId);

        if (! $topic) {
            return response()->json([
                'success' => false,
                'message' => 'Topic not found',
            ], 404);
        }

        $users = $topic->likes()->paginate(20);

        $users->getCollection()->transform(function ($user) {
            $user->created_at_formatted = date('d M Y, H:i', strtotime($user->created_at));
            $user->updated_at_formatted = date('d M Y, H:i', strtotime($user->updated_at));

            // ago formatted
            $user->updated_at_ago = $user->updated_at->diffInMinutes(now()) < 5
                ? 'just now'
                : $user->updated_at->diffForHumans();
            $user->created_at_ago = $user->created_at->diffInMinutes(now()) < 5
                ? 'just now'
                : $user->created_at->diffForHumans();
            unset($user->created_at, $user->updated_at, $user->pivot);
            return $user;
        });

        return response()->json([
            'success' => true,
            'data'    => $users,
        ]);
    }
}
