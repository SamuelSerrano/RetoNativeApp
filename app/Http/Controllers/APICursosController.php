<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cursos;
class APICursosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cursos::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $curso = new Cursos();
        $curso->nombre = $request->nombre;
        $curso->horario = $request->horario;
        $curso->fechainicio = $request->fechainicio;
        $curso->fechafin = $request->fechafin;
        $curso->save();

        return $curso;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $curso = Cursos::find($id);
        $curso->nombre = $request->nombre;
        $curso->horario = $request->horario;
        $curso->fechainicio = $request->fechainicio;
        $curso->fechafin = $request->fechafin;
        $curso->save();

        return $curso;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $curso = Cursos::find($id);
        $curso->delete();
    }
}
