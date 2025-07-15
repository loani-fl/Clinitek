<?php

namespace App\Http\Controllers;

use App\Models\Medicamento;
use Illuminate\Http\Request;

class MedicamentoController extends Controller
{
    public function search(Request $request)
    {
        $term = $request->get('q');
        $medicamentos = Medicamento::where('nombre', 'LIKE', '%' . $term . '%')
            ->limit(10)
            ->pluck('nombre');

        return response()->json($medicamentos);
    }
}
