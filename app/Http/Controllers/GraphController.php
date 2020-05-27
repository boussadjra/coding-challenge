<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Graph;
use App\Models\Node;
use Illuminate\Http\Request;

class GraphController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Graph::with('nodes')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $graph = new Graph($request->all());

        $graph->save();

        foreach ($request->nodes as $key => $node) {
            $nodeObj = new Node($node);
            $nodeObj->id_graph = $graph->id;
            $nodeObj->save();
        }
        return $graph;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Graph  $graph
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Graph::with('nodes')->findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Graph  $graph
     * @return \Illuminate\Http\Response
     */
    public function edit(Graph $graph)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Graph  $graph
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $graph = Graph::findOrFail($id)->load('nodes');

        $graph->update($request->all());
$reqNodes=[];
        //adding new nodes to updated graph
        foreach ($request->nodes as $key => $reqNode) {
            if (count($graph->nodes->find($reqNode)) == 0) {
                $nodeObj = new Node($reqNode);
                $nodeObj->id_graph = $graph->id;
                $nodeObj->save();
               array_push($reqNodes,$nodeObj);
            }else{
                array_push($reqNodes,$reqNode);

            }
        }

        //remove nodes
        foreach ($graph->nodes as $key => $node) {
     
                $collection = collect($reqNodes);
                $filtered = $collection->filter(function ($value, $key) use ($node) {
                    return !isset($value['id'])?true: $value['id']==$node->id;
                });
             if( $filtered->count()==0){
                $nodeObj = Node::findOrFail($node->id);
                $nodeObj->delete();
             }
              
            
        }

        return $reqNodes;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Graph  $graph
     * @return \Illuminate\Http\Response
     */
    public function destroy(Graph $graph)
    {
        //
    }
}
