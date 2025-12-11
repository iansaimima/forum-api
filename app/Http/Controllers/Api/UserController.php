<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Search users by username, email, or name
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([
                'success' => false,
                'message' => 'Query parameter is required'
            ], 422);
        }

        $users = User::where(function($q) use ($query) {
                $q->where('username', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('name', 'like', "%{$query}%");
            })
            ->where('id', '!=', auth()->id()) // Exclude current user
            ->select('id', 'name', 'username', 'email', 'created_at')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Get user profile
     */
    public function show($id)
    {
        $user = User::withCount(['topics', 'followers', 'following'])
            ->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $data = $user->toArray();
        $data['is_following'] = auth()->user()->isFollowing($id);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Follow a user
     */
    public function follow($id)
    {
        if ($id == auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot follow yourself'
            ], 422);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $currentUser = auth()->user();

        if ($currentUser->isFollowing($id)) {
            return response()->json([
                'success' => false,
                'message' => 'You are already following this user'
            ], 422);
        }

        $currentUser->following()->attach($id);

        return response()->json([
            'success' => true,
            'message' => 'User followed successfully'
        ]);
    }

    /**
     * Unfollow a user
     */
    public function unfollow($id)
    {
        if ($id == auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid operation'
            ], 422);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $currentUser = auth()->user();

        if (!$currentUser->isFollowing($id)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not following this user'
            ], 422);
        }

        $currentUser->following()->detach($id);

        return response()->json([
            'success' => true,
            'message' => 'User unfollowed successfully'
        ]);
    }

    /**
     * Get followers of a user
     */
    public function followers($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $followers = $user->followers()
            ->select('users.id', 'users.name', 'users.username', 'users.email')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $followers
        ]);
    }

    /**
     * Get following of a user
     */
    public function following($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $following = $user->following()
            ->select('users.id', 'users.name', 'users.username', 'users.email')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $following
        ]);
    }
}
