<template>
    <div class="cc-list-graphs">
           <b-table bordered hover :items="graphs" :fields="fields">
             <template v-slot:cell(actions)="row">
       <b-button-group>
        <b-button variant="info" size="sm" @click="$router.push('/graphs/'+row.item.id)" >
        show
        </b-button>
         <b-button size="sm" variant="success" >
          edit
        </b-button>
         <b-button size="sm" variant="danger" >
          delete
        </b-button>
        </b-button-group>
      </template>
           </b-table>
    </div>
</template>

<script>
import GraphService from "../services/GraphService.js";

export default {
 name:'graphs',
data:()=>({
    graphs:[],
    fields: [
       
          {
            key: 'name',
          },
            {
            key: 'description',
          },
          {
              key:"actions"
          }
    ]
}),
methods: {
    show(row){
        console.log('--------------------')
        console.log(row)
        console.log('--------------------')
    }
},
created(){

GraphService.all().then((result) => {
    this.graphs=result.data
}).catch((err) => {
    
});
}

}
</script>

<style>
.cc-list-graphs{

    padding: 10px;
}
</style>
