<?php

use App\Models\Graph;
use App\Models\Node;
use App\Models\NodeNeighbor;
use Illuminate\Database\Seeder;

class GraphsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $colors = [
            "#004D40",
            "#00695C",
            "#2e003e",
            "#3d2352",
            "#05386B",
            "#379683",
            "#022140",
            "#265077",
            "#0c0023",
            "#190061",
            "#38003c",
            "#e90052",
            "#330136",
            "#5E1742",
            "#191226",
            "#F2355B",
            "#702C8E",
            "#ED1651",
            "#2A4C59",
            "#A62D43",
            "#103754",
            "#D53D13",
            "#332851",
            "#CA3074",
            "#2D4057",
            "#4097AA",
            "#214D72",
            "#2C7695",
            "#071E22",
            "#EE2E31",
            "#434858",
            "#FC6453",
            "#651e3e",
            "#851e3e",
            "#0072ff",
            "#00c6ff",
            "#34495e",
            "#41b883",
            "#2b2d5c",
            "#3465d8",
            "#323E40",
            "#D97D0D",
            "#1D1D2C",
            "#E40C2B",
            "#1D1D2C",
            "#C3002F",
            "#306073",
            "#F2385A",
            "#0f256e",
            "#01a168",
            "#05004E",
            "#fd5f00",
            "#3e1063",
            "#401372",
            "#2F2440",
            "#BA0F30",
        ]
        ;
        $n = mt_rand(1, 50);
        for ($i = 0; $i < $n; $i++) {
            $graph = new Graph([
                'name' => 'Graph n ° ' . ($i+1),
                'description' => 'description of Graph n ° ' . $i,
            ]);

            $graph->save();
            $n_nodes = mt_rand(1, 10);

            for ($j = 0; $j < $n_nodes; $j++) {
                $x = mt_rand(20, 900);
                $y = mt_rand(20, 500);
                $color = mt_rand(0, 55);
                $node = new Node([
                    'tooltip' => 'node ' . ($j+1) . ' of graph n ° ' . $i,
                    'x' => $x,
                    'y' => $y,
                    'color' => $colors[$color],
                    'id_graph'=>$graph->id,
                ]);
                $node->save();

               
            }

            //take some random nodes and add relations

            $links=mt_rand(2, $n_nodes*2);

            for ($k=0; $k < $links; $k++) { 
               $node_1=Node::where('id_graph',$graph->id)->get()->random();;
               $node_2=Node::where('id_graph',$graph->id)->get()->random();;
               
               if($node_1->id!=$node_2->id ){
                NodeNeighbor::create([
                    'id_node' => $node_1->id,
                    'id_node_neighbor' => $node_2->id,
                ]);
               }
            }
        }
    }
}
