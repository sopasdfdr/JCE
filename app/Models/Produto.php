<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'prd_id'; // Specify the primary key column


    // Table name
    protected $table = 'produtos';

    // Fillable attributes
    protected $fillable = [
        'prd_nome',
        'prd_codigo',
        'prd_tipo',
    ];

    // Relationship: A product has many formulacoes
    public function formulacoes()
    {
        return $this->hasMany(Formulacoes::class, 'prd_id', 'prd_id');
    }
}
