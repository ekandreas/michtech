<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th><abbr title="ID">ID</abbr></th>
                        <th>Mapp</th>
                        <th>Pinkod</th>
                        <th>Prio</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="folder in folders">
                        <th>{{ folder.id }}</th>
                        <td>
                            <a href="javascript:void(0)" v-on:click="edit(folder)">
                                {{ folder.name }}
                            </a>
                        </td>
                        <td>{{ folder.passcode }}</td>
                        <td>{{ folder.prio }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <a class="button is-primary" @click="create">
            <i class="fa fa-plus"></i>&nbsp;
            Skapa ny mapp
        </a>

        <div class="modal" ref="modal" :class="{'is-active':editMode}" v-if="currentFolder">
            <div class="modal-background" @click="abort"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">{{ currentFolder.name }}</p>
                    <button class="delete" @click="abort"></button>
                </header>
                <section class="modal-card-body">
                    <div class="field">
                        <label class="label">Namn</label>
                        <p class="control">
                            <input class="input" type="text" placeholder="Namn" v-model="currentFolder.name">
                        </p>
                        <p class="help">Namnet på mappen för användarna</p>
                    </div>
                    <div class="field">
                        <label class="label">Pinkod</label>
                        <p class="control">
                            <input class="input" type="text" placeholder="Pinkod" v-model="currentFolder.passcode">
                        </p>
                        <p class="help">Access för att se och ladda upp</p>
                    </div>
                    <div class="field">
                        <label class="label">Prio</label>
                        <p class="control">
                            <input class="input" type="text" placeholder="Prio" v-model="currentFolder.prio">
                        </p>
                        <p class="help">Lägst siffra visas först</p>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <a class="button is-success" @click="save">Spara</a>
                    <a class="button" @click="abort">Avbryt</a>
                    <a v-if="currentFolder.id" class="button is-danger is-pulled-right" @click="remove">Ta bort</a>
                </footer>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        mounted() {
            let self = this;
            self.load();
        },
        data() {
            return {
                folders: [],
                editMode: false,
                currentFolder: {}
            }
        },
        methods: {
            load() {
                let self = this;
                axios.get('/api/admin/folder').then(response => self.folders = response.data);
            },
            edit(folder) {
                let self = this;
                self.currentFolder = folder;
                self.editMode = true;
            },
            create() {
                let self = this;
                self.currentFolder = {
                    id: 0,
                    name:'Ett namn på mappen',
                    passcode:'0000',
                    prio: '999'
                };
                self.editMode = true;
            },
            abort() {
                let self = this;
                self.editMode = false;
            },
            save() {
                let self = this;
                if(self.currentFolder.id) {
                    self.update();
                }
                else {
                    self.insert();
                }
            },
            update() {
                let self = this;
                axios.put('/api/admin/folder/'+self.currentFolder.id, {
                    name: self.currentFolder.name,
                    passcode: self.currentFolder.passcode,
                    prio: self.currentFolder.prio
                }).then(function (response) {
                    self.editMode = false;
                    self.load();
                });
            },
            insert() {
                let self = this;
                axios.post('/api/admin/folder', {
                    name: self.currentFolder.name,
                    passcode: self.currentFolder.passcode,
                    prio: self.currentFolder.prio
                }).then(function (response) {
                    self.editMode = false;
                    self.load();
                });
            },
            remove(folder) {
                let self = this;
                let question='Du kommer att tappa kopplingen mellan ID och mapp/sparade filer.\n' +
                    'Borttag går inte att ångra.';
                if(confirm(question)) {
                    axios.delete('/api/admin/folder/'+self.currentFolder.id).then(function (response) {
                        self.editMode = false;
                        self.load();
                    });
                }
            }
        }
    }
</script>
