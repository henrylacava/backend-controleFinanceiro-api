<?php

namespace App\Http\Controllers;

use App\Models\Despesa;
use App\Models\Receita;
use Illuminate\Support\Facades\Validator;

class ResumoController extends Controller
{
    public function summaryMonth($ano, $mes)
    {
        $validator = Validator::make([
            'ano' => $ano,
            'mes' => $mes
        ], [
            'ano' => ['date_format:Y'],
            'mes' => ['date_format:m']
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $receita_soma = Receita::whereRaw('YEAR(data) = ?', [$ano])->whereRaw('MONTH(data) = ?', [$mes])->sum('valor');
        $despesa_soma = Despesa::whereRaw('YEAR(data) = ?', [$ano])->whereRaw('MONTH(data) = ?', [$mes])->sum('valor');
        $saldo_final = $receita_soma - $despesa_soma;
        $despesa_categoria = Despesa::whereIn('categoria_id', range(1,7))
            ->select('categoria_id', 'valor')
            ->whereRaw('YEAR(data) = ?', [$ano])
            ->whereRaw('MONTH(data) = ?', [$mes])
            ->get();

        if ($despesa_categoria){
            $data = [
                (object) ['chave' => 'receita_soma', 'valor' => $receita_soma],
                (object) ['chave' => 'despesa_soma', 'valor' => $despesa_soma],
                (object) ['chave' => 'saldo_final', 'valor' => $saldo_final],
                (object) ['chave' => 'despesa_categoria', 'valor' => $despesa_categoria]
            ];
            return response()->json($data, 200);
         }
    }
}
