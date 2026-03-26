<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Search users by username, email, or name
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (! $query) {
            return response()->json([
                'success' => false,
                'message' => 'Query parameter is required',
            ], 422);
        }

        $users = User::where(function ($q) use ($query) {
            $q->where('username', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhere('name', 'like', "%{$query}%");
        })
            ->where('id', '!=', auth()->id()) // Exclude current user
            ->select('id', 'name', 'username', 'email', 'created_at', 'updated_at')
            ->paginate(20);

        $users->getCollection()->transform(function ($user) {
            if ($user->created_at) {
                $user->created_at_formatted = date('d M Y, H:i', strtotime($user->created_at));
                $user->created_at_ago       = $user->created_at->diffInMinutes(now()) < 5
                    ? 'just now'
                    : $user->created_at->diffForHumans();
            }

            if ($user->updated_at) {
                $user->updated_at_formatted = date('d M Y, H:i', strtotime($user->updated_at));
                $user->updated_at_ago       = $user->updated_at->diffInMinutes(now()) < 5
                    ? 'just now'
                    : $user->updated_at->diffForHumans();
            }

            // Check if authenticated user is following this user
            $user->is_follow = auth()->user()->isFollowing($user->id);

            // Add profile photo URL if exists
            $user->profile_photo_url = $user->profile_photo
                ? asset('storage/' . $user->profile_photo)
                : null;

            unset($user->created_at, $user->updated_at);
            return $user;
        });

        return response()->json([
            'success' => true,
            'data'    => $users,
        ]);
    }

    public function myProfile()
    {
        $user    = auth()->user();
        $user_id = $user->id;

        return $this->show($user_id);
    }

    public function myFollowing()
    {
        $user    = auth()->user();
        $user_id = $user->id;

        return $this->following($user_id);
    }

    public function myFollowers()
    {
        $user    = auth()->user();
        $user_id = $user->id;

        return $this->followers($user_id);
    }

    public function myTopics()
    {
        $user    = auth()->user();
        $user_id = $user->id;

        return $this->topics($user_id);
    }

    /**
     * Get user profile
     */
    public function show($id)
    {
        $user = User::withCount(['topics', 'followers', 'following'])
            ->find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        if ($user->created_at) {
            $user->created_at_formatted = date('d M Y, H:i', strtotime($user->created_at));
            $user->created_at_ago       = $user->created_at->diffInMinutes(now()) < 5
                ? 'just now'
                : $user->created_at->diffForHumans();
        }

        if ($user->updated_at) {
            $user->updated_at_formatted = date('d M Y, H:i', strtotime($user->updated_at));
            $user->updated_at_ago       = $user->updated_at->diffInMinutes(now()) < 5
                ? 'just now'
                : $user->updated_at->diffForHumans();
        }

        $data              = $user->toArray();
        $data['is_follow'] = auth()->user()->isFollowing($id);
        unset($data['created_at'], $data['updated_at']);

        // tambahkan field is_you
        $data['is_you'] = auth()->id() == $user->id;

        // tambahkan profile photo URL jika ada
        if ($user->profile_photo) {
            $data['profile_photo_url'] = asset('storage/' . $user->profile_photo);
        } else {
            $data['profile_photo_url'] = null;
        }

        return response()->json([
            'success' => true,
            'data'    => $data,
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
                'message' => 'You cannot follow yourself',
            ], 422);
        }

        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $currentUser = auth()->user();

        if ($currentUser->isFollowing($id)) {
            return response()->json([
                'success' => false,
                'message' => 'You are already following this user',
            ], 422);
        }

        $currentUser->following()->attach($id);

        return response()->json([
            'success' => true,
            'message' => 'User followed successfully',
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
                'message' => 'Invalid operation',
            ], 422);
        }

        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $currentUser = auth()->user();

        if (! $currentUser->isFollowing($id)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not following this user',
            ], 422);
        }

        $currentUser->following()->detach($id);

        return response()->json([
            'success' => true,
            'message' => 'User unfollowed successfully',
        ]);
    }

    /**
     * Get followers of a user
     */
    public function followers($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $followers = $user->followers()
            ->select('users.id', 'users.name', 'users.username', 'users.email', 'users.created_at', 'users.updated_at')
            ->paginate(20);

        $followers->getCollection()->transform(function ($follower) {
            if ($follower->created_at) {
                $follower->created_at_formatted = date('d M Y, H:i', strtotime($follower->created_at));
                $follower->created_at_ago       = $follower->created_at->diffInMinutes(now()) < 5
                    ? 'just now'
                    : $follower->created_at->diffForHumans();
            }

            if ($follower->updated_at) {
                $follower->updated_at_formatted = date('d M Y, H:i', strtotime($follower->updated_at));
                $follower->updated_at_ago       = $follower->updated_at->diffInMinutes(now()) < 5
                    ? 'just now'
                    : $follower->updated_at->diffForHumans();
            }

            // Check if authenticated user is following this user
            $follower->is_follow = auth()->user()->isFollowing($follower->id);

            // Add profile photo URL if exists
            $follower->profile_photo_url = $follower->profile_photo
                ? asset('storage/' . $follower->profile_photo)
                : null;

            unset($follower->created_at, $follower->updated_at, $follower->pivot);
            return $follower;
        });

        return response()->json([
            'success' => true,
            'data'    => $followers,
        ]);
    }

    /**
     * Get following of a user
     */
    public function following($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $following = $user->following()
            ->select('users.id', 'users.name', 'users.username', 'users.email', 'users.created_at', 'users.updated_at')
            ->paginate(20);

        $following->getCollection()->transform(function ($user) {
            if ($user->created_at) {
                $user->created_at_formatted = date('d M Y, H:i', strtotime($user->created_at));
                $user->created_at_ago       = $user->created_at->diffInMinutes(now()) < 5
                    ? 'just now'
                    : $user->created_at->diffForHumans();
            }

            if ($user->updated_at) {
                $user->updated_at_formatted = date('d M Y, H:i', strtotime($user->updated_at));
                $user->updated_at_ago       = $user->updated_at->diffInMinutes(now()) < 5
                    ? 'just now'
                    : $user->updated_at->diffForHumans();
            }

            // Check if authenticated user is following this user
            $user->is_follow = auth()->user()->isFollowing($user->id);

            // Add profile photo URL if exists
            $user->profile_photo_url = $user->profile_photo
                ? asset('storage/' . $user->profile_photo)
                : null;

            unset($user->created_at, $user->updated_at, $user->pivot);
            return $user;
        });

        return response()->json([
            'success' => true,
            'data'    => $following,
        ]);
    }

    /**
     * Get topics created by a user
     */
    public function topics($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $topics = $user->topics()
            ->with(['user', 'category:id,name', 'comments', 'likes'])
            ->withCount(['comments', 'likes'])
            ->latest()
            ->paginate(20);

        // Transform data untuk format timestamps dan field tambahan
        $topics->getCollection()->transform(function ($topic) {
            // Format timestamps untuk topic
            if ($topic->created_at) {
                $topic->created_at_formatted = date('d M Y, H:i', strtotime($topic->created_at));
                $topic->created_at_ago       = $topic->created_at->diffInMinutes(now()) < 5
                    ? 'just now'
                    : $topic->created_at->diffForHumans();
            }

            if ($topic->updated_at) {
                $topic->updated_at_formatted = date('d M Y, H:i', strtotime($topic->updated_at));
                $topic->updated_at_ago       = $topic->updated_at->diffInMinutes(now()) < 5
                    ? 'just now'
                    : $topic->updated_at->diffForHumans();
            }

            unset($topic->created_at, $topic->updated_at);

            // Format timestamps untuk user
            if ($topic->user) {
                if ($topic->user->created_at) {
                    $topic->user->created_at_formatted = date('d M Y, H:i', strtotime($topic->user->created_at));
                    $topic->user->created_at_ago       = $topic->user->created_at->diffInMinutes(now()) < 5
                        ? 'just now'
                        : $topic->user->created_at->diffForHumans();
                }

                if ($topic->user->updated_at) {
                    $topic->user->updated_at_formatted = date('d M Y, H:i', strtotime($topic->user->updated_at));
                    $topic->user->updated_at_ago       = $topic->user->updated_at->diffInMinutes(now()) < 5
                        ? 'just now'
                        : $topic->user->updated_at->diffForHumans();
                }

                // Add profile photo URL
                $topic->user->profile_photo_url = $topic->user->profile_photo
                    ? asset('storage/' . $topic->user->profile_photo)
                    : null;

                unset($topic->user->created_at, $topic->user->updated_at);
            }

            // Format timestamps untuk comments
            foreach ($topic->comments as $comment) {
                if ($comment->created_at) {
                    $comment->created_at_formatted = date('d M Y, H:i', strtotime($comment->created_at));
                    $comment->created_at_ago       = $comment->created_at->diffInMinutes(now()) < 5
                        ? 'just now'
                        : $comment->created_at->diffForHumans();
                }

                if ($comment->updated_at) {
                    $comment->updated_at_formatted = date('d M Y, H:i', strtotime($comment->updated_at));
                    $comment->updated_at_ago       = $comment->updated_at->diffInMinutes(now()) < 5
                        ? 'just now'
                        : $comment->updated_at->diffForHumans();
                }

                unset($comment->created_at, $comment->updated_at);
            }

            // Format timestamps untuk likes
            foreach ($topic->likes as $like) {
                if ($like->created_at) {
                    $like->created_at_formatted = date('d M Y, H:i', strtotime($like->created_at));
                    $like->created_at_ago       = $like->created_at->diffInMinutes(now()) < 5
                        ? 'just now'
                        : $like->created_at->diffForHumans();
                }

                if ($like->updated_at) {
                    $like->updated_at_formatted = date('d M Y, H:i', strtotime($like->updated_at));
                    $like->updated_at_ago       = $like->updated_at->diffInMinutes(now()) < 5
                        ? 'just now'
                        : $like->updated_at->diffForHumans();
                }

                // Add profile photo URL for liked user
                $like->profile_photo_url = $like->profile_photo
                    ? asset('storage/' . $like->profile_photo)
                    : null;

                unset($like->created_at, $like->updated_at, $like->pivot);
            }

            // Check if authenticated user has liked this topic
            $topic->is_like = $topic->likes->contains('id', auth()->id());

            return $topic;
        });

        return response()->json([
            'success' => true,
            'data'    => $topics,
        ]);
    }

    /**
     * Upload profile photo
     */
    public function uploadProfilePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
        ]);

        $user = auth()->user();

        // Delete old photo if exists
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Get file extension
        $extension = $request->file('photo')->getClientOriginalExtension();

        // Create filename: [username].[extension]
        $filename = $user->username . '.' . $extension;

        // Store new photo with custom filename
        $path = $request->file('photo')->storeAs('profile_photos', $filename, 'public');

        // Update user profile
        $user->profile_photo = $path;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile photo uploaded successfully',
            'data'    => [
                'profile_photo'     => $path,
                'profile_photo_url' => asset('storage/' . $path),
            ],
        ]);
    }
}
