<template>
    <v-app id="inspire">
         <v-snackbar v-model="snackbar" right top rounded="pill" color="green" >{{text}}
            <template v-slot:action="{ attrs }">
                <v-btn color="white" text v-bind="attrs" @click="snackbar = false">Close</v-btn>
            </template>
        </v-snackbar>
        <v-container fluid>
            <!-- breadcrumbs -->
            <v-breadcrumbs :items="breadcrumbItems">
            <template v-slot:divider>
                <v-icon>mdi-chevron-right</v-icon>
            </template>
            </v-breadcrumbs>
            <!-- breadcrumbs End-->

            <!-- data table -->
            <v-data-table 
                item-key="id" 
                class="elevation-1" 
                color="error"
                :loading = "loading"
                loading-text="Loading... Please wait"
                :headers="headers"
                :options.sync="options"
                :server-items-length="roles.total"
                :items="roles.data"
                show-select
                @input="selectAll"
                :footer-props="{
                itemsPerPageOptions: [5,10,15],
                itemsPerPageText: 'Roles Per Page',
                'show-current-page': true,
                'show-first-last-page': true
                }"
            >
                <!-- modal -->
                <template v-slot:top>
                    <v-toolbar flat >
                        <v-toolbar-title>User Roles</v-toolbar-title>
                        <v-divider class="mx-4" inset vertical></v-divider>
                        <v-card-title>
                            <v-text-field
                                append-icon="mdi-magnify"
                                label="Search"
                                single-line
                                hide-details
                                color="deep-purple"
                                @input="searchIt"
                            />
                        </v-card-title>            
                        <v-divider class="mx-4" inset vertical></v-divider>        
                        
                        <v-spacer></v-spacer>
                        <v-dialog v-model="dialog" max-width="400px" >
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn color="deep-purple" small dark class="mb-2" v-bind="attrs" v-on="on" > Add New Role</v-btn>
                                <v-divider class="mx-4" inset vertical></v-divider>
                                <v-btn color="red" small dark class="mb-2" @click="deleteAll">Delete Selected</v-btn>
                            </template>
                            <v-card>
                                <v-card-title>
                                    <span class="headline">{{ formTitle }}</span>
                                </v-card-title>
                                <!-- form -->
                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" sm="12" md="12" >
                                                <v-text-field v-model="editedItem.name" label="Role Name" color="deep-purple"></v-text-field>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>
                                <!-- End form -->
                                <v-card-actions>
                                <v-spacer></v-spacer>
                                    <v-btn color="red" text @click="close" > Cancel </v-btn>
                                    <v-btn color="green" text @click="save" > Save </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                        <v-dialog v-model="dialogDelete" max-width="500px">
                            <v-card>
                                <v-card-title class="headline">Are you sure you want to delete this item?</v-card-title>
                                <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
                                <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                                <v-spacer></v-spacer>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-toolbar>
                    <!-- search box -->
                </template>
                <!-- end modal -->
                <!-- action btn -->
                <template v-slot:[`item.actions`]="{ item }">
                    <v-icon small class="mr-2" @click="editItem(item)" > mdi-pencil </v-icon>
                    <v-icon small @click="deleteItem(item)" > mdi-delete </v-icon>
                </template>

                <template v-slot:no-data>
                    <v-alert border="top" color="grey lighten-2" dark > No Data TO Show </v-alert>
                </template>
            
            </v-data-table>
            <!-- data table -->
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
                    text: 'Users',
                    disabled: false,
                    href: '/#/users',
                },
                {
                    text: 'Roles',
                    disabled: true,
                },
            ],
            
            loading:false,
            dialog: false,
            dialogDelete: false,
            snackbar:false,
            selected: [],
            text:'',
            options:{
                itemsPerPage: 5,
                sortBy:['id'],
                sortDesc: [false]
            },
            headers: [
                { text: 'Index', align: 'start', sortable: false, value: 'id'},
                { text: 'Name', align: 'start', sortable: true, value: 'name'},
                { text: 'Created', align: 'start', sortable: true, value: 'created_at'},
                { text: 'Updated', align: 'start', sortable: false, value: 'updated_at'},
                { text: 'Actions', value: 'actions', sortable: false },
            ],
            roles: [],
            editedIndex: -1,
            editedItem: {
                id:'',
                name: '',
                created_at:'',
                updated_at:'',

            },
            defaultItem: {
                id:'',
                name: '',
                created_at:'',
                updated_at:'',
            }, 
            
        }
    },
    //Computed Hook 
    computed: {
        formTitle () {
            return this.editedIndex === -1 ? 'New Data' : 'Edit Data'
        },
    },
      
    //Watch Hook
    watch: {
        dialog (val) {
            val || this.close()
        },
        dialogDelete (val) {
            val || this.closeDelete()
        },
        options:{
            handler(e){
            const sortBy = e.sortBy.length>0 ? e.sortBy[0].trim() : 'id';
            const orderBy = e.sortDesc[0] ? 'desc' : 'asc';
            axios.get(`/api/roles`,{params:{'page':e.page, 'per_page':e.itemsPerPage, 'sort_by': sortBy, 'order_by': orderBy}})
            .then(res => {
                this.roles = res.data.roles
            })
            .catch(err => {
                if(err.response.status == 401)
                localStorage.removeItem('token');
                this.$router.push('/login');
            } )
            },
            deep: true
        }
    },

    //Created Hook
    created () {
        this.initialize();
    },

    //Methods Hook
    methods: {
        selectAll(e){
            this.selected = [];
            if(e.length > 0){
                this.selected = e.map(val => val.id)
            }
        },
        deleteAll(){
            let decide = confirm('Are you sure you want to delete these items?')
            if(decide){
                axios.post('/api/roles/delete', {'roles': this.selected})
                .then(res => {
                    this.text = "Records Deleted Successfully!";
                    this.selected.map(val => {
                        const index = this.roles.data.indexOf(val)
                        this.roles.data.splice(index, 1)
                    })
                    this.snackbar = true
                }).catch(err => {
                    console.log(err.response)
                    this.text = "Error Deleting Record"
                    this.snackbar=true
                })
            }        
        },
        searchIt(e){
            if(e.length > 3){
                axios.get(`/api/roles/${e}`)
                    .then(res =>{
                        this.roles = res.data.roles;
                        this.loading = false;
                    })
                    .catch(err => console.dir(err.response))
            }
            if (e.length <= 0) {
                axios
                .get(`/api/roles`)
                .then(res => (this.roles = res.data.roles))
                .catch(err => console.dir(err.response));
            }
        },
        paginate(e){
            const sortBy = e.sortBy.length>0 ? e.sortBy[0].trim() : 'name';
            const orderBy = e.sortDesc[0] ? 'desc' : 'asc';
            axios.get(`/api/roles`,{params:{'page':e.page, 'per_page':e.itemsPerPage, 'sort_by': sortBy, 'order_by': orderBy}})
            .then(res => {
                this.roles = res.data.roles;
            })
            .catch(err => {
                if(err.response.status == 401)
                localStorage.removeItem('token');
                this.$router.push('/login');
            } )
        },
        
        initialize () {
        // Add a request interceptor
        axios.interceptors.request.use((config) => {
            this.loading = true;
            return config;
          },  (error) => {
            this.loading = false;
            return Promise.reject(error);
          });
        // Add a response interceptor
        axios.interceptors.response.use( (response) => {
           this.loading = false;
            return response;
          },  (error) => {
           this.loading = false
            return Promise.reject(error);
          });
      },

        deleteItem (item) {
            this.editedIndex = this.roles.data.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true
        },

        deleteItemConfirm () {
            axios.delete('/api/roles/'+this.editedItem.id)
            .then((res) => {
                this.snackbar = true;
                this.roles.data.splice(this.editedIndex, 1)
                this.loading = false;
                this.closeDelete()
            })
            .catch(err => console.dir(err.response))           
        },

        close () {
            this.dialog = false
            this.$nextTick(() => {
            this.editedItem = Object.assign({}, this.defaultItem)
            this.editedIndex = -1
            })
        },

        closeDelete () {
            this.dialogDelete = false
            this.$nextTick(() => {
            this.editedItem = Object.assign({}, this.defaultItem)
            this.editedIndex = -1
            })
        },

         editItem (item) {
            this.editedIndex = this.roles.data.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
        },

        save () {
            if (this.editedIndex > -1) {
                const index = this.editedIndex
                axios.put('/api/roles/'+this.editedItem.id, {'name': this.editedItem.name})
                .then(res => {
                    this.text = "Record Updated Successfully!"
                    this.snackbar = true;
                    Object.assign(this.roles.data[index], res.data.role)
                    this.loading = false;
                })
                .catch(err => {
                    console.log(err.response)
                    this.text = "Something Is Wrong!"
                    this.snackbar = true;
                })   
            } 
            else 
            {
                axios.post('/api/roles',{'name': this.editedItem.name})
                .then(res=>{
                    this.text = "Record Added Successfully!"
                    this.snackbar = true;
                    this.roles.data.push(res.data.role)
                    this.loading = false;
                })
                .catch(err => {
                    console.log(err.response)
                    this.text = "Something Is Wrong!"
                    this.snackbar = true;
                })
            }
            this.close()
        },
    }
}
</script>