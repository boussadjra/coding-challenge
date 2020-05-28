<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $fillable = ["tooltip","x","y","color","id_graph"];

    public function graph()
    {
        return $this->belongsTo('App\Models\Graph', 'id_graph');
    }

    public function nodeNeighbors()
    {
        return $this->belongsToMany('App\Models\Node', 'node_neighbors', 'id_node', 'id_node_neighbor');

    }
}
