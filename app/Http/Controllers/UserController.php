<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Listar todos os usuários
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    // Criar um novo usuário
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json($user, 201);
    }

    // Obter um usuário específico
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
        return response()->json($user, 200);
    }

    // Atualizar um usuário existente
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->validated());

        return response()->json($user, 200);
    }

    // Excluir um usuário
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'Usuário deletado com sucesso'], 200);
    }
}