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
               {{authPermissions}}
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
            }
        },
        created(){
            // console.log('page route',this.$route)
            this.fetchAuthPermissions();
        },

        computed:{
            //mapping getters with spread operators
            ...mapGetters([
                'authPermissions'
            ])
        },

        methods:{
            //mapping actions with spread opertors
            ...mapActions([
                'fetchAuthPermissions'
            ]),
        },
    }
</script>