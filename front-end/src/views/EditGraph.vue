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
        <line
          v-for="(link, index) in links"
          :key="'l'+index"
          :x1="link.x1"
          :y1="link.y1"
          :x2="link.x2"
          :y2="link.y2"
          :stroke="link.color"
          style="stroke-width:2"
          :id="'node'+index"
        ></line>
        <template v-for="(node, index) in  graph.nodes">
          <circle
            :id="'node'+index"
            :cx="node.x"
            :cy="node.y"
            r="16"
            stroke-width="1"
            :fill="node.color"
          ></circle>
         <!-- <text
            :x="node.x-4"
            :y="node.y+6"
            fill="white"
          >{{node.id?node.id:graph.nodes[graph.nodes.length-2].id+1}}</text>-->

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
            if (
              this.link.start.x &&
              this.link.end.x &&
              this.link.endNode.id !== this.link.startNode.id
            ) {
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
    save() {
        this.graph.nodes=this.graph.nodes.map(node=>{
            node.neighbors=[];
            return node;
          })
      this.links.forEach(link => {
        //startNode
        let indexStartNode = this.graph.nodes.findIndex(node => {
          return node.id === link.startNode.id;
        });
        let node = this.graph.nodes[indexStartNode];
       
        node.neighbors.push(link.endNode);

        this.$set(this.graph.nodes, indexStartNode, node);
        //endNode
        let indexEndNode = this.graph.nodes.findIndex(node => {
          return node.id === link.endNode.id;
        });
         node = this.graph.nodes[indexEndNode];
       
        node.neighbors.push(link.startNode);

        this.$set(this.graph.nodes, indexEndNode, node);
      });
      /* if(this.mode === "edit"){
         GraphService.update(this.graph).then((result) => {
           this.refresh()
         }).catch((err) => {
           
         });

      }else{
         GraphService.save(this.graph).then((result) => {
           this.refresh()
           
         }).catch((err) => {
           
         });;

      }*/
    },
    refresh() {
      GraphService.findById(this.$route.params.id)
        .then(result => {
          this.graph = result.data;
          this.graph.nodes=this.graph.nodes.map(node=>{
            node.neighbors=[];
            return node;
          })
        })
        .catch(err => {});
    }
  },
  mounted() {
    if (this.$route.params.id) {
      this.mode = "edit";
      this.refresh();
    }
  }
};
</script>

<style>
.cc-editor {
  padding: 10px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
.cc-editor-toolbar {
  display: flex;
  justify-content: space-around;
  align-items: baseline;
  height: auto;
  width: 100%;
}

.cc-editor-info {
  display: grid;
  grid-template-columns: 1fr 1fr;
  grid-gap: 4px;
  justify-content: center;
  align-items: center;

  width: 512px;
}
.cc-edit-area {
  cursor: crosshair;
  height: 500px;
  width: 100%;
  border: 2px solid rgb(136, 134, 134);
  margin-top: 4px;
  background-color: transparent;
  background-image: linear-gradient(
      0deg,
      transparent 24%,
      #efefef 25%,
      #efefef 26%,
      transparent 27%,
      transparent 74%,
      #efefef 75%,
      #efefef 76%,
      transparent 77%,
      transparent
    ),
    linear-gradient(
      90deg,
      transparent 24%,
      #efefef 25%,
      #efefef 26%,
      transparent 27%,
      transparent 74%,
      #efefef 75%,
      #efefef 76%,
      transparent 77%,
      transparent
    );
  background-size: 64px 64px;
}
</style>
