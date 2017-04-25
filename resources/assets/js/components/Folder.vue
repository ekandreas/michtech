<template>
    <div class="panel folder" v-if="data.name">
        <p class="panel-heading">
            {{ data.name }}
        </p>
        <div class="panel-block">
                <div class="column">
                    <button @click="toggle(1)" class="button is-success is-fullwidth" v-bind:class="{'is-outlined': view!=1 }">Dokument</button>
                </div>
                <div class="column">
                    <button  @click="toggle(2)" class="button is-info is-fullwidth" v-bind:class="{'is-outlined': view!=2 }">Uppladdat</button>
                </div>
        </div>
        <a class="panel-block" v-if="view==2" v-for="file in data.uploads" @click="download(file)">
            {{ file }}
        </a>
        <a class="panel-block" v-if="view==1" v-for="file in data.documents" @click="download(file)">
            {{ file }}
        </a>
        <div class="panel-block">
            <dropzone
                    :useCustomDropzoneOptions="true"
                    :dropzoneOptions="uploadOptions"
                    :autoProcessQueue="false"
                    ref="folderUpload"
                    :useFontAwesome="dropzone.useFontAwesome"
                    :id="dropZoneId"
                    :url="uploadUrl">
            </dropzone>
        </div>
    </div>
</template>

<script>

    export default {
        props: ['id'],
        data() {
            let self = this;
            return {
                view: 0,
                data: {},
                uploadUrl: '/api/folder/' + self.id + '/upload',
                dropzone: {
                    useFontAwesome: true
                },
                dropZoneId: 'dropZone' + self.id,
                uploadOptions: {
                    autoProcessQueue: false,
                    dictDefaultMessage: '<p><i class="fa fa-cloud-upload"></i><br/>Klicka eller släpp dokument här för att ladda upp!</p>',
                    addedfile(file) {
                        let passcode = prompt('Ange kod för åtkomst!');
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
            toggle(view) {
                let self = this;
                if(self.view==view) {
                    self.view=0;
                }
                else {
                    self.view=view;
                }
            },
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
                let passcode = prompt('Ange kod för åtkomst!');
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
    .dropzone .dz-message {
        margin: 4px;
    }

    .dropzone {
        padding: 6px;
        margin: 0px;
        min-height: 0px;
    }

    .dragdroparea {
        width: 100%;
        border: dashed 1px #ccc;
        vertical-align: middle;
        max-height: 50px;
    }

    .dragdroparea {
        cursor: pointer;
    }

    .vue-dropzone {
        width: 100%;
    }

    .folder {
        margin-bottom: 25px;
    }
</style>