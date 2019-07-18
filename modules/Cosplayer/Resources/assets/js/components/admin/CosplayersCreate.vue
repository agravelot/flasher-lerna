<template>
    <div class="card">
        <div class="card-content">
            <b-field
                label="Name"
                :type="errors.name ? 'is-danger' : ''"
                :message="errors.name ? errors.name[0] : null"
            >
                <b-input v-model="cosplayer.name"></b-input>
            </b-field>

            <b-field
                label="Description"
                :type="errors.description ? 'is-danger' : ''"
                :message="errors.description ? errors.description[0] : null"
            >
                <quill-editor
                    v-model="cosplayer.description"
                    ref="myQuillEditor"
                    :options="editorOption"
                ></quill-editor>
            </b-field>

            <div class="columns">
                <div class="column">
                    <div v-if="cosplayer.avatar">
                        <label class="label">Current avatar</label>
                        <img :src="cosplayer.avatar.thumb" alt="" />
                        <b-button
                            type="is-danger"
                            icon-right="trash-alt"
                            @click="cosplayer.avatar = null"
                        ></b-button>
                    </div>

                    <b-field
                        v-else
                        label="Upload avatar"
                        :type="errors.avatar ? 'is-danger' : ''"
                        :message="errors.avatar ? errors.avatar[0] : null"
                    >
                        <b-upload v-model="cosplayer.avatar" drag-drop>
                            <section class="section">
                                <div class="content has-text-centered">
                                    <p>
                                        <b-icon icon="upload" size="is-large"></b-icon>
                                    </p>
                                    <p>Drop your files here or click to upload</p>
                                    <span class="file-name" v-if="cosplayer.avatar">
                                        {{ cosplayer.avatar.name }}
                                    </span>
                                </div>
                            </section>
                        </b-upload>
                    </b-field>
                </div>
                <div class="column">
                    <b-field
                        label="Linked user"
                        :type="errors.user_id ? 'is-danger' : ''"
                        :message="errors.user_id ? errors.user_id[0] : null"
                    >
                        <b-autocomplete
                            :data="searchUsers"
                            v-model="cosplayer.user_id"
                            placeholder="e.g. Anne"
                            keep-first
                            open-on-focus
                            @typing="searchUser"
                            field="id"
                            @select="option => (selected = option)"
                        >
                            <template slot-scope="props">
                                <div>
                                    {{ props.option.name }}
                                    <br />
                                    <small>
                                        Email: {{ props.option.email }}, role
                                        <b>{{ props.option.role }}</b>
                                    </small>
                                </div>
                            </template>
                        </b-autocomplete>
                    </b-field>
                </div>
            </div>

            <b-button type="is-primary" :loading="this.loading" @click="createCosplayer()"
                >Create
            </b-button>
        </div>
    </div>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import VueBuefy from '../../../../../../../resources/js/admin/Buefy.vue';
import Cosplayer from '../../cosplayer';
import 'quill/dist/quill.core.css';
import 'quill/dist/quill.snow.css';
import 'quill/dist/quill.bubble.css';
import { quillEditor } from 'vue-quill-editor';
import User from '../../../../../../User/Resources/assets/js/user';

@Component({
    name: 'CosplayersEdit',
    components: {
        quillEditor,
    },
})
export default class CosplayersCreate extends VueBuefy {
    private cosplayer: Cosplayer = new Cosplayer();
    private loading: boolean = false;
    private searchUsers: Array<User> = [];
    protected errors: object = {};

    protected editorOption: object = {
        placeholder: 'Enter your description...',
        theme: 'snow',
    };

    created(): void {}

    createCosplayer(): void {
        this.loading = true;

        let formData: FormData = this.cosplayerToFormData(this.cosplayer);
        this.axios
            .post(`/api/admin/cosplayers`, formData, {
                headers: { 'Content-Type': 'multipart/form-data' },
            })
            .then(res => res.data)
            .then(res => {
                this.cosplayer = res.data;
                this.loading = false;
                this.showSuccess('Cosplayer created');
                this.cosplayer = new Cosplayer();
            })
            .catch(err => {
                this.loading = false;
                this.$snackbar.open({
                    message: 'Unable to load cosplayer, maybe you are offline?',
                    type: 'is-danger',
                    position: 'is-top',
                    actionText: 'Retry',
                    indefinite: true,
                    onAction: () => {
                        this.createCosplayer();
                    },
                });
                this.errors = err.response.data.errors;
                throw err;
            });
    }

    searchUser(): void {
        this.axios
            .get('/api/admin/users', { params: { 'filter[name]': this.cosplayer.user_id } })
            .then(res => res.data)
            .then(res => {
                this.searchUsers = res.data;
            })
            .catch(err => {
                this.$snackbar.open({
                    message: 'Unable to load users, maybe you are offline?',
                    type: 'is-danger',
                    position: 'is-top',
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

    showError(message: string): void {
        this.$toast.open({
            message: message,
            type: 'is-danger',
            duration: 5000,
        });
    }

    cosplayerToFormData(cosplayer: Cosplayer): FormData {
        let formData = new FormData();
        //TODO Rewrite
        if (cosplayer.name) {
            formData.append('name', cosplayer.name);
        }
        if (cosplayer.description) {
            formData.append('description', cosplayer.description);
        }
        if (cosplayer.user_id) {
            formData.append('user_id', String(cosplayer.user_id));
        }
        if (cosplayer.avatar != null) {
            formData.append('avatar', cosplayer.avatar);
        }

        return formData;
    }
}
</script>
