<template>
  <section>
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
          :loading="loading"
          type="is-primary"
          @click="updateCategory()"
        >
          Update
        </b-button>
      </div>
    </div>
    <section class="section">
      <div class="card">
        <div class="card-header">
          <div class="card-header-title">
            Cover
          </div>
        </div>
        <div class="card-content">
          <div v-if="category.cover">
            <img
              :src="category.cover.thumb"
              :alt="category.cover.name"
            >
            <a
              class="button has-text-danger"
              @click="deleteCurrentCategoryCover()"
            >
              Delete
            </a>
          </div>
          <div v-else>
            <vue-dropzone
              ref="myVueDropzone"
              :options="dropzoneOptions"
              class="has-margin-bottom-md"
              @vdropzone-sending="sendingEvent"
              @vdropzone-complete="refreshCover"
            />
          </div>
        </div>
      </div>
    </section>
  </section>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import vue2Dropzone from 'vue2-dropzone';
import Buefy from '../../../admin/Buefy.vue';
import Category from '../../../models/category';
import 'quill/dist/quill.core.css';
import 'quill/dist/quill.snow.css';
import 'quill/dist/quill.bubble.css';
import { quillEditor } from 'vue-quill-editor';

@Component({
  name: 'CategoriesEdit',
  components: {
    vueDropzone: vue2Dropzone,
    quillEditor,
  },
})
export default class CategoriesEdit extends Buefy {
    private category: Category = new Category();

    private loading = false;

    protected errors: object = {};

    // TODO Limit 1 file
    dropzoneOptions: object = {
      url: '/api/admin/cover-categories',
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
        'X-CSRF-Token': (
                document.head.querySelector('meta[name="csrf-token"]') as HTMLMetaElement
        ).content,
      },
    };

    protected editorOption: object = {
      placeholder: 'Enter your description...',
      theme: 'snow',
    };

    created(): void {
      this.fetchCategory();
    }

    updateCategory(): void {
      this.loading = true;

      this.axios
        .patch(`/api/admin/categories/${this.$route.params.slug}`, this.category)
        .then((res) => res.data)
        .then((res) => {
          this.category = res.data;
          this.loading = false;
          this.showSuccess('Category updated');
          this.$router.push({ name: 'admin.categories.index' });
        })
        .catch((err) => {
          this.loading = false;
          this.$buefy.snackbar.open({
            message: 'Unable to load category, maybe you are offline?',
            type: 'is-danger',
            position: 'is-top',
            actionText: 'Retry',
            indefinite: true,
            onAction: () => {
              this.updateCategory();
            },
          });
          this.errors = err.response.data.errors;
          throw err;
        });
    }

    fetchCategory(): void {
      this.loading = true;

      this.axios
        .get(`/api/admin/categories/${this.$route.params.slug}`)
        .then((res) => res.data)
        .then((res) => {
          this.category = res.data;
          this.loading = false;
        })
        .catch((err) => {
          this.category = new Category();
          this.loading = false;
          this.$buefy.snackbar.open({
            message: 'Unable to load category, maybe you are offline?',
            type: 'is-danger',
            position: 'is-top',
            actionText: 'Retry',
            indefinite: true,
            onAction: () => {
              this.fetchCategory();
            },
          });
          throw err;
        });
    }

    sendingEvent(file: File, xhr: XMLHttpRequest, formData: FormData): void {
      if (!this.category.slug) {
        throw new DOMException('category slug is null');
      }
      formData.append('category_slug', this.category.slug as string);
    }

    refreshCover(): void {
      this.loading = true;

      this.axios
        .get(`/api/admin/categories/${this.$route.params.slug}`)
        .then((res) => res.data)
        .then((res) => {
          this.category.cover = res.data.cover;
          this.loading = false;
        })
        .catch((err) => {
          this.category.cover = null;
          this.loading = false;
          this.$buefy.snackbar.open({
            message: 'Unable to load category, maybe you are offline?',
            type: 'is-danger',
            position: 'is-top',
            actionText: 'Retry',
            indefinite: true,
            onAction: () => {
              this.fetchCategory();
            },
          });
          throw err;
        });
    }

    deleteCurrentCategoryCover(): void {
      this.loading = true;

      this.axios
        .delete(`/api/admin/cover-categories/${this.$route.params.slug}`)
        .then((res) => res.data)
        .then(() => {
          this.category.cover = null;
          this.loading = false;
        })
        .catch((err) => {
          this.loading = false;
          this.$buefy.snackbar.open({
            message: 'Unable to delete cover category, maybe you are offline?',
            type: 'is-danger',
            position: 'is-top',
            actionText: 'Retry',
            indefinite: true,
            onAction: () => {
              this.deleteCurrentCategoryCover();
            },
          });
          throw err;
        });
    }
}
</script>
