<?php

namespace App\Http\Controllers;

use App\Models\Service_order_model;
use Illuminate\Http\Request;

class service_order extends Controller
{
    private $request;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (empty($request->limit)) $request->limit = 5;

        $service_orders = Service_order_model::with('user');
        $total_orders = Service_order_model::all()->count();
        $paginas = ceil(intval($total_orders) / intval($request->limit));

        if (!empty($request->page)) {
            if (intval($request->page) > $paginas) $request->page = $paginas;
            $service_orders->offset(intval($request->page) * intval($request->limit) - intval($request->limit));
        }

        if (!empty($request->search)) {
            $service_orders->whereRaw('lower(vehiclePlate) = ?',  mb_strtolower($request->search, 'UTF-8'));
        }

        $service_orders->orderBy(!empty($request->sort) ? $request->sort : 'entryDateTime', strtoupper($request->order) === 'ASC' ? 'ASC' : 'DESC');
        $service_orders->limit($request->limit);

        return response()->json([
            'total' => $total_orders,
            'pages' => $paginas,
            'service_orders' => $service_orders->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->request = $request;
        $this->_validar();

        $service_order = Service_order_model::create($request->all());

        return response()->json(["result" => !empty($service_order), "id" => $service_order->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (empty($id) || !is_numeric($id)) return response()->json(['result' => false, 'message' => 'Missing or invalid service_order id.'], 400);

        $service_order =  Service_order_model::with('user')->find($id);

        if (empty($service_order)) return response()->json(['result' => false, 'message' => 'Service_order not found.'], 404);

        return response()->json([
            'service_order' => $service_order
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        if (empty($request->id) || !is_numeric($request->id)) return response()->json(['result' => false, 'message' => 'Missing or invalid service_order id.'], 400);

        $this->request = $request;
        $this->_validar();

        $service_order = Service_order_model::find($request->id);

        if (empty($service_order)) return response()->json(['result' => false, 'message' => 'Service_order not found.'], 404);

        $service_order->update($request->all());

        return response()->json([
            "result" => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (empty($id) || !is_numeric($id)) return response()->json(['result' => false, 'message' => 'Missing or invalid service_order id.'], 400);

        $service_order = Service_order_model::find($id);

        if (empty($service_order)) return response()->json(['result' => false, 'message' => 'Service_order not found.'], 404);

        return response()->json([
            'result' => $service_order->delete()
        ]);
    }

    private function _validar()
    {
        $campos = [
            "vehiclePlate" => "required",
            "entryDateTime" => "required|date_format:Y-m-d H:i:s",
            "exitDateTime" => "nullable|date_format:Y-m-d H:i:s",
            "priceType" => "nullable",
            "price" => "nullable|decimal",
            "userId" => "required|integer",
        ];

        $this->request->validate($campos, [], ["name" => "nome"]);
    }
}
