<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;

class EmpresaController extends Controller
{
    //
    public function getEmailE($email){
        return Empresa::where('email', 'LIKE', $email)->get();
    }

    public function getLoginE($param1, $param2){
        
        //encontramos a la empresa en concreto
        $q = Empresa::where('email', 'LIKE', $param1)
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


}
