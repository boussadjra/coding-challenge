import Vue from 'vue'
import Router from 'vue-router'
import Graphs from '../views/Graphs.vue'
import Graph from '../views/Graph.vue'

Vue.use(Router)


export default new Router({
  
    routes: [{
            path: '/',
            name: 'Graphs',
            component: Graphs,
        },
        {
            path: '/graphs/:id',
            name: 'Graph',
            component: Graph,
        },



    ]
})