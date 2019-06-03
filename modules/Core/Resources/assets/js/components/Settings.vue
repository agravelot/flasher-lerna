<template>
    <section class="card">
        <div class="card-header">
            <div class="card-header-title">
                <h1>Settings</h1>
            </div>
        </div>
       <div class="card-content">
           <div v-for="setting in settings">
               <b-field :label="setting.title">
                   <b-numberinput v-if="setting.type === 'numeric'" v-model="setting.value"></b-numberinput>
                   <b-checkbox v-else-if="setting.type === 'bool'"
                               true-value="1"
                               false-value="0"
                               v-model.numeric="setting.value">
                       {{ setting.desciption }}
                   </b-checkbox>
                   <quill-editor v-else-if="setting.type === 'textarea'" v-model="setting.value" ref="myQuillEditor" :options="editorOption"></quill-editor>
                   <b-input v-else v-model="setting.value" expanded></b-input>

               </b-field>
               <div class="control">
                   <button class="button is-primary" @click="sendSetting(setting)">Update</button>
               </div>
           </div>
       </div>
    </section>
</template>

<script lang="ts">
    import Vue from 'vue';
    import Component from 'vue-class-component';
    import VueBuefy from "../../../../../../resources/js/admin/Buefy.vue";
    import 'quill/dist/quill.core.css'
    import 'quill/dist/quill.snow.css'
    import 'quill/dist/quill.bubble.css'
    import {quillEditor} from 'vue-quill-editor'

    class Setting {
        public name: string;
        public value: string;
    }

    @Component({
        name: "Core.Resources.assets.js.components.Settings",
        components: {
            quillEditor,
        },
    })
    export default class Settings extends VueBuefy {

        private loading: boolean = false;
        private settings: Array<Setting> = [];

        created(): void {
            this.fetchSettings();
        }

        sendSetting(setting: Setting): void {
            this.loading = true;

            Vue.axios.patch(`/api/admin/settings/${setting.name}`, {
                'value': setting.value
            })
                .then(res => res.data)
                .then(res => {
                    this.loading = false;
                    this.showSuccess('Setting updated');
                })
                .catch(err => {
                    this.settings = [];
                    this.loading = false;
                    this.$snackbar.open({
                        message: 'Unable to save setting, maybe you are offline?',
                        type: 'is-danger',
                        position: 'is-top',
                        actionText: 'Retry',
                        indefinite: false,
                        onAction: () => {
                            this.sendSetting(setting);
                        }
                    });
                    throw err;
                });
        }

        showSuccess(message: string): void {
            this.$toast.open({
                message: message,
                type: 'is-success',
            });
        }

        fetchSettings(): void {
            this.loading = true;

            Vue.axios.get('/api/admin/settings')
                .then(res => res.data)
                .then(res => {
                    this.settings = res.data;
                    this.loading = false;
                })
                .catch(err => {
                    this.settings = [];
                    this.loading = false;
                    this.$snackbar.open({
                        message: 'Unable to load settings, maybe you are offline?',
                        type: 'is-danger',
                        position: 'is-top',
                        actionText: 'Retry',
                        indefinite: true,
                        onAction: () => {
                            this.fetchSettings();
                        }
                    });
                    throw err;
                });
        }

    }
</script>
