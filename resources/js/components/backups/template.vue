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
                <v-card-title>
                    <v-btn medium @click="createItem()" color="green" dark > 
                    <v-icon dark medium> mdi-plus </v-icon> New </v-btn>
                    <v-spacer></v-spacer>
                    <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details ></v-text-field>
                </v-card-title>
                <!-- datatable -->
                <v-data-table :headers="headers" :items="desserts" :search="search" >
                    <template v-slot:[`item.image`] ="{ item }">
                        <img :src="'assets/img/' + item.image" style="width: 60px; margin-top: 5px;" />
                        </template>
                        <template v-slot:[`item.actions`]="{ item }" >
                        <v-icon small color="cyan" class="mr-2" @click="editItem(item)"> mdi-pencil </v-icon>
                        <v-icon small color="red"  @click="deleteItem(item)"> mdi-delete </v-icon>
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
                <v-card>
                <v-card-title class="headline">Confirm To Delete.</v-card-title>
                <v-card-text class=" text-h6 text-center"></v-card-text>
                <v-card-actions >
                    <v-spacer></v-spacer>
                    <v-btn 
                    color="red darken-1"
                    text
                    @click="deleteDialog = false"
                    >
                    Cancel
                    </v-btn>
                    <v-btn 
                    color="green darken-1"
                    text
                    @click="deleteDialog = false"
                    >
                    Confirm
                    </v-btn>
                </v-card-actions>
                </v-card>
            </v-dialog>
            </v-row>

            <!-- Create Modal -->
            <v-row justify="center">
            <v-dialog
                v-model="createDialog"
                persistent
                max-width="600px"
            >
                <v-card>
                <v-card-title>
                    <span class="headline">New Category</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                    <v-row>
                        <v-col
                        cols="12"
                        sm="12"
                        md="7"
                        >
                        <v-text-field
                            label="Category Name"
                            hint="Enter Name Of Category Here"
                        ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="12" md="5" >
                        <v-file-input  accept="image/*" label="Upload Image" ></v-file-input>
                    </v-col>
                    </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                    color="red darken-1"
                    text
                    @click="createDialog = false"
                    >
                    Cancel
                    </v-btn>
                    <v-btn
                    color="green darken-1"
                    text
                    @click="createDialog = false"
                    >
                    Submit
                    </v-btn>
                </v-card-actions>
                </v-card>
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
                createDialog:false,
                deleteDialog: false,
                headers: [
                    { text: 'Index', align: 'start', sortable: true, value: 'id'},
                    { text: 'Image', align: 'start', sortable: false, value: 'image'},
                    { text: 'Name', align: 'start', sortable: true, value: 'name'},
                    { text: 'Date', align: 'start', sortable: true, value: 'created_at'},
                    { text: 'Actions', value: 'actions', sortable: false },
                ],
                desserts: [
                    {
                        id: 1,
                        image: 'default.jpg',
                        name: 'Capsules',
                        created_at: '15-01-2021',
                    },
                    {
                        id: 2,
                        image: 'default.jpg',
                        name: 'Injections',
                        created_at: '15-01-2021',
                    },
                    {
                        id: 3,
                        image: 'default.jpg',
                        name: 'Tablets',
                        created_at: '15-01-2021',
                    },
                    {
                        id: 4,
                        image: 'default.jpg',
                        name: 'Inhalers',
                        created_at: '15-01-2021',
                    },
                
                ],
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
            }
        },

        methods:{
            createItem() {
                this.createDialog = true;
            },
            editItem (item) {
                this.createDialog = true;
                console.log(item.id);
                console.log(item.name);
            },

            deleteItem (item) {
                this.deleteDialog = true;
                console.log(item.id);
                console.log(item.name);
            },
        }
    }
</script>