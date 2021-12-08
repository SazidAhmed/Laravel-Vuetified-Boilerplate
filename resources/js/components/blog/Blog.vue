<template>
    <v-app id="inspire">
        <v-snackbar v-model="snackbar" right top rounded="pill" :color="color" >{{text}}
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

            <!-- Data Table-->
            <v-card>
                <v-toolbar flat >
                    <v-toolbar-title>Blogs List</v-toolbar-title>
                    <v-divider class="mx-4" inset vertical></v-divider>
                    <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" color="deep-purple" single-line hide-details ></v-text-field>
                    <v-divider class="mx-4" inset vertical></v-divider>
                    <v-spacer></v-spacer>
                    <v-divider class="mx-4" inset vertical></v-divider>
                    <v-btn color="cyan" small dark class="mb-2">Print</v-btn>
                    <v-divider class="mx-4" inset vertical></v-divider>
                    <v-btn color="red" small dark class="mb-2">Delete Selected</v-btn>
                    <v-divider class="mx-4" inset vertical></v-divider>
                    <v-btn color="deep-purple" small dark class="mb-2" @click="createBtn"><v-icon dark small> mdi-plus </v-icon>Add New</v-btn>
                </v-toolbar>
                <!-- datatable -->                        
                <v-data-table class="elevation-1" :headers="headers" :items="blogs" :search="search" :loading="loading"
                    loading-text="Loading... Please wait" :footer-props="{itemsPerPageOptions: [5,10,15],itemsPerPageText: 'Data Per Page','show-current-page': true,'show-first-last-page': true}">
                    <template v-slot:[`item.image`] ="{ item }">
                        <img :src="item.image" style="width: 60px; margin-top: 5px;" />
                    </template>
                    <template v-slot:[`item.actions`]="{ item }" >
                        <v-icon small color="cyan" class="mr-2" @click="editBtn(item.id)"> mdi-pencil </v-icon>
                        <v-icon small color="red"  @click="deleteBtn(item.id)"> mdi-delete </v-icon>
                    </template>
                </v-data-table>
                <!-- End datatable -->
            </v-card>

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

            <!-- Update Modal -->
            <v-row justify="center">
                <v-dialog
                    v-model="updateDialog"
                    persistent
                    max-width="600px"
                >
                 <form @submit.prevent="updateBlogs()" enctype="multipart/form-data">
                    <v-card>
                        <v-card-title>
                            <span class="headline">Update Blog</span>
                        </v-card-title>
                        <v-card-text>
                            <v-container>
                            <v-row>
                                <v-col cols="12" sm="12" md="12">
                                    <v-select
                                        v-model="actionData.category"
                                        :items="categories"
                                        item-text="name"
                                        item-value="id"
                                        label="Select Here"
                                        class="rounded-0"
                                        outlined
                                    ></v-select>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" >
                                    <v-text-field label="Title" v-model="actionData.title" hint="Enter Blog Title Here"></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" >
                                    <v-text-field label="Body" v-model="actionData.body"></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" >
                                    <v-radio-group v-model="actionData.active">
                                        <template v-slot:label>
                                            <div>Active ?</div>
                                        </template>
                                        <v-radio value="Yes">
                                            <template v-slot:label>
                                            <div>Yes</div>
                                            </template>
                                        </v-radio>
                                        <v-radio value="No">
                                            <template v-slot:label>
                                            <div>No</div>
                                            </template>
                                        </v-radio>
                                    </v-radio-group>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" >
                                    <v-menu
                                        v-model="editDate"
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="auto"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field
                                            v-model="actionData.date"
                                            label="Select Date"
                                            prepend-icon="mdi-calendar"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker
                                           v-model="actionData.date"
                                            @input="editDate = false"
                                        ></v-date-picker>
                                    </v-menu>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" >
                                    <v-menu
                                    ref="menu"
                                    v-model="editTime"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    :return-value.sync="time"
                                    transition="scale-transition"
                                    offset-y
                                    max-width="290px"
                                    min-width="290px"
                                    >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                        v-model="actionData.time"
                                        label="Select Time"
                                        prepend-icon="mdi-clock"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-time-picker
                                        v-if="editTime"
                                        v-model="actionData.time"
                                        full-width
                                        @click:minute="$refs.menu.save(time)"
                                    ></v-time-picker>
                                    </v-menu>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" >
                                    <v-file-input
                                        label="Upload File"
                                        outlined
                                        dense
                                        prepend-icon="mdi-camera"
                                        accept="image/*"
                                        v-model="image"
                                    ></v-file-input>
                                </v-col>
                            </v-row>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="red darken-1" text @click="updateDialog = false">Cancel</v-btn>
                            <v-btn type="submit" color="green darken-1" text> Submit</v-btn>
                        </v-card-actions>
                    </v-card>
                 </form>
                </v-dialog>
            </v-row>

            <!-- Create Modal -->
            <v-row justify="center">
                <v-dialog
                    v-model="createDialog"
                    persistent
                    max-width="600px"
                >
                 <v-form @submit.prevent="createBlogs()" enctype="multipart/form-data" v-model="valid" lazy-validation>
                    <v-card>
                        <v-card-title>
                            <span class="headline">New Blog</span>
                        </v-card-title>
                        <v-card-text>
                            <v-container>
                            <v-row>
                                <v-col cols="12" sm="12" md="12">
                                    <v-select
                                        :items="categories"
                                        item-text="name"
                                        item-value="id"
                                        label="Select Here"
                                        class="rounded-0"
                                        v-model="category"
                                        outlined
                                    ></v-select>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" >
                                    <v-text-field label="Title" v-model="title" :counter="6" :rules="titleRules" outlined hint="Enter Blog Title Here" required></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" >
                                    <v-text-field label="Body" v-model="body" outlined></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" >
                                    <v-radio-group v-model="active">
                                        <template v-slot:label>
                                            <div>Active ?</div>
                                        </template>
                                        <v-radio value="Yes">
                                            <template v-slot:label>
                                            <div>Yes</div>
                                            </template>
                                        </v-radio>
                                        <v-radio value="No">
                                            <template v-slot:label>
                                            <div>No</div>
                                            </template>
                                        </v-radio>
                                    </v-radio-group>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" >
                                    <v-menu
                                        v-model="menu"
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="auto"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field
                                            v-model="date"
                                            label="Select Date"
                                            prepend-icon="mdi-calendar"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker
                                            v-model="date"
                                            @input="menu = false"
                                        ></v-date-picker>
                                    </v-menu>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" >
                                    <v-menu
                                    ref="menu"
                                    v-model="menu2"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    :return-value.sync="time"
                                    transition="scale-transition"
                                    offset-y
                                    max-width="290px"
                                    min-width="290px"
                                    >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                        v-model="time"
                                        label="Select Time"
                                        prepend-icon="mdi-clock"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-time-picker
                                        v-if="menu2"
                                        v-model="time"
                                        full-width
                                        @click:minute="$refs.menu.save(time)"
                                    ></v-time-picker>
                                    </v-menu>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" >
                                    <v-file-input
                                        label="Upload File"
                                        outlined
                                        dense
                                        prepend-icon="mdi-camera"
                                        accept="image/*"
                                        v-model="image"
                                    ></v-file-input>
                                </v-col>
                            </v-row>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="red darken-1" text @click="createDialog = false">Cancel</v-btn>
                            <v-btn type="submit" color="green darken-1" text :disabled="!valid"> Submit</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-form>
                </v-dialog>
            </v-row>
        </v-container>
    </v-app>
</template>

<script>
import axios from 'axios'
    export default {
        data () {
            return {
                category:'',
                title:'',
                body:'',
                active:'Yes',
                date:'',
                time:'',
                image:null,
                menu: false,
                menu2: false,
                editDate:false,
                editTime:false,
                search: '',
                color:'',
                loading: false,
                snackbar: false,
                text: '',
                createDialog:false,
                updateDialog:false,
                deleteDialog: false,
                headers: [
                    { text: 'ID', align: 'start', sortable: true, value: 'id'},
                    { text: 'Image', align: 'start', sortable: false, value: 'image'},
                    { text: 'Category', align: 'start', sortable: false, value: 'category'},
                    { text: 'Title', align: 'start', sortable: false, value: 'title'},
                    { text: 'Body', align: 'start', sortable: true, value: 'body'},
                    { text: 'Active', align: 'start', sortable: true, value: 'active'},
                    { text: 'Date', align: 'start', sortable: true, value: 'date'},
                    { text: 'Time', align: 'start', sortable: true, value: 'time'},
                    { text: 'Actions', value: 'actions', sortable: false },
                ],
                categories: [
                    {
                        id: 1,
                        name: 'React',
                    },
                    {
                        id: 2,
                        name: 'Vue',
                    },
                    {
                        id: 3,
                        name: 'Angular',
                    },
                ],
                blogs: [],
                actionData:[],
                breadcrumbItems: [
                    {
                        text: 'Dashboard',
                        disabled: false,
                        href: '/#/admin',
                    },
                    {
                        text: 'blog',
                        disabled: true,
                    },
                ],
                valid: true,
                titleRules: [
                    v => !!v || 'Title is required',
                    v => (v && v.length <= 10) || 'Title must be less than 6 characters',
                ],
            }
        },

        created() {         
            this.getBlogs();
            this.reset();
        },

        methods:{
            reset(){
                this.category='';
                this.title='';
                this.body='';
                this.active='';
                this.date='';
                this.time='';
            },
            getBlogs(){
                this.loading = true;
                axios
                    .get("/api/list")
                    .then((response) => {
                        this.blogs = response.data.blogs;
                        this.loading = false;
                    })
                    .catch((error) => {
                        console.log(error);
                        this.loading = false;
                    });
            },

            createBtn() {
                this.createDialog = true;
            },
            createBlogs() {
                this.loading = true;
                console.log(this.image, this.image.name);
                const config = {
                    headers: {
                        "content-type": "multipart/form-data",
                    },
                };
                let formData = new FormData();
                    formData.append("category", this.category);
                    formData.append("title", this.title);
                    formData.append("body", this.body);
                    formData.append("active", this.active);
                    formData.append("date", this.date);
                    formData.append("time", this.time);
                    formData.append("image", this.image, this.image.name);
                axios
                    .post("api/create", formData, config)
                    .then(() => {
                        this.color = 'green';
                        this.snackbar = true;
                        this.text = "Record Added Successfully!";
                        this.getBlogs();
                        this.title='';
                        this.body='';
                        this.createDialog=false;
                        this.loading = false;
                        
                    })
                    .catch((error) => {
                        console.log(error);
                        this.color = 'red';
                        this.snackbar = true;
                        this.text = "Something is Wrong! Try Again!";
                        this.createDialog=false;
                        this.loading = false;
                    });
            },
           
            editBtn(id) {
                 axios
                    .get("/api/getById/" + id)
                        .then((response) => {
                            console.log(response.data);
                            this.actionData = response.data;
                            this.updateDialog=true;
                            console.log('edit data:',this.actionData);
                        })
                    .catch((error) => {
                        alert("unable to get action data");
                    });
            },
            updateBlogs(){
                this.loading = true;
                const config = {
                    headers: {
                        "content-type": "multipart/form-data",
                    },
                };
                let formData = new FormData();
                    formData.append("category", this.actionData.category);
                    formData.append("title", this.actionData.title);
                    formData.append("body", this.actionData.body);
                    formData.append("active", this.actionData.active);
                    formData.append("date", this.actionData.date);
                    formData.append("time", this.actionData.time);
                     formData.append("image", this.image, this.image.name);
                    formData.append("_method", "put");
                axios
                    .post("api/update/" + this.actionData.id, formData, config)
                    .then(() => {
                        this.color = 'green';
                        this.snackbar = true;
                        this.text = "Record Updated Successfully!";
                        this.title='';
                        this.body='';
                        this.updateDialog=false;
                        this.getBlogs();
                        this.loading = false;
                    })
                    .catch(() => {
                        this.color = 'red';
                        this.snackbar = true;
                        this.text = "Something is Wrong! Try Again!";
                        this.updateDialog=false;
                        this.loading = false;
                    });
            },

            deleteBtn(id) {
                axios
                    .get("/api/getById/" + id)
                        .then((response) => {
                            this.actionData = response.data;
                            console.log('delete data:',this.actionData);
                            this.deleteDialog = true;
                        })
                    .catch((error) => {
                        alert("unable to get action data");
                    });
            },
            deleteData(){
                this.loading = true;
                axios
                    .delete("api/delete/" + this.actionData.id)
                    .then(() => {
                        this.color = 'green';
                        this.snackbar = true;
                        this.text = "Record Deleted Successfully!";
                        this.getBlogs();
                        this.deleteDialog = false;
                        this.loading = false;
                    })
                    .catch(() => {
                        this.color = 'red';
                        this.snackbar = true;
                        this.text = "Something is Wrong! Try Again!";
                        this.deleteDialog = false;
                        this.loading = false;
                    })
            }
        }
    }
</script>