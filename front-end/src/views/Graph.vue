<template>
  <div class="cc-editor">
    <div class="cc-editor-toolbar">
      <div>{{graph.name}}</div>
      <b-button
        variant="primary"
        class="mb-2"
        @click="$router.push('/graphs/'+graph.id+'/edit')"
      >
        Edit
        <b-icon icon="pencil" aria-hidden="true"></b-icon>
      </b-button>
    </div>

    <div class="cc-edit-area">
      <svg width="100%" height="100%">
        <template v-for="(_link, index) in links">
          <graph-link :key="'l'+index" :link="_link" :id="'node'+index"></graph-link>
        </template>
        <template v-for="(node, index) in  graph.nodes">
          <node :id="'node'+index" :node="node"></node>
          <b-tooltip :target="'node'+index">{{node.tooltip}}</b-tooltip>
        </template>
      </svg>
    </div>
  </div>
</template>

<script>
import { COLORS as colors } from "../utils/constants.js";
import GraphService from "../services/GraphService.js";
import NodeService from "../services/NodeService.js";

import GraphLink from "../components/GraphLink";
import Node from "../components/Node";
export default {
  name: "graph",

  data() {
    return {
     
      graph: {
        name: "",
        description: "",
        nodes: []
      },

      links: []
    };
  },

  methods: {
    refresh(result) {
      this.graph = result.data;
      this.graph.nodes = this.graph.nodes.map(node => {
        node.neighbors = node.node_neighbors;
        
        return node;
      });
      this.graph.nodes.forEach(node=>{
        node.neighbors.forEach(neighbor=>{
     this.links.push({
                x1: node.x,
                x2: neighbor.x,
                y1: node.y,
                y2: neighbor.y,
                color: colors[Math.round(Math.random() * 56) - 1],
                startNode: node,
                endNode:neighbor
              });
        })
       
      })
    }
  },
  components: {
    GraphLink,
    Node
  },
  async mounted() {
    if (this.$route.params.id) {
      this.mode = "edit";
      let result = await GraphService.findById(this.$route.params.id);

      this.refresh(result);
    }
  }
};
</script>

