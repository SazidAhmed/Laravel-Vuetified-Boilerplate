<template>
    <v-app id="inspire">
        <v-snackbar v-model="snackbar" right top rounded="pill" color="red" >{{ text }}
            <template v-slot:action="{ attrs }">
                <v-btn color="white" text v-bind="attrs" @click="snackbar = false">Close</v-btn>
            </template>
        </v-snackbar>
            <v-main>
                <v-container
                class="fill-height"
                fluid
                >
                    <v-row
                        align="center"
                        justify="center"
                    >
                        <v-col
                        cols="12"
                        sm="8"
                        md="4"
                        >
                        <v-card class="elevation-12">
                            <v-toolbar
                            color="deep-purple"
                            dark
                            flat
                            >
                            <v-toolbar-title>Login form</v-toolbar-title>
                            <v-spacer></v-spacer>
                            </v-toolbar>
                            <v-card-text>
                                <v-progress-linear
                                    :active="loading"
                                    :indeterminate="loading"
                                    absolute
                                    top
                                    color="deep-purple accent-4"
                                ></v-progress-linear>
                                <v-form
                                    ref="form"
                                    v-model="valid"
                                >
                                    <v-text-field
                                    label="Email"
                                    v-model="email"
                                    :rules="emailRules"
                                    name="email"
                                    prepend-icon="mdi-account"
                                    type="text"
                                    color="deep-purple"
                                    required
                                    ></v-text-field>
                
                                    <v-text-field
                                    id="password"
                                    label="Password"
                                    :rules="passwordRules"
                                    v-model="password"
                                    name="password"
                                    prepend-icon="mdi-lock"
                                    type="password"
                                    color="deep-purple"
                                    required
                                    ></v-text-field>
                                </v-form>
                            </v-card-text>
                            <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="deep-purple white--text" @click="login" :disabled="!valid" block>Login</v-btn>
                            </v-card-actions>
                        </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </v-main>
    </v-app>
</template>

<script>
    export default {
        data(){
            return{
                email:'',
                password:'',
                text:'',
                valid: true,
                loading: false,
                snackbar:false,
                emailRules: [
                    v => !!v || 'E-mail is required',
                    v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
                ],
                passwordRules: [
                    v => !!v || 'Password is required',
                ],
            }
        },
        methods:{
            login:function(){
                axios.interceptors.request.use((config)=>{
                    this.loading= true;
                    return config;
                }, (error)=>{
                    this.loading = false;
                    return Promise.reject(error);
                });

                axios.interceptors.request.use((response)=>{
                    this.loading = false;
                    return response;
                }, (error)=>{
                    this.loading = false;
                    return Promise.reject(error);
                });

                axios.post('/api/login',{'email':this.email, 'password':this.password})
                .then(res =>{
                    console.log(res.data);
                    localStorage.setItem('token', res.data.token)
                    // localStorage.setItem('permissions', res.data.permissions)
                    localStorage.setItem('loggedIn', true)
                    this.$router.push('/admin')
                })
                .catch(err => {
                    this.text = err.response.data.status;
                    this.snackbar = true;
                })
                
            }
        }
    }
</script>
