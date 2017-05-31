<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th><abbr title="ID">ID</abbr></th>
                        <th>Rubrik</th>
                        <th>Prio</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="text in texts">
                        <th>{{ text.id }}</th>
                        <td>
                            <a href="javascript:void(0)" v-on:click="edit(text)">
                                {{ text.headline }}
                            </a>
                        </td>
                        <td>{{ text.prio }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <a class="button is-primary" @click="create">
            <i class="fa fa-plus"></i>&nbsp;
            Skapa ny text
        </a>

        <div class="modal" ref="modal" :class="{'is-active':editMode}" v-if="currentText">
            <div class="modal-background" @click="abort"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">{{ currentText.headline }}</p>
                    <button class="delete" @click="abort"></button>
                </header>
                <section class="modal-card-body">
                    <div class="field">
                        <label class="label">Rubrik</label>
                        <p class="control">
                            <input class="input" type="text" placeholder="Rubrik" v-model="currentText.headline">
                        </p>
                        <p class="help">Rubrik på text för användarna</p>
                    </div>
                    <div class="field">
                        <label class="label">Text</label>
                        <p class="control">
                            <div class="editable" ref="editor"></div>
                        </p>
                        <p class="help">Löpande text</p>
                    </div>
                    <div class="field">
                        <label class="label">Prio</label>
                        <p class="control">
                            <input class="input" type="text" placeholder="Prio" v-model="currentText.prio">
                        </p>
                        <p class="help">Lägst siffra visas först</p>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <a class="button is-success" @click="save">Spara</a>
                    <a class="button" @click="abort">Avbryt</a>
                    <a v-if="currentText.id" class="button is-danger is-pulled-right" @click="remove">Ta bort</a>
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

            tinymce.init({
                selector: 'div.editable',
                height: 300,
                menubar: false,
                plugins: [
                    'advlist autolink lists link charmap print preview anchor',
                    'searchreplace visualblocks code',
                    'insertdatetime table contextmenu paste code'
                ],
                toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link',
                content_css: [
                    '//fonts.googleapis.com/css?family=Roboto:300,300i,400,400i'
                ],
                init_instance_callback: function (editor) {
                    editor.on('change', function (e) {
                        self.currentText.body = tinymce.activeEditor.getContent();
                    });
                }
            });

        },
        data() {
            return {
                texts: [],
                editMode: false,
                currentText: {}
            }
        },
        methods: {
            load() {
                let self = this;
                axios.get('/api/admin/texts').then(response => self.texts = response.data);
            },
            edit(text) {
                let self = this;
                self.currentText = text;
                self.editMode = true;
                tinymce.activeEditor.setContent(self.currentText.body);
            },
            create() {
                let self = this;
                self.currentText = {
                    id: 0,
                    headline: 'En ny rubrik med text',
                    body: '<p></p>',
                    prio: 10
                };
                self.editMode = true;
            },
            abort() {
                let self = this;
                self.editMode = false;
            },
            save() {
                let self = this;
                if (self.currentText.id) {
                    self.update();
                }
                else {
                    self.insert();
                }
            },
            update() {
                let self = this;
                axios.put('/api/admin/texts/' + self.currentText.id, {
                    headline: self.currentText.headline,
                    body: self.currentText.body,
                    prio: self.currentText.prio
                }).then(function (response) {
                    self.editMode = false;
                    self.load();
                });
            },
            insert() {
                let self = this;
                axios.post('/api/admin/texts', {
                    headline: self.currentText.headline,
                    body: self.currentText.body,
                    prio: self.currentText.prio
                }).then(function (response) {
                    self.editMode = false;
                    self.load();
                });
            },
            remove(folder) {
                let self = this;
                let question = 'Borttag går inte att ångra.';
                if (confirm(question)) {
                    axios.delete('/api/admin/texts/' + self.currentText.id).then(function (response) {
                        self.editMode = false;
                        self.load();
                    });
                }
            }
        }
    }
</script>
