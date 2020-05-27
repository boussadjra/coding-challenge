import { API_HOST } from '../utils/constants';

const GraphService ={
	all,findById,save,update
};

 function all() {
	return axios({ method: 'get', url: `${API_HOST}/graphs` });
}

 function findById(id){
	return axios({ method: 'get', url: `${API_HOST}/graphs/${id}` });

}

function save(graph){
 
	return axios({ method: 'post', url: `${API_HOST}/graphs`,data:graph });

}

function update(graph){

	return axios({ method: 'put', url: `${API_HOST}/graphs/${graph.id}`,data:graph });

}

export default GraphService;
