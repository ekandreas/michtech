<template>

    <div class="panel folder" v-if="data.name && (visible=='public' || (administrator && visible=='admin'))">
        <p class="panel-heading" style="background-color: #333; color:#fff;">
            {{ data.name }}
            <i v-if="authenticated" class="fa fa-unlock-alt pull-right" aria-hidden="true"></i>
        </p>
        <p class="panel-block" v-if="description" v-html="description">
        </p>
        <div class="panel-block">
            <div class="column" v-if="showdocuments">
                <button @click="toggle(1)"
                        ref="documentButton"
                        class="button is-primary is-fullwidth"
                        v-bind:class="{'is-outlined': data.documents.length==0, 'is-loading': loadingDocuments }">
                    Dokument
                </button>
            </div>
            <div class="column" v-if="showuploads">
                <button @click="toggle(2)"
                        ref="uploadButton"
                        class="button is-danger is-fullwidth"
                        v-bind:class="{'is-outlined': data.uploads.length==0, 'is-loading': loadingUploads }">
                    Uppladdat
                </button>
            </div>
        </div>

        <a class="panel-block" v-if="data.documents.length" v-for="file in data.documents" @click="download(file)">
            <span class="panel-icon">
              <i v-if="file.type=='file'" class="fa fa-file-o"></i>
              <i v-if="file.type=='folder'" class="fa fa-folder"></i>
              <i v-if="file.type=='back'" class="fa fa-arrow-up"></i>
            </span>
            <span v-if="file.type=='back'">
                ...
            </span>
            <span v-else>
                {{ file.path.substr(file.path.lastIndexOf("/") + 1) }}
            </span>
        </a>

        <div class="panel-block" v-if="data.uploads.length" v-for="file in data.uploads">
            <a @click="download(file)" style="color:#333;">
                  <i v-if="file.type=='file'" class="fa fa-file is-info"></i>
                {{ file.path.substr(file.path.lastIndexOf("/") + 1) }}
            </a>
            <!--span v-if="administrator"-->
            <span v-if="false">
                <a href="#" @click="remove(file)" style="margin-left: 20px;" class="button is-warning is-small pull-right">
                    <i class="fa fa-spinner fa-pulse" v-if="file.removing"></i>Radera
                </a>
            </span>
        </div>

        <div class="panel-block" v-if="authenticated && view==2">
            <dropzone
                    :maxFileSizeInMB="999"
                    :timeout="300000"
                    :autoProcessQueue="true"
                    :useFontAwesome="true"
                    :language="langSE"
                    ref="folderUpload"
                    :id="dropZoneId"
                    :url="uploadUrl"
                    @vdropzone-success="success"
                    @vdropzone-error="error">
            </dropzone>
        </div>
    </div>
</template>

<script>

    import VueCookies from 'vue-cookies'

    export default {
        props: ['id','showdocuments','showuploads','administrator', 'visible', 'description'],
        data() {
            let self = this;
            return {
                view: 0,
                data: {},
                loadingDocuments: false,
                loadingUploads: false,
                uploadUrl: '/api/folder/' + self.id + '/upload',
                authenticated: false,
                dropZoneId: 'dropZone' + self.id,
                langSE: {
                    dictDefaultMessage: '<p>Klicka eller släpp dokument här för att ladda upp!</p>'
                }
            }
        },
        mounted()
        {
            let self = this;
            self.load();
            if (cookies.get('michtech-folder-' + self.id)) {
                self.authenticated = true;
            }
        },
        methods: {
            error(file) {
                let self = this;
                alert('Det gick inte att ladda upp ' + file.name + '. Avbrott eller för stort dokument?');
                console.log(file);
            },
            success(file, response) {
                let self = this;
                self.loadUploads();
                self.view = 2;
                self.$refs.folderUpload.removeAllFiles();
            },
            toggle(view) {
                let self = this;
                if (self.view == view) {

                    if (view == 1) {
                        self.data.documents = [];
                        self.$refs.documentButton.blur();
                    }

                    if (view == 2) {
                        self.data.uploads = [];
                        self.$refs.uploadButton.blur();
                    }

                    self.view = 0;
                }
                else {
                    self.view = view;
                    if (view == 1) self.loadDocs();
                    if (view == 2) self.loadUploads();
                }
            },
            load() {
                let self = this;
                self.data.documents = [];
                axios.get('api/folder/' + self.id).then(function (response) {
                    self.data = response.data;
                    self.clearLoading();
                });
            },
            loadDocs() {
                let self = this;
                self.setAuth();
                self.setLoading(1);
                axios.get('api/folder/' + self.id + '/documents', {timeout: 3000}).then(function (response) {
                    if (self.authenticated) self.data.documents = response.data;
                    self.clearLoading();
                }).catch(function () {
                    self.clearLoading();
                    self.data.documents = [];
                });
            },
            loadUploads() {
                let self = this;
                self.setAuth();
                self.setLoading(2);
                axios.get('api/folder/' + self.id + '/uploads', {timeout: 3000}).then(function (response) {
                    if (self.authenticated) self.data.uploads = response.data;
                    self.clearLoading();
                }).catch(function () {
                    self.data.uploads = [];
                    self.clearLoading();
                });
            },
            setLoading(type)   {
                let self = this;
                self.data.documents = [];
                self.data.uploads = [];
                if (type == 1) {
                    setTimeout(function () {
                        self.loadingDocuments = true;
                    }, 10);
                }
                if (type == 2) {
                    setTimeout(function () {
                        self.loadingUploads = true;
                    }, 10);
                }
            },
            clearLoading()   {
                let self = this;
                setTimeout(function () {
                    self.loadingUploads = false;
                    self.loadingDocuments = false;
                }, 1000);
            },
            mimeIcon(file) {
                let mime = file.mime.toString();
                if (mime.match('^application/pdf')) return 'fa fa-file-pdf-o';
                if (mime.match('^image')) return 'fa fa-file-image-o';
                if (mime.match('zip')) return 'fa fa-file-zip-o';
                if (mime.match('^application\/')) return 'fa fa-file-o';
                return 'fa fa-file-o';
            },
            setAuth() {
                let self = this;

                if (cookies.get('michtech-folder-' + self.id)) {
                    self.authenticated = true;
                    return;
                }

                let passcode = prompt('Ange kod för åtkomst!');
                axios.post('api/folder/' + self.id + '/passcode', {passcode}).then(function (response) {
                    if (response.data == 'success') {
                        self.authenticated = true;
                        cookies.set('michtech-folder-' + self.id, passcode, {expires: 1}); // Expires after 1 day
                    }
                    else {
                        alert('Felaktig kod angiven!');
                        self.authenticated = false;
                        self.$cookies.remove('michtech-folder-' + self.id);
                        self.data.documents = [];
                        self.data.uploads = [];
                    }
                });
            },
            remove(file) {
                let self = this;
                if (self.authenticated) {
                    self.setLoading(1);
                    axios.delete('api/folder/' + self.id + '/documents/' + file.id, {timeout: 3000}).then(function (response) {
                        self.loadUploads();
                    }).catch(function () {
                        self.clearLoading();
                    });
                }
                return false;
            },
            download(file) {
                let self = this;
                if (self.authenticated) {

                    if (file.type == 'file') {

                        var file_path = 'folder/' + self.id + '/item/' + file.id;
                        var a = document.createElement('A');
                        a.href = file_path;
                        a.download = file_path.substr(file_path.lastIndexOf('/') + 1);
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);

                        //document.location = 'folder/' + self.id + '/item/' + file.id;
                    }
                    else {
                        self.setLoading(1);
                        axios.get('api/folder/' + self.id + '/documents/' + file.id, {timeout: 3000}).then(function (response) {
                            if (self.authenticated) self.data.documents = response.data;
                            self.clearLoading();
                        }).catch(function () {
                            self.clearLoading();
                            self.data.documents = [];
                        });
                    }

                }
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