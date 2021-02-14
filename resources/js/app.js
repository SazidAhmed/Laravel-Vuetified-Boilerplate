require('./bootstrap');

window.Vue = require('vue');
import vuetify from './vuetify';
import router from './router';

//Components
import Index from './components/IndexComponent.vue';


//vue instance
new Vue({
    el: '#app',
    router,
    vuetify,
    components:{
        'index': Index,
    }
});
