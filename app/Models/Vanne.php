<?php

namespace App\models;
use App\Models\Facture;
use Illuminate\Database\Eloquent\Model;

class Vanne extends Model
{
public function user(){
    return $this->belongsTo('App\User');
}
    public function factures(){
        return $this->hasMany('App\Models\Facture');

    }

}
