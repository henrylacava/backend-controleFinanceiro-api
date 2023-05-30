<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    public function despesas()
    {
        return $this->hasMany(Despesa::class, 'categoria_id');
    }
}
