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

            <!-- Data Table-->
            <v-card>
                <h2>Settings</h2>
            </v-card>
        </v-container>
  </v-app>
</template>

<script>
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
                        text: 'Profile',
                        disabled: false,
                        href: '/#/profile',
                    },
                    {
                        text: 'Settings',
                        disabled: true,
                    },
                ],
            }
        },

        methods:{
            
        }
    }
</script>