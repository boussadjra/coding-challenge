import { API_HOST } from '../utils/constants';

const NodeService ={
	all,findById,save,update,remove,
	saveNeighbors
};

 function all() {
	return axios({ method: 'get', url: `${API_HOST}/nodes` });
}

 function findById(id){
	return axios({ method: 'get', url: `${API_HOST}/nodes/${id}` });

}
function findByGraphId(id){
	return axios({ method: 'get', url: `${API_HOST}/nodes/graph/${id}` });

}
 function  save(node){

	return  axios({ method: 'post', url: `${API_HOST}/nodes`,data:node });

}
function saveNeighbors(node, neighbors) {

	return  axios({ method: 'post', url: `${API_HOST}/neigbors`,data:{
		node,neighbors
	} });
	
}

function update(node){

	return axios({ method: 'put', url: `${API_HOST}/nodes/${node.id}`,data:node });

}

function remove(node){

	return axios({ method: 'delete', url: `${API_HOST}/nodes/${node.id}`,data:node });

}
export default NodeService;
