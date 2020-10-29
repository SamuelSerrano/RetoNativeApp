<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiantes;
use App\Models\EstudianteCurso;
use App\Models\Cursos;

class EstudianteController extends Controller
{
    public function index()
    {
        return view('estudiante');
    }

    public function AsignarCurso(Request $request)
    {
        //SDSE - Se verifica que no este asignado el curso previamente al estudiante.
        $cantidadCurso = EstudianteCurso::where('idestudiante',$request->idEstudiante)
            ->where('idCurso',$request->idCurso)
            ->count();
        
        if($cantidadCurso == 0)
        {
            //SDSE - Se Asigna el curso.
            $relcurso = new EstudianteCurso();
            $relcurso->idestudiante = $request->idEstudiante;
            $relcurso->idcurso = $request->idCurso;
            $relcurso->save();
            return 1; //Return 1:True / 0:False 
        }
        else{
            return 0; //Return 1:True / 0:False 
        }        
    }

    public function ListarCursos(Request $request)
    {
        $data = Cursos::select('cursos.nombre', 'cursos.horario')
                ->join('estudiante_cursos', 'estudiante_cursos.idcurso', '=', 'cursos.id')
                ->where('estudiante_cursos.idestudiante',$request->idEstudiante)
                ->get();

        return $data;
    }

}
