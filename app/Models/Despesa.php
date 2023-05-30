<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Despesa extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['descricao', 'valor', 'data', 'categoria_id'];
    protected $attributes = ['categoria_id' => 1];

    public function categorias()
    {
        return $this->belongsTo(Categoria::class);
    }
}
