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
                    <b-numberinput
                        v-if="setting.type === 'numeric'"
                        v-model="setting.value"
                    ></b-numberinput>
                    <b-checkbox
                        v-else-if="setting.type === 'bool'"
                        true-value="1"
                        false-value="0"
                        v-model.numeric="setting.value"
                    >
                        {{ setting.desciption }}
                    </b-checkbox>
                    <quill-editor
                        v-else-if="setting.type === 'textarea'"
                        v-model="setting.value"
                        ref="myQuillEditor"
                        :options="editorOption"
                    ></quill-editor>
                    <section v-else-if="setting.type === 'media'">
                        <vue-dropzone
                            v-if="setting.value === null"
                            ref="myVueDropzone"
                            :options="getDropzoneOptions(setting)"
                            v-on:vdropzone-sending="sendingEvent"
                            v-on:vdropzone-complete="fetchSettings"
                            class="has-margin-bottom-md"
                        ></vue-dropzone>

                        <img
                            v-if="setting.value"
                            :src="setting.value.url"
                            :alt="setting.value.name"
                        />

                        <div v-if="setting.value" class="tags">
                            <span class="tag is-primary">
                                {{ setting.value.name }}
                                <button
                                    class="delete is-small"
                                    type="button"
                                    @click="setting.value = null"
                                ></button>
                            </span>
                        </div>
                    </section>
                    <b-input
                        v-else-if="setting.type === 'email'"
                        type="email"
                        maxlength="30"
                        v-model="setting.value"
                    ></b-input>
                    <b-input v-else v-model="setting.value" expanded></b-input>
                </b-field>
                <div class="control">
                    <button
                        v-if="setting.type !== 'media'"
                        class="button is-primary"
                        @click="sendSetting(setting)"
                    >
                        Update
                    </button>
                </div>
            </div>
        </div>
    </section>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import VueBuefy from '../../admin/Buefy.vue';
import 'quill/dist/quill.core.css';
import 'quill/dist/quill.snow.css';
import 'quill/dist/quill.bubble.css';
import { quillEditor } from 'vue-quill-editor';
import vue2Dropzone from 'vue2-dropzone';

class Setting {
    public id: number;
    public name: string;
    public value: string;
}

@Component({
    name: 'Settings',
    components: {
        quillEditor,
        vueDropzone: vue2Dropzone,
    },
})
export default class Settings extends VueBuefy {
    private loading: boolean = false;
    private settings: Array<Setting> = [];

    getDropzoneOptions(setting: Setting): object {
        return {
            url: `/api/admin/settings/${setting.id}`,
            thumbnailWidth: 200,
            addRemoveLinks: true,
            parallelUploads: 5,
            // Setup chunking
            chunking: true,
            method: 'POST',
            maxFilesize: 400000000,
            chunkSize: 1000000,
            retryChunks: true,
            retryChunksLimit: 5,
            maxThumbnailFilesize: 25,
            // If true, the individual chunks of a file are being uploaded simultaneously.
            // parallelChunkUploads: true,
            acceptedFiles: 'image/*',
            dictDefaultMessage: "<i class='fas fa-images'></i> Upload",
            headers: {
                'X-CSRF-Token': (<HTMLMetaElement>(
                    document.head.querySelector('meta[name="csrf-token"]')
                )).content,
            },
        };
    }

    sendingEvent(file: File, xhr: XMLHttpRequest, formData: FormData): void {
        formData.append('_method', 'patch');
    }

    created(): void {
        this.fetchSettings();
    }

    sendSetting(setting: Setting): void {
        this.loading = true;

        this.axios
            .patch(`/api/admin/settings/${setting.id}`, {
                value: setting.value,
                test: 'test',
            })
            .then(res => res.data)
            .then(res => {
                this.loading = false;
                this.showSuccess('Setting updated');
            })
            .catch(err => {
                this.loading = false;
                this.$snackbar.open({
                    message: 'Unable to save setting, maybe you are offline?',
                    type: 'is-danger',
                    position: 'is-top',
                    actionText: 'Retry',
                    indefinite: false,
                    onAction: () => {
                        this.sendSetting(setting);
                    },
                });
                throw err;
            });
    }

    fetchSettings(): void {
        this.loading = true;

        this.axios
            .get('/api/admin/settings')
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
                    },
                });
                throw err;
            });
    }
}
</script>
