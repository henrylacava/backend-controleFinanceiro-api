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
        for ($i = 1; $i <= 7; $i++){
            $despesa_categoria = Despesa::where('categoria_id', $i)->get(['categoria_id','valor']);

            if (!$despesa_categoria->isEmpty()){
                return response()->json([$receita_soma, $despesa_soma, $saldo_final, $despesa_categoria], 200);
            }
        }
    }
}
