<template>
    <v-app id="inspire">
        <v-snackbar v-model="snackbar" right top rounded="pill" color="green" >Logged In Successfully
            <template v-slot:action="{ attrs }">
                <v-btn color="white" text v-bind="attrs" @click="snackbar = false">Close</v-btn>
            </template>
        </v-snackbar>
        <v-main>
            <!-- Top Nav -->
            <v-toolbar flat >
                <v-app-bar-nav-icon class="grey--text" @click="drawer =!drawer"></v-app-bar-nav-icon>
                <v-app-bar flat :clipped-left="$vuetify.breakpoint.lgAndUp">
                    <v-toolbar-title class="text-uppercase grey--text">
                    <span class="font-weight-light">Admin</span>
                    <span>Panel</span>
                    </v-toolbar-title>
                    <v-spacer></v-spacer>
                     <v-btn icon>
                        <v-icon class="grey--text" size="20px">mdi-facebook</v-icon>
                    </v-btn>
                    <v-btn icon>
                        <v-icon class="grey--text" size="20px">mdi-instagram</v-icon>
                    </v-btn>
                    <!-- dropdown menu -->
                    <div class="text-center">
                        <v-menu offset-y>
                            <template v-slot:activator="{ on,attrs }">
                            <v-btn icon v-bind="attrs" v-on="on">
                                <v-avatar size="20px">
                                    <v-img
                                        src="https://cdn.vuetifyjs.com/images/logos/logo.svg"
                                        alt="Vuetify"
                                    ></v-img>
                                </v-avatar>
                            </v-btn>
                            </template>
                            <v-list >
                                <v-list-item v-for="profile in profiles" :key="profile.text" :to="profile.url">
                                    <v-icon class="grey--text" left>{{ profile.icon }}</v-icon>
                                    <v-list-item-title >{{ profile.text }}</v-list-item-title>
                                </v-list-item>
                                <v-list-item @click="logout">
                                    <v-icon class="grey--text" left>mdi-exit-to-app</v-icon>
                                    <v-list-item-title >Logout</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </div>
                    <!--end dropdown -->
                    
                </v-app-bar>
            </v-toolbar>
            <!-- Top Nav End-->

            <!-- Left Side Navigation -->
            <v-navigation-drawer app v-model="drawer" :clipped="$vuetify.breakpoint.lgAndUp">
                <!-- avater -->
                <v-layout column align-center>
                    <v-flex class="mt-5">
                    <v-avatar size="100">
                        <img class="text-lg-center" src="assets/logo.svg" />
                    </v-avatar>
                    <p class="subheading mt-1">Vuetify Admin</p>
                    </v-flex>
                    <!-- Popup Button -->
                </v-layout>
                <v-divider></v-divider>
                <!-- list -->
                <v-list dense>
                    <template v-for="item in items" >
                        <v-list-group v-if="item.children" :key="item.text"
                            v-model="item.model" color="dark" :prepend-icon="item.model ? item.icon : item['icon-alt']" append-icon="" >
                            <template v-slot:activator>
                                <v-list-item-content >
                                    <v-list-item-title class="dark--text">
                                    {{ item.text }}
                                    </v-list-item-title>
                                </v-list-item-content>
                            </template>
                            <v-list-item v-for="(child, i) in item.children" :key="i" :to="child.url" >
                            <v-list-item-action v-if="child.icon">
                                <v-icon class="dark--text">{{ child.icon }}</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title class="dark--text">
                                {{ child.text }}
                                </v-list-item-title>
                            </v-list-item-content>
                            </v-list-item>
                        </v-list-group>
                        <v-list-item v-else :key="item.text" :to="item.url" color="dark">
                            <v-list-item-action>
                            <v-icon class="dark--text">{{ item.icon }}</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                            <v-list-item-title class="dark--text">
                                {{ item.text }}
                            </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </template>
                    <v-list-item @click="logout">
                        <v-list-item-action>
                            <v-icon class="dark--text">mdi-exit-to-app</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title class="dark--text">Logout</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item @click="toString">
                        <v-list-item-action>
                            <v-switch v-model="theme" color="dark"></v-switch>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title class="dark--text">Theme</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
                <v-footer absolute class="my-10px">
                    <v-col class="text-center grey--text" cols="12">
                        <strong >&copy;SazidAhmed{{ new Date().getFullYear() }}</strong>
                    </v-col>
                </v-footer>
            </v-navigation-drawer>
            <!-- Left Side Navigation End -->
        </v-main>
       <!-- body -->
        <v-main>
            <router-view></router-view>
        </v-main>
        <!--End body -->
        <!--Floating btn -->
            <v-btn bottom color="dark" dark fab fixed right>
              <v-icon>mdi-settings</v-icon>
            </v-btn>
    </v-app>
</template>

<script>
    export default {
        data () {
            return {
                drawer: null,
                snackbar: false,
                theme: true,
                text:'',
                
                items: [
                    { icon: "mdi-view-dashboard",  text: "Dashboard", url: '/admin',},
                    { icon: "mdi-chevron-up", 'icon-alt': 'mdi-account-supervisor-circle',
                        text: 'User Management', model: false,
                        children: [
                            { icon: "mdi-chevron-right", text: 'Users', url: '/users',},
                            { icon: "mdi-chevron-right", text: 'Roles', url: '/roles',},
                            { icon: "mdi-chevron-right", text: 'Permissions' , url: '/permissions'},
                        ],
                    },
                    { icon: "mdi-account-supervisor-circle", text: "Blog",url: '/blogs',},
                    { icon: "mdi-settings", text: "Settings", url: '/settings', },
                ],

                profiles: [
                    { 
                        icon: "mdi-account-supervisor-circle", 
                        text: "Profile",
                        url: '/profile',
                    },

                    {
                        icon: "mdi-settings",
                        text: "Settings",
                        url: '/settings',
                    },
                ],
            }
        },
        mounted(){
            this.snackbar = localStorage.getItem('loggedIn') ? true : false;
            localStorage.removeItem('loggedIn');
            this.$vuetify.theme.dark = true;
        },

        watch:{
            theme: function(old, newVal){
                this.$vuetify.theme.dark = old;
            }
        },

        methods:{
            logout(){
                localStorage.removeItem('token');
                this.$router.push('/login')
                    .then(res => {
                        this.text = "Logged Out Successfully";
                        this.snackbar = true;
                    })
                    .catch(err => console.log(err))
            }
        }
    }
</script>
