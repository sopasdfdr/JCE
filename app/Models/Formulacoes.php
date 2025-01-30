<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formulacoes extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'formulacoes';
    public $timestamps = false;
    protected $primaryKey = 'fmc_id'; // Specify the primary key column

    // Fillable attributes
    protected $fillable = [
        'fml_id',
        'prd_id',
        'fmc_qtde',
        'fmc_prioridade',
    ];

    // Relationship: A formulacao belongs to a formula
    public function formula()
    {
        return $this->belongsTo(Formula::class, 'fml_id', 'fml_id');
    }

    // Relationship: A formulacao belongs to a produto
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'prd_id', 'prd_id');
    }
}
