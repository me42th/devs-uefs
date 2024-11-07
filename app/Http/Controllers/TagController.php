<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    // Listar todas as tags
    public function index()
    {
        return response()->json(Tag::all(), 200);
    }

    // Criar uma nova tag
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tags',
        ]);

        $tag = Tag::create([
            'name' => $request->name,
        ]);

        return response()->json($tag, 201);
    }

    // Obter uma tag específica
    public function show($id)
    {
        $tag = Tag::find($id);
        if (!$tag) {
            return response()->json(['message' => 'Tag não encontrada'], 404);
        }
        return response()->json($tag, 200);
    }

    // Atualizar uma tag existente
    public function update(Request $request, $id)
    {
        $tag = Tag::find($id);
        if (!$tag) {
            return response()->json(['message' => 'Tag não encontrada'], 404);
        }

        $request->validate([
            'name' => 'string|max:255|unique:tags,name,' . $id,
        ]);

        $tag->update([
            'name' => $request->name ?? $tag->name,
        ]);

        return response()->json($tag, 200);
    }

    // Excluir uma tag
    public function destroy($id)
    {
        $tag = Tag::find($id);
        if (!$tag) {
            return response()->json(['message' => 'Tag não encontrada'], 404);
        }

        $tag->delete();
        return response()->json(['message' => 'Tag deletada com sucesso'], 200);
    }
}