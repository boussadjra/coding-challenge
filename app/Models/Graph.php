<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Graph extends Model
{
    protected $fillable=["name","description"];

    public function nodes(){
        return $this->hasMany('App\Models\Node','id_graph');
    }

}
