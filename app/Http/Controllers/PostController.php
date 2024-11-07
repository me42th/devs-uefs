<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    // Listar todos os posts
    public function index(): JsonResponse
    {
        $posts = Post::with('tags', 'user')->get();
        return response()->json($posts, 200);
    }

    // Criar um novo post
    public function store(StorePostRequest $request): JsonResponse
    {
        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => $request->user_id,
        ]);

        // Sincronizar as tags, se existirem
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return response()->json($post->load('tags', 'user'), 201);
    }

    // Obter um post específico
    public function show($id): JsonResponse
    {
        $post = Post::with('tags', 'user')->find($id);

        if (!$post) {
            return response()->json(['message' => 'Post não encontrado'], 404);
        }

        return response()->json($post, 200);
    }

    // Atualizar um post existente
    public function update(UpdatePostRequest $request, $id): JsonResponse
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post não encontrado'], 404);
        }

        $post->update($request->validated());

        // Sincronizar as tags, se existirem
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return response()->json($post->load('tags', 'user'), 200);
    }

    // Excluir um post
    public function destroy($id): JsonResponse
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post não encontrado'], 404);
        }

        $post->delete();

        return response()->json(['message' => 'Post deletado com sucesso'], 200);
    }
}