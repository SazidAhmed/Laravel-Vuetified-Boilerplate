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
            <v-card>
                <v-card-title>
                    <v-btn medium @click="createBtn()" color="green" dark > 
                    <v-icon dark medium> mdi-plus </v-icon> New </v-btn>
                    <v-spacer></v-spacer>
                    <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details ></v-text-field>
                </v-card-title>
                <!-- datatable -->
                <v-data-table :headers="headers" :items="roles" :search="search" >
                    <template v-slot:[`item.actions`]="{ item }" >
                        <v-icon small color="cyan" class="mr-2" @click="editBtn(item.id)"> mdi-pencil </v-icon>
                        <v-icon small color="red"  @click="deleteBtn(item.id)"> mdi-delete </v-icon>
                    </template>
                </v-data-table>
                <!-- End datatable -->
            </v-card>
             <!-- Create Modal -->
            <v-row justify="center">
                <v-dialog
                    v-model="createDialog"
                    persistent
                    max-width="600px"
                >
                <form @submit.prevent="createRole()" enctype="multipart/form-data">
                    <v-card>
                        <v-card-title>
                            <span class="headline">New Role</span>
                        </v-card-title>
                        <v-card-text>
                            <v-row>
                                <v-col cols="12" sm="12" md="4" lg="4" >
                                    <v-text-field v-model="name" label="Role Name" color="green"></v-text-field>
                                </v-col>
                            </v-row>
                            <!--Table-->
                            <v-simple-table>
                                <template v-slot:default>
                                    <thead>
                                        <tr>
                                        <th class="text-left">
                                            Section
                                        </th>
                                        <th class="text-left">
                                            Create
                                        </th>
                                        <th class="text-left">
                                            Edit
                                        </th>
                                        <th class="text-left">
                                            Delete
                                        </th>
                                        <th class="text-left">
                                            View
                                        </th>
                                        <th class="text-left">
                                            List
                                        </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(r, i) in resources" :key="i">
                                            <td>{{ r.resourceName }}</td>
                                            <td><v-checkbox color="success"  v-model="r.create" hide-details></v-checkbox></td>
                                            <td><v-checkbox color="success"  v-model="r.edit" hide-details></v-checkbox></td>
                                            <td><v-checkbox color="success"  v-model="r.delete" hide-details></v-checkbox></td>
                                            <td><v-checkbox color="success"  v-model="r.view" hide-details></v-checkbox></td>
                                            <td><v-checkbox color="success"  v-model="r.list" hide-details></v-checkbox></td>
                                        </tr>
                                    </tbody>
                                </template>
                            </v-simple-table>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="red darken-1" text @click="createDialog=false">Cancel</v-btn>
                            <v-btn type="submit" color="green darken-1" text>Submit</v-btn>
                        </v-card-actions>
                    </v-card>
                </form>
                </v-dialog>
            </v-row>

             <!-- Update Modal -->
            <v-row justify="center">
                <v-dialog
                    v-model="updateDialog"
                    persistent
                    max-width="600px"
                >
                <form @submit.prevent="updateRole()" enctype="multipart/form-data">
                    <v-card>
                        <v-card-title>
                            <span class="headline">New Blog</span>
                        </v-card-title>
                        <v-card-text>
                            <v-row>
                                <v-col cols="12" sm="12" md="4" lg="4" >
                                    <v-text-field v-model="actionData.name" label="Role Name" color="green"></v-text-field>
                                </v-col>
                            </v-row>
                            <!--Table-->
                            <v-simple-table>
                                <template v-slot:default>
                                    <thead>
                                        <tr>
                                        <th class="text-left">
                                            Section
                                        </th>
                                        <th class="text-left">
                                            Create
                                        </th>
                                        <th class="text-left">
                                            Edit
                                        </th>
                                        <th class="text-left">
                                            Delete
                                        </th>
                                        <th class="text-left">
                                            View
                                        </th>
                                        <th class="text-left">
                                            List
                                        </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(r, i) in resources" :key="i">
                                            <td>{{ r.resourceName }}</td>
                                            <td><v-checkbox color="success"  v-model="r.create" hide-details></v-checkbox></td>
                                            <td><v-checkbox color="success"  v-model="r.edit" hide-details></v-checkbox></td>
                                            <td><v-checkbox color="success"  v-model="r.delete" hide-details></v-checkbox></td>
                                            <td><v-checkbox color="success"  v-model="r.view" hide-details></v-checkbox></td>
                                            <td><v-checkbox color="success"  v-model="r.list" hide-details></v-checkbox></td>
                                        </tr>
                                    </tbody>
                                </template>
                            </v-simple-table>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="red darken-1" text @click="resetEditModal">Cancel</v-btn>
                            <v-btn type="submit" color="green darken-1" text>Submit</v-btn>
                        </v-card-actions>
                    </v-card>
                </form>
                </v-dialog>
            </v-row>

            <!-- Delete Modal -->
            <v-row justify="center">
                <v-dialog
                    v-model="deleteDialog"
                    persistent
                    max-width="290"
                >
                <form @submit.prevent="deleteData()" enctype="multipart/form-data">
                        <v-card>
                            <v-card-title>
                                <span class="headline">Warning!!!</span>
                            </v-card-title>
                            <v-card-text>
                                <v-container>
                                <v-row>
                                    <h3>Are You Sure???</h3>
                                </v-row>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="red darken-1" text @click="deleteDialog = false">No</v-btn>
                                <v-btn type="submit" color="green darken-1" text>Yes</v-btn>
                            </v-card-actions>
                        </v-card>
                    </form>
                </v-dialog>
            </v-row>
           
        </v-container>
  </v-app>
</template>

<script>
    export default {
        data () {
            return {
                search: '',
                name: '',
                createDialog:false,
                updateDialog:false,
                deleteDialog: false,
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
                        disabled: false,
                        href: '/#/roles',
                    },
                    {
                        text: 'Permissions',
                        disabled: true,
                    },
                ],
                roles: [],
                actionData:[],
                authUserPermissions:[],
                headers: [
                    { text: 'ID', align: 'start', sortable: true, value: 'id'},
                    { text: 'Name', align: 'start', sortable: false, value: 'name'},
                    { text: 'Date', align: 'start', sortable: true, value: 'created_at'},
                    { text: 'Actions', value: 'actions', sortable: false },
                ],
                resources:[
                    { resourceName: 'Users', create: false, view: false, edit: false, delete: false, list: false, routeName: 'Users'},
                    { resourceName: 'Blogs', create: false, view: false, edit: false, delete: false, list: false, routeName: 'Blogs'},
                ],
                defaultValue:[
                    { resourceName: 'Users', create: false, view: false, edit: false, delete: false, list: false, routeName: 'Users'},
                    { resourceName: 'Blogs', create: false, view: false, edit: false, delete: false, list: false, routeName: 'Blogs'},
                ]
            }
        },
        created(){
            // console.log('page route',this.$route)
            this.getRoles();
            this.getAuthPermissions();
        },

        methods:{
            getRoles(){
                axios
                    .get("/api/roles")
                    .then((response) => {
                        this.roles = response.data.roles;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            resetEditModal(){
                this.updateDialog=false;
                this.resources = this.defaultValue;
            },
            //create
            createBtn() {
                this.createDialog = true;
            },
            createRole(){
                 const config = {
                    headers: {
                        "content-type": "multipart/form-data",
                    },
                };
                let data = JSON.stringify(this.resources);
                
                let formData = new FormData();
                    formData.append("name", this.name);
                    formData.append("permissions", data);
                axios
                    .post("api/roles", formData, config)
                    .then(() => {
                        this.getRoles();
                        this.name = '';
                        this.resources = this.defaultValue;
                        this.createDialog=false;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            //edit
            editBtn(id) {
                 axios
                    .get("/api/roles/" + id)
                        .then((response) => {
                            this.actionData = response.data;
                            let newData = JSON.parse(response.data.permissions);
                            if(newData != null){
                                this.resources = newData;
                            }
                            this.updateDialog=true;
                        })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            updateRole(){
                const config = {
                    headers: {
                        "content-type": "multipart/form-data",
                    },
                };
                let data = JSON.stringify(this.resources);

                let formData = new FormData();
                    formData.append("name", this.actionData.name);
                    formData.append("permissions", data);
                    formData.append("_method", "put");
                axios
                    .post("api/roles/" + this.actionData.id, formData, config)
                    .then(() => {
                        this.getRoles();
                        this.name = '';
                        this.resources = this.defaultValue;
                        this.updateDialog=false;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            //delete
            deleteBtn(id) {
                axios
                    .get("/api/roles/" + id)
                        .then((response) => {
                            this.actionData = response.data.id;
                            console.log('delete data:',this.actionData);
                            this.deleteDialog = true;
                        })
                    .catch((error) => {
                        alert("unable to get action data");
                    });
            },
            deleteData(){
                axios
                    .delete("api/roles/" + this.actionData)
                    .then((response) => {
                        this.getRoles();
                        this.deleteDialog = false;
                    })
                    .catch((error) => {
                        console.log(error);
                    })
            },
            //auth User permissions
            getAuthPermissions(){
                let token = localStorage.getItem('token');
                let formData = new FormData();
                    formData.append("token", token);
                axios
                    .post("api/userPermissions/", formData)
                    .then((res) => {
                       this.authUserPermissions = res.data.permissions;
                    //    console.log('authUserPermissions:',this.authUserPermissions);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
                console.log(token);
            }
        }
    }
</script>