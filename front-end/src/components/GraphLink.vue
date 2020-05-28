<template>
  <line
    :x1="link.x1"
    :y1="link.y1"
    :x2="link.x2"
    :y2="link.y2"
    :stroke="link.color"
    style="stroke-width:2"
  ></line>

</template>

<script>
export default {
  name: "graph-link",
  props:{
      link:Object
  },

};
</script>

<style>
</style>
