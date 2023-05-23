<?php

namespace App\Http\Controllers;

use App\Models\User_model;
use Illuminate\Http\Request;

class user extends Controller
{

    private $request;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (empty($request->limit)) $request->limit = 5;

        $usuarios = User_model::query();
        $total_usuarios = User_model::all()->count();
        $paginas = ceil(intval($total_usuarios) / intval($request->limit));

        if (!empty($request->page)) {
            if (intval($request->page) > $paginas) $request->page = $paginas;
            $usuarios->offset(intval($request->page) * intval($request->limit) - intval($request->limit));
        }

        if (!empty($request->search)) {
            $usuarios->whereRaw('lower(name) like ?', "%" . mb_strtolower($request->search, 'UTF-8') . "%");
        }

        $usuarios->limit($request->limit);

        return response()->json([
            'total' => $total_usuarios,
            'pages' => $paginas,
            'users' => $usuarios->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->request = $request;
        $this->_validar();

        $user = User_model::create($request->all());

        return response()->json(["result" => !empty($user), "id" => $user->id], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (empty($id) || !is_numeric($id)) return response()->json(['result' => false, 'message' => 'Missing or invalid user id.'], 400);

        $user = User_model::find($id);

        if (empty($user)) return response()->json(['result' => false, 'message' => 'User not found.'], 404);

        return response()->json([
            'user' => User_model::find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        if (empty($request->id) || !is_numeric($request->id)) return response()->json(['result' => false, 'message' => 'Missing or invalid user id.'], 400);

        $user = User_model::find($request->id);

        if (empty($user)) return response()->json(['result' => false, 'message' => 'User not found.'], 404);

        $user->update($request->all());

        return response()->json([
            "result" => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (empty($id) || !is_numeric($id)) return response()->json(['result' => false, 'message' => 'Missing or invalid user id.'], 400);

        $usuario = User_model::find($id);

        if (empty($usuario)) return response()->json(['result' => false, 'message' => 'User not found.'], 404);

        return response()->json([
            'result' => $usuario->delete()
        ]);
    }

    private function _validar()
    {
        $this->request->validate(["name" => "required|string|min:5"], [], ["name" => "nome"]);
    }
}
