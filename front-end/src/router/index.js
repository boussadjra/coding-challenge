import Vue from 'vue'
import Router from 'vue-router'
import Graphs from '../views/Graphs.vue'
import Graph from '../views/Graph.vue'
import EditGraph from '../views/EditGraph.vue'
import GraphStats from '../views/GraphStats.vue'

Vue.use(Router)


export default new Router({
  mode:'history',
    routes: [{
            path: '/',
            name: 'Graphs',
            component: Graphs,
        },
        {
            path: '/graphs/:id',
            name: 'Graph',
            component: Graph,
            props: true
        },
        {
            path: '/add',
            name: 'AddGraph',
            component: EditGraph,
            props: true
        },
        {
            path: '/graphs/:id/edit',
            name: 'EditGraph',
            component: EditGraph,
        },
        {
            path: '/graphs/:id/statistics',
            name: 'GraphStats',
            component: GraphStats,
        },


    ]
})