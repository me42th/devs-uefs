<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    // Listar todas as tags
    public function index(): JsonResponse
    {
        $tags = Tag::all();
        return response()->json($tags, 200);
    }

    // Criar uma nova tag
    public function store(StoreTagRequest $request): JsonResponse
    {
        $tag = Tag::create($request->validated());
        return response()->json($tag, 201);
    }

    // Obter uma tag específica
    public function show($id): JsonResponse
    {
        $tag = Tag::find($id);

        if (!$tag) {
            return response()->json(['message' => 'Tag não encontrada'], 404);
        }

        return response()->json($tag, 200);
    }

    // Atualizar uma tag existente
    public function update(UpdateTagRequest $request, $id): JsonResponse
    {
        $tag = Tag::find($id);

        if (!$tag) {
            return response()->json(['message' => 'Tag não encontrada'], 404);
        }

        $tag->update($request->validated());

        return response()->json($tag, 200);
    }

    // Excluir uma tag
    public function destroy($id): JsonResponse
    {
        $tag = Tag::find($id);

        if (!$tag) {
            return response()->json(['message' => 'Tag não encontrada'], 404);
        }

        $tag->delete();

        return response()->json(['message' => 'Tag deletada com sucesso'], 200);
    }
}