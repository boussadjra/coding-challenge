<template>
<div>

    <b-navbar toggleable="lg" type="dark" variant="dark">
    <b-navbar-brand href="#">Graph</b-navbar-brand>

    <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

    <b-collapse id="nav-collapse" is-nav>
      <b-navbar-nav>
        <b-nav-item >
        <b-link to="/">List of graphs
        </b-link>
        </b-nav-item>
     
      </b-navbar-nav>

      <b-navbar-nav class="ml-auto">
   
      

        <b-nav-item right>
     <b-button pill size="sm" class="my-2 my-sm-0 " variant="success" @click="$router.push({ path: '/add' })">Add graph</b-button>
     
        </b-nav-item>
      </b-navbar-nav>
    </b-collapse>
  </b-navbar>
<b-container fluid>

    <router-view></router-view>

 </b-container>
 </div>

</template>

<script>


export default {
  name: "app",
  mounted() {
   
     
  }
};
</script>
<style>
.example {
  color: brown;
}
</style>
