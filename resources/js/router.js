import Vue from 'vue';
import VueRouter from 'vue-router';

//Auth components
import LoginComponent from './components/auth/LoginComponent';
import SignupComponent from './components/auth/SignupComponent';
//Adminpanel components
import AdminComponent from './components/adminpanel/AdminComponent';
import DashboardComponent from './components/adminpanel/DashboardComponent';
import SettingsComponent from './components/adminpanel/SettingsComponent';
//Users components
import UsersComponent from './components/users/UsersComponent';
import RolesComponent from './components/users/RolesComponent';
import PermissionsComponent from './components/users/PermissionsComponent';
import ProfileComponent from './components/users/ProfileComponent';
//Blog Components
import BlogComponent from './components/blog/BlogComponent';
import VuexComponent from './components/blog/VuexComponent';

import axios from 'axios';

Vue.use(VueRouter)

const routes =[
    {
        path: '/',
        redirect: '/login'
    },
    {
        path: '/login',
        component: LoginComponent,
        name:'Login'
    },
    {
        path: '/signup',
        component: SignupComponent,
        name:'Signup'
    },
    {
        path: '/admin',
        component: AdminComponent,
        
        children:[
            {
                path: '',
                component: DashboardComponent,
                name:'Dashboard',
            },
            {
                path: '/users',
                component: UsersComponent,
                name:'Users',
            },
            {
                path: '/roles',
                component: RolesComponent,
                name:'Roles',
            },
            {
                path: '/permissions',
                component: PermissionsComponent,
                name:'Permissions',
            },
            {
                path: '/profile',
                component: ProfileComponent,
                name:'Profile',
            },
            {
                path: '/settings',
                component: SettingsComponent,
                name:'Settings',
            },
            {
                path: '/vuex',
                component: VuexComponent,
                name:'Vuex',
            },
            {
                path: '/blogs',
                component: BlogComponent,
                name:'Blogs',
            },
        ],

        beforeEnter:(to, from, next) => {
            axios.get('api/userVarify')
            .then(res => next())
            .catch(err => next('/login'))
        }

       
    }
]
// export default new VueRouter({routes});

const router = new VueRouter({routes})

router.beforeEach(( to, from, next )=>{
    
    if(to.name == 'Login'){
        next();
    }
    else{
        const token = localStorage.getItem('token') || null
        window.axios.defaults.headers['Authorization'] = "Bearer " + token;
        next();
    }
})
export default router;
