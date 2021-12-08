import { constant } from 'lodash';
import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export const store = new Vuex.Store({
    state:{
        infos:[
            { "name": "jhon", "age": "23"}, 
            { "name": "jenny", "age": "20"},
        ],
    },

    //Getters 
    getters:{
        updateInfos: state => {
            var updateInfos = state.infos.map(info => {
                return{
                    name:'**'+ info.name + '**',
                    age: info.age / 2
                }
            });
            return updateInfos;
        }
    },
    
    //mutation
    // mutations:{
    //     reduceAge: state=> {
    //         state.infos.forEach( info => {
    //             info.age -= 1;
    //         })
    //     },
    // },

    //mutation with payload
    mutations:{
        reduceAge: (state, payload) => {
            state.infos.forEach( info => {
                info.age -= payload;
            })
        },
    },

    //mutation with actions
    actions:{
        reduceAge: (context, payload)=>{
            setTimeout(()=>{
                context.commit('reduceAge', payload)
            },2000)
        }
    },
})