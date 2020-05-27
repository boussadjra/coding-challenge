<template>
  <div class="cc-editor">
    <b-form-group>
      <b-form-radio-group
        id="btn-radios-1"
        v-model="shape"
        :options="shapes"
        buttons
        name="radios-btn-default"
      ></b-form-radio-group>
    </b-form-group>

      
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
        ></line>
        <circle
          v-for="(node, index) in  nodes"
          :key="index"
          :cx="node.x"
          :cy="node.y"
          r="16"
          stroke-width="1"
          :fill="node.color"
        ></circle>

     
      </svg>
    </div>
  </div>
</template>

<script>

import {COLORS as colors} from '../utils/constants.js'

export default {
  name: "graph",

  data() {
    return {
      nodes: [],
      links: [],
      shape: "circle",
      shapes: [
        { text: "Node", value: "circle" },
        { text: "Link", value: "line" }
      ],

      link: {
        start: {},
        end: {}
      }
    };
  },
  methods: {
    draw(e) {
      if (this.shape === "circle") {
        this.nodes.push({
          x: e.offsetX,
          y: e.offsetY,
          color: colors[Math.round(Math.random() * 56) - 1]
        });
      } else {
   

        //if the target element is a circle (node)
        if (e.target.cy) {
            //save the target node coordinate in order to create links 
            //that join these nodes
          if (!this.link.start.x) {
            this.link.start.x = e.target.cx.baseVal.value;
            this.link.start.y = e.target.cy.baseVal.value;
          } else if (!this.link.end.x) {
            this.link.end.x = e.target.cx.baseVal.value;
            this.link.end.y = e.target.cy.baseVal.value;
          }
//if we have chosen two nodes we create the link 
          if(this.link.start.x && this.link.end.x){
              this.links.push({
                  x1:this.link.start.x,
                  x2:this.link.end.x,
                  y1:this.link.start.y,
                  y2:this.link.end.y,
                   color: colors[Math.round(Math.random() * 56) - 1]
              })

              //reset the link
              this.link= {
        start: {},
        end: {}
      }


          }
        }
      }
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
