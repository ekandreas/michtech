<template>
    <div class="panel folder" v-if="data.name">
        <p class="panel-heading">
            {{ data.name }}
        </p>
        <a class="panel-block" v-for="file in data.items" @click="download(file)">
            <span class="panel-icon">
              <i :class="mimeIcon(file)"></i>
            </span>
            {{ file.name }}
        </a>
        <div class="panel-block">
            <dropzone
                    :language="uploadLanguage"
                    :useCustomDropzoneOptions="true"
                    :dropzoneOptions="uploadOptions"
                    :autoProcessQueue="false"
                    ref="folderUpload"
                    :useFontAwesome="dropzone.useFontAwesome"
                    :id="dropZoneId"
                    :url="uploadUrl"></dropzone>
        </div>
    </div>
</template>

<script>

    export default {
        props: ['id'],
        data() {
            let self = this;
            return {
                data: {},
                uploadUrl: '/api/folder/' + self.id + '/upload',
                dropzone: {
                    useFontAwesome: true
                },
                dropZoneId: 'dropZone' + self.id,
                uploadOptions: {
                    autoProcessQueue: false,
                    dictDefaultMessage: '<p><i class="fa fa-cloud-upload"></i><br/>Klicka eller släpp dokument här!</p>',
                    addedfile(file) {
                        let passcode = prompt('Ange kod för att ladda upp!');
                        axios.post('api/folder/' + self.id + '/passcode', {passcode}).then(function (response) {
                            if (response.data == 'success') {
                                self.$refs.folderUpload.processQueue();
                                setTimeout(function () {
                                    self.load();
                                }, 500);
                            }
                            else {
                                alert('Felaktig kod angiven!')
                            }
                        });
                    }
                }
            }
        },
        mounted()
        {
            let self = this;
            self.load();
        },
        methods: {
            load() {
                let self = this;
                axios.get('api/folder/' + self.id).then(response => self.data = response.data);
            },
            mimeIcon(file) {
                let mime = file.mime.toString();
                if (mime.match('^application/pdf')) return 'fa fa-file-pdf-o';
                if (mime.match('^image')) return 'fa fa-file-image-o';
                if (mime.match('zip')) return 'fa fa-file-zip-o';
                if (mime.match('^application\/')) return 'fa fa-file-o';
                return 'fa fa-file-o';
            },
            download(file) {
                let self = this;
                let passcode = prompt('Ange kod för att ladda upp!');
                axios.post('api/folder/' + self.id + '/passcode', {passcode}).then(function (response) {
                    if (response.data == 'success') {
                        document.location = 'folder/' + self.id + '/item/' + file.id;
                    }
                    else {
                        alert('Felaktig kod angiven!')
                    }
                });
            }
        }
    }
</script>

<style>
    .dragdroparea {
        width: 100%;
        min-height: 100px;
        padding: 6px;
        border: dashed 1px #ccc;
        vertical-align: middle;
    }

    .dragdroparea p {
        margin-top: 30px;
        cursor: pointer;
    }

    .vue-dropzone {
        width: 100%;
    }

    .folder {
        margin-bottom: 25px;
    }
</style>