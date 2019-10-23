<template>
  <div class="card">
    <div class="card-content">
      <b-field
        :type="errors.name ? 'is-danger' : ''"
        :message="errors.name ? errors.name[0] : null"
        label="Name"
      >
        <b-input v-model="category.name" />
      </b-field>

      <b-field
        :type="errors.description ? 'is-danger' : ''"
        :message="errors.description ? errors.description[0] : null"
        label="Description"
      >
        <quill-editor
          ref="myQuillEditor"
          v-model="category.description"
          :options="editorOption"
        />
      </b-field>

      <b-button
        :loading="this.loading"
        @click="createCategory()"
        type="is-primary"
      >
        Create
      </b-button>
    </div>
  </div>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import Buefy from '../../admin/Buefy.vue';
import Category from '../../models/category';
import 'quill/dist/quill.core.css';
import 'quill/dist/quill.snow.css';
import 'quill/dist/quill.bubble.css';
import { quillEditor } from 'vue-quill-editor';
import User from '../../models/user';

@Component({
    name: 'CategoriesEdit',
    components: {
        quillEditor,
    },
})
export default class CategoriesCreate extends Buefy {
    private category: Category = new Category();
    private loading = false;
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
                this.$buefy.snackbar.open({
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
