<template>
  <div class="cc-graph-stats">
    <div>
      <h4>Graph statistics :</h4>
      <h5>Name : {{graph.name}}</h5>
      <h5>Description : {{graph.description}}</h5>
          <b-button variant="primary" size="sm" @click="$router.push('/graphs/'+graph.id)">
          show
        <b-icon icon="eye" aria-hidden="true"></b-icon>
          
          </b-button>

    </div>
    <b-card-group deck>
      <b-card
        border-variant="primary"
        header="Number of nodes"
        header-bg-variant="primary"
        header-text-variant="white"
        align="center"
      >
        <b-card-text>
          <h2>{{graph.nodes.length}}</h2>
        </b-card-text>
      </b-card>
      <b-card
        border-variant="success"
        header="Number of relations"
        header-bg-variant="success"
        header-text-variant="white"
        align="center"
      >
        <b-card-text>
          <h2>{{relationsCount}}</h2>
        </b-card-text>
      </b-card>

      <b-card
        border-variant="dark"
        header="Date"
        header-bg-variant="dark"
        header-text-variant="white"
        align="center"
      >
        <b-card-text>
          <p>Created at {{new Date(graph.created_at).toLocaleDateString()}}</p>
          <p>Updated at {{new Date(graph.updated_at).toLocaleDateString()}}</p>
        </b-card-text>
      </b-card>
    </b-card-group>

    <div>
      <h4>Nodes Table :</h4>
      <b-table bordered hover :items="nodes"/>
    </div>
  </div>
</template>

<script>
import GraphService from "../services/GraphService.js";
import NodeService from "../services/NodeService.js";

export default {
  name: "graph-stats",

  data() {
    return {
      graph: {
        name: "",
        description: "",
        nodes: []
      }
    };
  },
  computed: {
    relationsCount() {
      return (
        this.graph.nodes.reduce((acc, curr) => {
          return (acc += curr.neighbors.length);
        }, 0) / 2
      );
    },
    nodes() {
      return this.graph.nodes.map(node => {
        return {
          nodeID: node.id,
          nodeTooltip: node.tooltip,
          nodeNeighbors: node.neighbors.map(ng => ng.id).join()
        };
      });
    }
  },

  methods: {
    refresh(result) {
      this.graph = result.data;
      this.graph.nodes = this.graph.nodes.map(node => {
        node.neighbors = node.node_neighbors;

        return node;
      });
    }
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

<style>
.cc-graph-stats {
  padding: 10px;
  display: grid;
  grid-gap: 40px;
}
</style>
