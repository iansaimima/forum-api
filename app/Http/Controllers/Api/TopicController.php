<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\TopicCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TopicController extends Controller
{
    /**
     * Display a listing of topics
     */
    public function index()
    {
        $topics = Topic::with(['user', 'category:id,name', 'comments', 'likes'])
            ->withCount(['comments', 'likes'])
            ->latest()
            ->paginate(20);

        // Map data untuk menambahkan field createdAtAgo
        $topics->getCollection()->transform(function ($topic) {
            // Format timestamps untuk topic
            if ($topic->created_at) {
                $topic->created_at_formatted = date('d M Y, H:i', strtotime($topic->created_at));
                $topic->created_at_ago = $topic->created_at->diffInMinutes(now()) < 5
                    ? 'just now'
                    : $topic->created_at->diffForHumans();
            }

            if ($topic->updated_at) {
                $topic->updated_at_formatted = date('d M Y, H:i', strtotime($topic->updated_at));
                $topic->updated_at_ago = $topic->updated_at->diffInMinutes(now()) < 5
                    ? 'just now'
                    : $topic->updated_at->diffForHumans();
            }

            unset($topic->created_at, $topic->updated_at);

            // Format timestamps untuk user
            if ($topic->user) {
                if ($topic->user->created_at) {
                    $topic->user->created_at_formatted = date('d M Y, H:i', strtotime($topic->user->created_at));
                    $topic->user->created_at_ago = $topic->user->created_at->diffInMinutes(now()) < 5
                        ? 'just now'
                        : $topic->user->created_at->diffForHumans();
                }

                if ($topic->user->updated_at) {
                    $topic->user->updated_at_formatted = date('d M Y, H:i', strtotime($topic->user->updated_at));
                    $topic->user->updated_at_ago = $topic->user->updated_at->diffInMinutes(now()) < 5
                        ? 'just now'
                        : $topic->user->updated_at->diffForHumans();
                }

                unset($topic->user->created_at, $topic->user->updated_at);
            }

            // Format timestamps untuk comments
            foreach ($topic->comments as $comment) {
                if ($comment->created_at) {
                    $comment->created_at_formatted = date('d M Y, H:i', strtotime($comment->created_at));
                    $comment->created_at_ago = $comment->created_at->diffInMinutes(now()) < 5
                        ? 'just now'
                        : $comment->created_at->diffForHumans();
                }

                if ($comment->updated_at) {
                    $comment->updated_at_formatted = date('d M Y, H:i', strtotime($comment->updated_at));
                    $comment->updated_at_ago = $comment->updated_at->diffInMinutes(now()) < 5
                        ? 'just now'
                        : $comment->updated_at->diffForHumans();
                }

                unset($comment->created_at, $comment->updated_at);
            }

            // Format timestamps untuk likes
            foreach ($topic->likes as $like) {
                if ($like->created_at) {
                    $like->created_at_formatted = date('d M Y, H:i', strtotime($like->created_at));
                    $like->created_at_ago = $like->created_at->diffInMinutes(now()) < 5
                        ? 'just now'
                        : $like->created_at->diffForHumans();
                }

                if ($like->updated_at) {
                    $like->updated_at_formatted = date('d M Y, H:i', strtotime($like->updated_at));
                    $like->updated_at_ago = $like->updated_at->diffInMinutes(now()) < 5
                        ? 'just now'
                        : $like->updated_at->diffForHumans();
                }

                unset($like->created_at, $like->updated_at, $like->pivot);
            }
            return $topic;
        });

        return response()->json([
            'success' => true,
            'data'    => $topics,
        ]);
    }

    /**
     * Store a newly created topic
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'         => 'required|string|max:255',
            'body'          => 'required|string',
            'category_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 200);
        }

        // Find or create category
        $category = TopicCategory::firstOrCreate(
            ['name' => $request->category_name]
        );

        $topic = Topic::create([
            'title'             => $request->title,
            'body'              => $request->body,
            'user_id'           => auth()->id(),
            'topic_category_id' => $category->id,
        ]);

        $topic->load(['user', 'category']);

        return response()->json([
            'success' => true,
            'message' => 'Topic created successfully',
            'data'    => $topic,
        ], 201);
    }

    /**
     * Display the specified topic
     */
    public function show($id)
    {
        $topic = Topic::with(['user', 'category:id,name', 'comments.user', 'likes'])
            ->withCount(['comments', 'likes'])
            ->find($id);

        if (! $topic) {
            return response()->json([
                'success' => false,
                'message' => 'Topic not found',
            ], 404);
        }

        // Format timestamps untuk topic
        if ($topic->created_at) {
            $topic->created_at_formatted = date('d M Y, H:i', strtotime($topic->created_at));
            $topic->created_at_ago = $topic->created_at->diffInMinutes(now()) < 5
                ? 'just now'
                : $topic->created_at->diffForHumans();
        }

        if ($topic->updated_at) {
            $topic->updated_at_formatted = date('d M Y, H:i', strtotime($topic->updated_at));
            $topic->updated_at_ago = $topic->updated_at->diffInMinutes(now()) < 5
                ? 'just now'
                : $topic->updated_at->diffForHumans();
        }

        unset($topic->created_at, $topic->updated_at);

        // Format timestamps untuk user
        if ($topic->user) {
            if ($topic->user->created_at) {
                $topic->user->created_at_formatted = date('d M Y, H:i', strtotime($topic->user->created_at));
                $topic->user->created_at_ago = $topic->user->created_at->diffInMinutes(now()) < 5
                    ? 'just now'
                    : $topic->user->created_at->diffForHumans();
            }

            if ($topic->user->updated_at) {
                $topic->user->updated_at_formatted = date('d M Y, H:i', strtotime($topic->user->updated_at));
                $topic->user->updated_at_ago = $topic->user->updated_at->diffInMinutes(now()) < 5
                    ? 'just now'
                    : $topic->user->updated_at->diffForHumans();
            }

            unset($topic->user->created_at, $topic->user->updated_at);
        }

        // Format timestamps untuk comments
        foreach ($topic->comments as $comment) {
            if ($comment->created_at) {
                $comment->created_at_formatted = date('d M Y, H:i', strtotime($comment->created_at));
                $comment->created_at_ago = $comment->created_at->diffInMinutes(now()) < 5
                    ? 'just now'
                    : $comment->created_at->diffForHumans();
            }

            if ($comment->updated_at) {
                $comment->updated_at_formatted = date('d M Y, H:i', strtotime($comment->updated_at));
                $comment->updated_at_ago = $comment->updated_at->diffInMinutes(now()) < 5
                    ? 'just now'
                    : $comment->updated_at->diffForHumans();
            }

            unset($comment->created_at, $comment->updated_at);
        }

        // Format timestamps untuk likes
        foreach ($topic->likes as $like) {
            if ($like->created_at) {
                $like->created_at_formatted = date('d M Y, H:i', strtotime($like->created_at));
                $like->created_at_ago = $like->created_at->diffInMinutes(now()) < 5
                    ? 'just now'
                    : $like->created_at->diffForHumans();
            }

            if ($like->updated_at) {
                $like->updated_at_formatted = date('d M Y, H:i', strtotime($like->updated_at));
                $like->updated_at_ago = $like->updated_at->diffInMinutes(now()) < 5
                    ? 'just now'
                    : $like->updated_at->diffForHumans();
            }

            unset($like->created_at, $like->updated_at, $like->pivot);
        }

        return response()->json([
            'success' => true,
            'data'    => $topic,
        ]);
    }

    /**
     * Update the specified topic
     */
    public function update(Request $request, $id)
    {
        $topic = Topic::find($id);

        if (! $topic) {
            return response()->json([
                'success' => false,
                'message' => 'Topic not found',
            ], 404);
        }

        // Check if user is the owner
        if ($topic->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this topic',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title'         => 'sometimes|required|string|max:255',
            'body'          => 'sometimes|required|string',
            'category_name' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 200);
        }

        // Update category if provided
        if ($request->has('category_name')) {
            $category = TopicCategory::firstOrCreate(
                ['name' => $request->category_name]
            );
            $topic->topic_category_id = $category->id;
        }

        if ($request->has('title')) {
            $topic->title = $request->title;
        }

        if ($request->has('body')) {
            $topic->body = $request->body;
        }

        $topic->save();
        $topic->load(['user', 'category']);

        return response()->json([
            'success' => true,
            'message' => 'Topic updated successfully',
            'data'    => $topic,
        ]);
    }

    /**
     * Remove the specified topic
     */
    public function destroy($id)
    {
        $topic = Topic::find($id);

        if (! $topic) {
            return response()->json([
                'success' => false,
                'message' => 'Topic not found',
            ], 404);
        }

        // Check if user is the owner
        if ($topic->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this topic',
            ], 403);
        }

        $topic->delete();

        return response()->json([
            'success' => true,
            'message' => 'Topic deleted successfully',
        ]);
    }
}
