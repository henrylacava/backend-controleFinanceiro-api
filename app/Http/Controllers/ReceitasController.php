<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Http\Requests\UpdateRequest;
use App\Models\Receita;
use Illuminate\Support\Facades\Validator;

class ReceitasController extends Controller
{
    public function index()
    {
        $search_param = request('descricao');
        if ($search_param){
            $receitas = Receita::where('descricao', 'LIKE', '%'.$search_param.'%')->get();
        }else {
            $receitas = Receita::all();
        }
        return response()->json($receitas, 200);
    }

    public function store(StoreRequest $request)
    {
        // Request na descricao e na data
        $data = $request->data;
        $descricao = $request->descricao;

        // Pega apenas o mês da data
        $mes = date('m', strtotime($data));

        // Verifica se já existe um registro com o mesmo mês e mesma descricao
        $verifica= Receita::whereRaw('MONTH(data) = ?', [$mes])->where('descricao', $descricao)->exists();

        if ($verifica) { // Se existir retorna uma resposta de erro
            $receita = Receita::whereRaw('MONTH(data) = ?', [$mes])->where('descricao', $descricao)->get();

            return response()->json(['Já existe um registro igual a esse', $receita],409);
        } else { // Se não existir ele cria no banco de dados
            $receita = new Receita($request->all());
            $receita->save();

            return response()->json($receita, 201);
        }
    }

    public function show(int $id)
    {
        $receita = Receita::find($id);

        return response()->json($receita, 200);
    }

    public function update(int $id, UpdateRequest $request)
    {
        $receita = Receita::find($id);
        $receita->fill($request->all());
        $receita->save();

        return response()->json($receita, 200);
    }

    public function destroy(int $id)
    {
        Receita::find($id)->delete();

        return response()->noContent();
    }

    public function showByYearAndMonth($ano, $mes){
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

        $receitas = Receita::whereRaw('YEAR(data) = ?', [$ano])->whereRaw('MONTH(data) = ?', [$mes])->get();
        return response()->json($receitas, 200);
    }
}
