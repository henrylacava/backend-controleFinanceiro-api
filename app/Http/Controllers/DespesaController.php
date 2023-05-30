<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Http\Requests\UpdateRequest;
use App\Models\Despesa;
use Illuminate\Support\Facades\Validator;

class DespesaController extends Controller
{
    public function index()
    {
        $search_param = request('descricao');
        if ($search_param) {
            $despesas = Despesa::where('descricao', 'LIKE', '%'.$search_param.'%')->get();
        } else {
            $despesas = Despesa::all();
        }

        return response()->json($despesas, 200);
    }

    public function store(StoreRequest $request)
    {
        // Request na descricao e na data
        $data = $request->data;
        $descricao = $request->descricao;

        //Pega apenas o mês da data
        $mes = date('m', strtotime($data));

        // Verifica se já existe um registro com o mesmo mês e mesma descricao
        $verifica = Despesa::whereRaw('MONTH(data) = ?', [$mes])->where('descricao', $descricao)->exists();

        if ($verifica) { // Se existir retorna uma resposta de erro
            $despesa = Despesa::whereRaw('MONTH(data) = ?', [$mes])->where('descricao', $descricao)->get();

            return response()->json(['Já existe um registro igual a esse', $despesa],409);
        } else { // Se não existir ele cria no banco de dados
            $despesa = new Despesa($request->all());
            $despesa->save();

            return response()->json($despesa, 201);
        }
    }

    public function show(int $id)
    {
        $despesa = Despesa::find($id);

        return response()->json($despesa, 200);
    }

    public function update(int $id, UpdateRequest $request)
    {
        $despesa = Despesa::find($id);
        $despesa->fill($request->all());
        $despesa->save();

        return response()->json($despesa, 200);
    }

    public function destroy(int $id)
    {
        Despesa::find($id)->delete();

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

        $receitas = Despesa::whereRaw('YEAR(data) = ?', [$ano])->whereRaw('MONTH(data) = ?', [$mes])->get();
        return response()->json($receitas, 200);
    }

}
