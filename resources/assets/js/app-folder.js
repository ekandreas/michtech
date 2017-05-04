require('./bootstrap');

Vue.component('dropzone', require('vue2-dropzone'));
Vue.component('folder', require('./components/Folder.vue'));

const app = new Vue({
    el: '#app-folder',
    data() {
        return {
            folders: []
        }
    },
    mounted() {
        let self = this;
        self.load();
    },
    methods: {

        load() {
            let self = this;
            axios.get('api/folder').then(response => self.folders = response.data);
        }
    }
});

