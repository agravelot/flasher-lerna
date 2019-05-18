<template>
    <div class="card">
        <div class="card-content">
            <b-field label="Name">
                <b-input v-model="cosplayer.name"></b-input>
            </b-field>

            <b-field label="Description">
                <quill-editor v-model="cosplayer.description" ref="myQuillEditor"
                              :options="editorOption"></quill-editor>
            </b-field>

            <b-field v-if="cosplayer.avatar" label="Current avatar">
                <img :src="cosplayer.avatar" alt="">
                <b-button type="is-danger" icon-right="delete" @click="cosplayer.avatar = null"></b-button>
            </b-field>


            <div class="columns">
                <div class="column">
                    <b-field label="Upload avatar">
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
                    <b-field label="Linked user">
                        <b-autocomplete
                                :data="searchUsers"
                                v-model="cosplayer.user_id"
                                placeholder="e.g. Anne"
                                keep-first
                                open-on-focus
                                @typing="searchUser"
                                field="id"
                                @select="option => selected = option">
                            <template slot-scope="props">
                                <div>
                                    {{ props.option.name }}
                                    <br>
                                    <small>
                                        Email: {{ props.option.email }},
                                        role <b>{{ props.option.role }}</b>
                                    </small>
                                </div>
                            </template>
                        </b-autocomplete>
                    </b-field>
                </div>
            </div>

            <b-button type="is-primary" :loading="this.loading" @click="updateCosplayer()">Update</b-button>
        </div>
    </div>
</template>

<script lang="ts">
    import Vue from 'vue';
    import Component from 'vue-class-component';
    import VueBuefy from "../../../../../../../resources/js/buefy";
    import Cosplayer from '../../cosplayer';
    import 'quill/dist/quill.core.css'
    import 'quill/dist/quill.snow.css'
    import 'quill/dist/quill.bubble.css'
    import {quillEditor} from 'vue-quill-editor'
    import User from "../../../../../../User/Resources/assets/js/user";

    @Component({
        name: "CosplayersEdit",
        components: {
            quillEditor,
        },
    })
    export default class CosplayersEdit extends VueBuefy {

        private cosplayer: Cosplayer = new Cosplayer();
        private loading: boolean = false;
        private searchUsers: Array<User> = [];

        protected editorOption: object = {
            placeholder: 'Enter your description...',
            theme: 'snow',
        };

        created(): void {
            this.fetchCosplayer();
        }

        updateCosplayer(): void {
            this.loading = true;

            Vue.axios.patch(`/api/admin/cosplayers/${this.$route.params.slug}`, this.cosplayer)
                .then(res => res.data)
                .then(res => {
                    this.cosplayer = res.data;
                    this.loading = false;
                    this.showSuccess('Cosplayer updated');
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
                            this.updateCosplayer();
                        }
                    });
                    throw err;
                });
        }

        fetchCosplayer(): void {
            this.loading = true;

            Vue.axios.get(`/api/admin/cosplayers/${this.$route.params.slug}`)
                .then(res => res.data)
                .then(res => {
                    this.cosplayer = res.data;
                    this.loading = false;
                })
                .catch(err => {
                    this.cosplayer = new Cosplayer();
                    this.loading = false;
                    this.$snackbar.open({
                        message: 'Unable to load cosplayer, maybe you are offline?',
                        type: 'is-danger',
                        position: 'is-top',
                        actionText: 'Retry',
                        indefinite: true,
                        onAction: () => {
                            this.fetchCosplayer();
                        }
                    });
                    throw err;
                });
        }

        searchUser(): void {
            Vue.axios.get('/api/admin/users', {params: {'filter[name]': this.cosplayer.user_id}})
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
                })
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
    }
</script>
