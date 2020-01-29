<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    //
    protected $fillable = [
        'name_reg', 'surname_reg', 'name', 'email', 'password', 'secretQ', 'secretA', 'phone', 'sector', 'description'
    ];

    public function ofertas()
    {
        return $this->hasMany('App\Oferta');
    }

    
}
