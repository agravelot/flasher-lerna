<template>
    <div class="card">
        <div class="card-content">
            <b-field
                label="Name"
                :type="errors.name ? 'is-danger' : ''"
                :message="errors.name ? errors.name[0] : null"
            >
                <b-input v-model="category.name"></b-input>
            </b-field>

            <b-field
                label="Description"
                :type="errors.description ? 'is-danger' : ''"
                :message="errors.description ? errors.description[0] : null"
            >
                <quill-editor
                    v-model="category.description"
                    ref="myQuillEditor"
                    :options="editorOption"
                ></quill-editor>
            </b-field>

            <b-button type="is-primary" :loading="this.loading" @click="createCategory()"
                >Create
            </b-button>
        </div>
    </div>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import VueBuefy from '../../../../../../../resources/js/admin/Buefy.vue';
import Category from '../../category';
import 'quill/dist/quill.core.css';
import 'quill/dist/quill.snow.css';
import 'quill/dist/quill.bubble.css';
import { quillEditor } from 'vue-quill-editor';
import User from '../../../../../../User/Resources/assets/js/user';

@Component({
    name: 'CategoriesEdit',
    components: {
        quillEditor,
    },
})
export default class CategoriesCreate extends VueBuefy {
    private category: Category = new Category();
    private loading: boolean = false;
    private searchUsers: Array<User> = [];
    protected errors: object = {};

    protected editorOption: object = {
        placeholder: 'Enter your description...',
        theme: 'snow',
    };

    created(): void {}

    createCategory(): void {
        this.loading = true;

        this.axios
            .post(`/api/admin/categories`, this.category)
            .then(res => res.data)
            .then(res => {
                this.category = res.data;
                this.loading = false;
                this.showSuccess('Category created');
                this.$router.push({ name: 'admin.categories.index' });
            })
            .catch(err => {
                this.loading = false;
                this.$snackbar.open({
                    message: 'Unable to load category, maybe you are offline?',
                    type: 'is-danger',
                    position: 'is-top',
                    actionText: 'Retry',
                    indefinite: true,
                    onAction: () => {
                        this.createCategory();
                    },
                });
                this.errors = err.response.data.errors;
                throw err;
            });
    }
}
</script>
