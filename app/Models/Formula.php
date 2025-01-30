<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formula extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'fml_id'; // Specify the primary key column

    // Table name (optional if it matches the default pluralization)
    protected $table = 'formulas';

    // Fillable attributes
    protected $fillable = [
        'fml_nome',
        'fml_codigo',
        'fml_qtde',
        'fml_nrprodutos',
        'fml_datacriacao',
        'fml_dataalteracao',
    ];

    // Relationship: A formula has many formulacoes
    public function formulacoes()
    {
        return $this->hasMany(Formulacoes::class, 'fml_id', 'fml_id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'prd_id', 'prd_id');
    }
}
