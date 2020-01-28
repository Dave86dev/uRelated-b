<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Usuario;

class UsuarioController extends Controller
{
    //Obtener usuario por email
    public function getEmailU($email){
        return Usuario::where('email', 'LIKE', $email)->get();
    }

    //Login de usuario con las credenciales de email y password
    public function getLoginU($param1, $param2){

        //encontramos al usuario en concreto
        $q = Usuario::where('email', 'LIKE', $param1)
         ->where('password', 'LIKE', $param2)->first()->id;

         //si existe, generamos el token
         if($q != null){
            $length = 50;
            $token = bin2hex(random_bytes($length));

            //guardamos el token en su campo correspondiente
            Usuario::where('id', '=', $q)
            ->update(['token' => $token]);

            //devolvemos al front la info necesaria ya actualizada
            return Usuario::where('email', 'LIKE', $param1)
            ->where('password', 'LIKE', $param2)->get();
         }
         return;
         
    }

    //Logout de usuario borrando el campo token
    public function getLogOutU($id){
        //hacemos update en el campo token del usuario

        $token_empty = "";

        return Usuario::where('id', '=', $id)
        ->update(['token' => $token_empty]);
    }

    //Actualizar perfil de usuario
    public function perfilUMod($id, $paramPhone, $paramEmail, $paramCiudad,
    $paramProvincia, $paramPais, $paramName, $paramSurname){
        return Usuario::where('id', '=', $id)
        ->update(['phone' => $paramPhone, 'email' => $paramEmail,
        'ciudad' => $paramCiudad, 'provincia' => $paramProvincia, 
        'pais' => $paramPais, 'name' => $paramName, 'surname' => $paramSurname]);
        
    }

    public function getPerfilU ($id){
        return Usuario::all()->where('id', '=', $id)
        ->makeHidden(['password']);
       
        //muestra 1 en el resultado en la web

    }


    
}

