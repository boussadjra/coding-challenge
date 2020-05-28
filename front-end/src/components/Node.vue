<template>
      <circle
            :cx="node.x"
            :cy="node.y"
            r="16"
            stroke-width="1"
            :fill="node.color"
          ></circle>
</template>

<script>
export default {
name:"node",
props:["node"]
}
</script>

<style>

</style>
