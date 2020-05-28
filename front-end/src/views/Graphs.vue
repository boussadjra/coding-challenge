<template>
  <div class="cc-list-graphs">

      
      <b-button variant="primary" class="mb-2" @click="refresh">
      Refresh
        <b-icon icon="arrow-counterclockwise" aria-hidden="true"></b-icon>
      </b-button>
    <b-table bordered hover :items="graphs" :fields="fields">
      <template v-slot:cell(actions)="row">
        <b-button-group>
          <b-button variant="info" size="sm" @click="$router.push('/graphs/'+row.item.id)">show</b-button>
          <b-button
            size="sm"
            variant="success"
            @click="$router.push('/graphs/'+row.item.id+'/edit')"
          >edit</b-button>
          <b-button size="sm" variant="danger" @click="remove(row.item)">delete</b-button>
        </b-button-group>
      </template>
    </b-table>
  </div>
</template>

<script>
import GraphService from "../services/GraphService.js";

export default {
  name: "graphs",
  data: () => ({
    graphs: [],
    fields: [
      {
        key: "name"
      },
      {
        key: "description"
      },
      {
        key: "actions"
      }
    ]
  }),
  methods: {
    remove(graph) {
      GraphService.remove(graph);
    },
    refresh(){
          GraphService.all()
      .then(result => {
        this.graphs = result.data;
      })
      .catch(err => {});
    }
  },
  created() {
this.refresh()
  }
};
</script>

<style>
.cc-list-graphs {
  padding: 10px;
}
</style>
