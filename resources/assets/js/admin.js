require('./bootstrap');

Vue.component('admin-folder', require('./components/admin-folder.vue'));

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

