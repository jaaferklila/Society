<?php

namespace App\Models;
use App\Models\Vanne;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
   public function Vanne()
   {
       return $this->belongsTo('App\Models\Vanne');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
