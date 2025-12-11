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
        $topics = Topic::with(['user', 'category', 'comments', 'likes'])
            ->withCount(['comments', 'likes'])
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $topics
        ]);
    }

    /**
     * Store a newly created topic
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'category_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Find or create category
        $category = TopicCategory::firstOrCreate(
            ['name' => $request->category_name]
        );

        $topic = Topic::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => auth()->id(),
            'topic_category_id' => $category->id,
        ]);

        $topic->load(['user', 'category']);

        return response()->json([
            'success' => true,
            'message' => 'Topic created successfully',
            'data' => $topic
        ], 201);
    }

    /**
     * Display the specified topic
     */
    public function show($id)
    {
        $topic = Topic::with(['user', 'category', 'comments.user', 'likes'])
            ->withCount(['comments', 'likes'])
            ->find($id);

        if (!$topic) {
            return response()->json([
                'success' => false,
                'message' => 'Topic not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $topic
        ]);
    }

    /**
     * Update the specified topic
     */
    public function update(Request $request, $id)
    {
        $topic = Topic::find($id);

        if (!$topic) {
            return response()->json([
                'success' => false,
                'message' => 'Topic not found'
            ], 404);
        }

        // Check if user is the owner
        if ($topic->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this topic'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'body' => 'sometimes|required|string',
            'category_name' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
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
            'data' => $topic
        ]);
    }

    /**
     * Remove the specified topic
     */
    public function destroy($id)
    {
        $topic = Topic::find($id);

        if (!$topic) {
            return response()->json([
                'success' => false,
                'message' => 'Topic not found'
            ], 404);
        }

        // Check if user is the owner
        if ($topic->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this topic'
            ], 403);
        }

        $topic->delete();

        return response()->json([
            'success' => true,
            'message' => 'Topic deleted successfully'
        ]);
    }
}
