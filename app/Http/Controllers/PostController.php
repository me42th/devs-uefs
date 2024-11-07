<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Posts",
 *     description="API Endpoints for managing posts"
 * )
 */
class PostController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/posts",
     *     tags={"Posts"},
     *     summary="List all posts",
     *     @OA\Response(
     *         response=200,
     *         description="A list of posts"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $posts = Post::with('tags', 'user')->get();
        return response()->json($posts, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/posts",
     *     tags={"Posts"},
     *     summary="Create a new post",
     *     @OA\RequestBody(
     *         required=true
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post created successfully"
     *     )
     * )
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => $request->user_id,
        ]);

        // Associar tags se forem fornecidas
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return response()->json($post->load('tags', 'user'), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     tags={"Posts"},
     *     summary="Get post by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        $post = Post::with('tags', 'user')->find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response()->json($post, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/posts/{id}",
     *     tags={"Posts"},
     *     summary="Update a post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     )
     * )
     */
    public function update(UpdatePostRequest $request, $id): JsonResponse
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $post->update($request->validated());

        // Atualizar as tags associadas, se fornecidas
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return response()->json($post->load('tags', 'user'), 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     tags={"Posts"},
     *     summary="Delete a post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Post deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $post->delete();
        return response()->json(['message' => 'Post deleted successfully'], 200);
    }

    /**
     * @OA\Schema(
     *     schema="Post",
     *     required={"title", "content", "user_id"},
     *     @OA\Property(property="id", type="integer", readOnly=true),
     *     @OA\Property(property="title", type="string"),
     *     @OA\Property(property="content", type="string"),
     *     @OA\Property(property="user_id", type="integer", description="ID of the user who created the post"),
     *     @OA\Property(property="created_at", type="string", format="date-time", readOnly=true),
     *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true),
     *     @OA\Property(property="tags", type="array", @OA\Items(type="integer"))
     * )
     *
     * @OA\Schema(
     *     schema="PostRequest",
     *     required={"title", "content", "user_id"},
     *     @OA\Property(property="title", type="string", example="My First Post"),
     *     @OA\Property(property="content", type="string", example="This is the content of the post."),
     *     @OA\Property(property="user_id", type="integer", example=1),
     *     @OA\Property(property="tags", type="array", @OA\Items(type="integer", example=1), description="Array of tag IDs")
     * )
     */
}