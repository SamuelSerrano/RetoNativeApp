<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\EstudianteCurso;
use App\Models\Cursos;

class CursoController extends Controller
{
    public function index()
    {
        return view('curso');
    }

    public function GetTopCurso(){
        $data = DB::table('cursos')
        ->join('estudiante_cursos', 'estudiante_cursos.idCurso', '=', 'cursos.id')
        ->select('cursos.nombre', DB::raw('count(estudiante_cursos.idCurso) as cantidad'))        
        ->groupBy('cursos.nombre')
        ->orderBy('cantidad', 'desc')
        ->take(3)
        ->get();
        return $data;
    }
}
