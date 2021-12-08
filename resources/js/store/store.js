import { constant } from 'lodash';
import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export const store = new Vuex.Store({

    //Vuex States
    state:{
        permissions:[],
    },

    //Getters 
    getters:{
        authPermissions: state => state.permissions
    },
    
    //mutation
    mutations:{
        setPermissions: (state, fetchedData) => (state.permissions = fetchedData)

    },

    //mutation with actions
    actions:{
       async fetchAuthPermissions({ commit }){
            let token = localStorage.getItem('token');
            let formData = new FormData();
                formData.append("token", token);
                const response = await axios.post("api/userPermissions/", formData);
                commit('setPermissions', response.data.permissions);
       }
    }
    
})