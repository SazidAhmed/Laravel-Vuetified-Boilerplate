require('./bootstrap');

window.Vue = require('vue');
import vuetify from './vuetify';
import router from './router';
import { store } from './store/store';

//Components
import Index from './components/IndexComponent.vue';


//vue instance
new Vue({
    store:store,
    el: '#app',
    router,
    vuetify,
    components:{
        'index': Index,
    }
});
