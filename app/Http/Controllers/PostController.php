<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Listar todos os posts
    public function index()
    {
        return response()->json(Post::with('tags', 'user')->get(), 200);
    }

    // Criar um novo post
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => $request->user_id,
        ]);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return response()->json($post->load('tags', 'user'), 201);
    }

    // Obter um post específico
    public function show($id)
    {
        $post = Post::with('tags', 'user')->find($id);
        if (!$post) {
            return response()->json(['message' => 'Post não encontrado'], 404);
        }
        return response()->json($post, 200);
    }

    // Atualizar um post existente
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post não encontrado'], 404);
        }

        $request->validate([
            'title' => 'string|max:255',
            'content' => 'string',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        $post->update([
            'title' => $request->title ?? $post->title,
            'content' => $request->content ?? $post->content,
        ]);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return response()->json($post->load('tags', 'user'), 200);
    }

    // Excluir um post
    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post não encontrado'], 404);
        }

        $post->delete();
        return response()->json(['message' => 'Post deletado com sucesso'], 200);
    }
}