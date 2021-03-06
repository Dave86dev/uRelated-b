<?php

namespace App\Http\Controllers;

use App\Suscripcion;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;


class SuscripcionController extends Controller
{
    //Obtener todas las suscripciones
    public function getAll(){
        return Suscripcion::all();
    }

    //Numero de suscritos a una oferta
    public function cuentaSuscritos(Request $request){

        //contamos el número de suscripciones que hay según id de oferta 

        $idOferta = $request->query('id_oferta');

        return Suscripcion::where('idoferta', '=', $idOferta)
        ->count();
    }

    public function existeCandidato(Request $request){

        //comprobar si existe la id de un usuario en una suscripción dado determinado id de oferta

        $id = $request->query('id_candidato');
        $idoferta = $request->query('id_oferta');

        return Suscripcion::where('idusuario', '=', $id)
        ->where('idoferta','=',$idoferta)
        ->get();
    }

    //Crea una nueva suscripcion
    public function nuevaSuscripcion(Request $request){

        //variables por body para generar una nueva suscripción

        $id_oferta = $request->input('id_oferta');
        $id_candidato = $request->input('id_usuario');
        $date = $request->input('date');
        $estado = 1;

        try {

            return Suscripcion::create(
                [
                    'estado' => $estado,
                    'fecha_sus' => $date,
                    'idusuario' => $id_candidato,
                    'idoferta' => $id_oferta
                ]);


        } catch(QueryException $err) {
             echo ($err);
        }
    }

    //Suscripciones por Usuario
    public function suscripcionesPorU(Request $request){

        //buscamos todas las suscripciones por usuario, con el filtro añadido del estado de la misma. (when al final)

        $id = $request->query('id_usuario');
        $estado = $request->query('estado');

        return Suscripcion::selectRaw('usuarios.id, ofertas.id AS idoferta, ofertas.titulo, ofertas.ciudad, ofertas.tipo_contrato, empresas.id AS idempresa, empresas.name, suscripcions.estado, suscripcions.id AS idsuscrip, suscripcions.fecha_sus')
        ->join('usuarios', 'suscripcions.idusuario', '=', 'usuarios.id')
        ->join('ofertas', 'suscripcions.idoferta', '=', 'ofertas.id')
        ->join('empresas', 'ofertas.idempresa', '=', 'empresas.id')
        ->where('usuarios.id', '=', $id)
        ->when($estado, function ($query, $estado) {
            $query->where('suscripcions.estado', '=', $estado);
        })
        ->get();
    }


    //Suscripciones de usuarios que la empresa puede gestionar
    public function suscripcionesPorE(Request $request){
        
        //buscamos las suscripciones que tiene una empresa en concreto dado su id 


        //obtenemos el id por url
        $id = $request->query('id_oferta');
        

        return Suscripcion::selectRaw('usuarios.id, usuarios.name, usuarios.surname, 
        usuarios.ciudad AS usuciudad, suscripcions.id AS idsuscrip, suscripcions.estado, 
        suscripcions.fecha_sus, ofertas.titulo, ofertas.fecha_publi, ofertas.salario, 
        ofertas.ciudad, ofertas.sector')
        ->join('usuarios', 'suscripcions.idusuario', '=', 'usuarios.id')
        ->join('ofertas', 'suscripcions.idoferta', '=', 'ofertas.id')
        ->join('empresas', 'ofertas.idempresa', '=', 'empresas.id')
        ->where('ofertas.id', '=', $id)
        ->get();

        
    }

    //Modifica el estado de una suscripcion
    public function modSuscripcion(Request $request){

        $id_suscripcion = $request->input('id_suscripcion');
        $estado = $request->input('estado');

        return Suscripcion::where ('id', '=', $id_suscripcion)
        ->update(['estado' => $estado]);
    }

    //Elimina una suscripcion (candidatura)
    public function delSuscripcion(Request $request){

        $id_suscripcion = $request->query('id_suscripcion');
        
        return Suscripcion::where ('id', '=', $id_suscripcion)
        ->delete();
    }
}

