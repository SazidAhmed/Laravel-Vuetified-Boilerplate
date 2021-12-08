<template>
    <v-app id="inspire">
        <v-container fluid>
            <!-- breadcrumbs -->
            <v-breadcrumbs :items="breadcrumbItems">
                <template v-slot:divider>
                    <v-icon>mdi-chevron-right</v-icon>
                </template>
            </v-breadcrumbs>
            <!-- breadcrumbs End-->
            <!--Table-->
           <template>
               <v-row>
                   <v-col v-for="(info, index) in infos" :key="index">
                        <v-card
                            class="mx-auto" max-width="344" outlined>
                            <v-list-item three-line>
                            <v-list-item-content>
                                <v-list-item-title class="headline mb-1">
                                {{ info.name }}
                                </v-list-item-title>
                                <v-list-item-subtitle>{{ info.age }}</v-list-item-subtitle>
                            </v-list-item-content>
                            </v-list-item>
                        </v-card>
                   </v-col>
               </v-row>
               <v-btn color="deep-purple" small dark class="mb-2" @click="reduceAge(4)"><v-icon dark small> mdi-plus </v-icon>Update</v-btn>
                <h4>Updated Info :</h4>
               <v-row>
                   <v-col v-for="(info, index) in updateInfos" :key="index">
                        <v-card
                            class="mx-auto" max-width="344" outlined>
                            <v-list-item three-line>
                            <v-list-item-content>
                                <v-list-item-title class="headline mb-1">
                                {{ info.name }}
                                </v-list-item-title>
                                <v-list-item-subtitle>{{ info.age }}</v-list-item-subtitle>
                            </v-list-item-content>
                            </v-list-item>
                        </v-card>
                   </v-col>
               </v-row>
               
            </template>       
        </v-container>
  </v-app>
</template>

<script>
import { mapActions } from 'vuex';
import { mapGetters } from 'vuex';

    export default {
        data () {
            return {
                breadcrumbItems: [
                    {
                        text: 'Dashboard',
                        disabled: false,
                        href: '/#/admin',
                    },
                    {
                        text: 'Vuex',
                        disabled: true,
                    },
                ],
                authUserPermissions:[],
            }
        },
        created(){
            // console.log('page route',this.$route)
            this.getAuthPermissions();
        },

        computed:{
            infos(){
                return this.$store.state.infos;
            },

            //getters
            // updateInfos(){
            //     return this.$store.getters.updateInfos;
            // }

            //mapping getters with spread operators
            ...mapGetters([
                'updateInfos'
            ])
        },

        methods:{

            //using mutation
            // reduceAge(){
            //    this.$store.commit('reduceAge');
            // },

            //using actions
            // reduceAge(amount){
            //    this.$store.dispatch('reduceAge', amount);
            // },

            //mapping actions with spread opertors
            ...mapActions([
                'reduceAge'
            ]),
        }
    }
</script>