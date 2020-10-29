<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstudianteCurso extends Model
{
    use HasFactory;
    protected $table = "estudiante_cursos";
    protected $fillable = [
        'idestudiante',
        'idcurso'
    ];
}
