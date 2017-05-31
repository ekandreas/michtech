require('./bootstrap');

Vue.component('admin-folder', require('./components/admin-folder.vue'));
Vue.component('admin-texts', require('./components/admin-texts.vue'));

const app = new Vue({
    el: '#admin',
    data() {
        return {
        }
    },
    mounted() {
        let self = this;
    },
    methods: {
    }
});

