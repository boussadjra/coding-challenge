<template>
  <div class="cc-editor">
    <div class="cc-editor-toolbar">
      <b-form-group>
        <b-form-radio-group
          id="btn-radios-1"
          v-model="shape"
          :options="shapes"
          buttons
          name="radios-btn-default"
        ></b-form-radio-group>
      </b-form-group>
      <b-button-group>
        <b-button variant="success" class="mb-2" @click="action='add'">
          <b-icon icon="plus" aria-hidden="true"></b-icon>
        </b-button>
        <b-button variant="info" class="mb-2" @click="action='edit'">
          <b-icon icon="pencil" aria-hidden="true"></b-icon>
        </b-button>
        <b-button variant="danger" class="mb-2" @click="action='remove'">
          <b-icon icon="trash" aria-hidden="true"></b-icon>
        </b-button>
      </b-button-group>
      <div class="cc-editor-info">
        <b-form-input v-model="graph.name" placeholder="Enter graph name"></b-form-input>
        <b-form-input v-model="graph.description" placeholder="Enter graph description"></b-form-input>
      </div>
      <b-button variant="primary" class="mb-2" @click="save">
        Save
        <b-icon icon="check" aria-hidden="true"></b-icon>
      </b-button>
    </div>

    <div class="cc-edit-area">
      <svg width="100%" height="100%" @click="draw">
        <template v-for="(_link, index) in links">
          <graph-link :key="'l'+index" :link="_link" :id="'node'+index"></graph-link>
        </template>
        <template v-for="(node, index) in  graph.nodes">
          <node :id="'node'+index" :node="node"></node>
          <b-tooltip :target="'node'+index">{{node.tooltip}}</b-tooltip>
        </template>
      </svg>
      <b-modal hide-backdrop button-size="sm" @ok="addNode" v-model="modalShow" size="sm">
        <b-form-input v-model="node.tooltip" placeholder="Enter the node tooltip"></b-form-input>
      </b-modal>
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
  name: "edit-graph",

  data() {
    return {
      mode: "add",
      graph: {
        name: "",
        description: "",
        nodes: []
      },

      links: [],
      action: "add",
      shape: "circle",
      shapes: [
        { text: "Node", value: "circle" },
        { text: "Link", value: "line" }
      ],
      node: {
        tooltip: "",
        neighbors: []
      },
      link: {
        start: {},
        end: {}
      },
      modalShow: false
    };
  },

  watch: {
    async $route(to, from) {
      if (this.$route.params.id) {
        this.mode = "edit";
        let result = await GraphService.findById(this.$route.params.id);

        this.refresh(result);
      } else {
        this.mode = "add";

        this.graph = {
          name: "",
          description: "",
          nodes: []
        };
      }
    }
  },
  computed: {
    neighbors() {
      let _neighbors = [];
      this.graph.nodes.forEach(_node => {
        _node.neighbors = _node.neighbors.forEach(neighbor => {
          let f_node = this.graph.nodes.find(n => {
            return n.x === neighbor.x && n.y === neighbor.y;
          });

          _neighbors.push({
            node1: _node,
            node2: f_node
          });
        });
      });
      return _neighbors;
    }
  },
  methods: {
    addNode() {
      this.graph.nodes.push({ ...this.node });
      this.node.tooltip = "";
    },
    getNodeByTarget(e) {
      return this.graph.nodes.find(node => {
        return (
          node.x === e.target.cx.baseVal.value &&
          node.y === e.target.cy.baseVal.value
        );
      });
    },
    draw(e) {
      if (this.action === "add") {
        if (this.shape === "circle") {
          this.node = {
            ...this.node,
            x: e.offsetX,
            y: e.offsetY,
            color: colors[Math.round(Math.random() * 56) - 1]
          };
          this.modalShow = true;
        } else {
          //if the target element is a circle (node)
          if (e.target.cy) {
            //save the target node coordinate in order to create links
            //that join these nodes
            if (!this.link.start.x) {
              this.link.start.x = e.target.cx.baseVal.value;
              this.link.start.y = e.target.cy.baseVal.value;
              this.link.startNode = this.getNodeByTarget(e);
            } else if (!this.link.end.x) {
              this.link.end.x = e.target.cx.baseVal.value;
              this.link.end.y = e.target.cy.baseVal.value;
              this.link.endNode = this.getNodeByTarget(e);
            }
            //if we have chosen two nodes we create the link
            if (this.link.start.x && this.link.end.x) {
              this.links.push({
                x1: this.link.start.x,
                x2: this.link.end.x,
                y1: this.link.start.y,
                y2: this.link.end.y,
                color: colors[Math.round(Math.random() * 56) - 1],
                startNode: this.link.startNode,
                endNode: this.link.endNode
              });

              //reset the link
              this.link = {
                start: {},
                end: {}
              };
            }
          }
        }
      } else if (this.action === "remove") {
        this.graph.nodes = this.graph.nodes.filter(node => {
          return (
            node.x !== e.target.cx.baseVal.value &&
            node.y !== e.target.cy.baseVal.value
          );
        });
      }
    },
    async save() {
      if (this.mode === "edit") {
        let result = await GraphService.update(this.graph);
        this.refresh(result);
      } else {
        let result = await GraphService.save(this.graph);
        let _nodes = [];
        let tmpNodes = [...this.graph.nodes];
        this.graph.nodes.forEach(async _node => {
          _node.id_graph = result.data.id;
          let storedNode = await NodeService.save(_node);
          let neighbors = this.getNeighbors(storedNode.data, tmpNodes);
          NodeService.saveNeighbors(storedNode.data, neighbors)
            .then(res => {})
            .catch(err => {});
          _nodes.push({ ...storedNode.data, neighbors });
        });

        this.graph.nodes = _nodes;
      }
    },

    getNeighbors(_node, tmpNodes) {
      let neighbors = [];
      this.links.forEach(_link => {
        if (_link.startNode.x === _node.x && _link.startNode.y === _node.y) {
          let filtered = tmpNodes.filter(gNode => {
            return gNode.x === _link.endNode.x && gNode.y === _link.endNode.y;
          });

          neighbors = [...neighbors, ...filtered];
        }
        if (_link.endNode.x === _node.x && _link.endNode.y === _node.y) {
          let filtered = tmpNodes.filter(gNode => {
            return (
              gNode.x === _link.startNode.x && gNode.y === _link.startNode.y
            );
          });

          neighbors = [...neighbors, ...filtered];
        }
      });
      return neighbors;
    },
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

