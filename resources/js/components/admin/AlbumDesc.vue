<template>
  <section>
    <b-field
      :type="errors.title ? 'is-danger' : ''"
      :message="errors.title ? errors.title[0] : null"
      label="Title"
    >
      <b-input
        v-model="album.title"
        type="text"
        maxlength="30"
      />
    </b-field>

    <quill-editor
      ref="myQuillEditor"
      v-model="album.body"
      :options="editorOption"
    />

    <b-field
      :type="errors.categories ? 'is-danger' : ''"
      :message="errors.categories ? errors.categories[0] : null"
      label="Enter some categories"
    >
      <b-taginput
        v-model="album.categories"
        :data="filteredCategories"
        :allow-new="false"
        @typing="getFilteredCategories"
        autocomplete
        field="name"
        placeholder="Add a category"
        icon="tag"
      />
    </b-field>

    <b-field
      :type="errors.cosplayers ? 'is-danger' : ''"
      :message="errors.cosplayers ? errors.cosplayers[0] : null"
      label="Enter some cosplayers"
    >
      <b-taginput
        v-model="album.cosplayers"
        :data="filteredCosplayers"
        :allow-new="false"
        @typing="getFilteredCosplayers"
        autocomplete
        field="name"
        placeholder="Add a cosplayer"
        icon="user-tag"
      />
    </b-field>

    <b-field
      :type="errors.published_at ? 'is-danger' : ''"
      :message="errors.published_at ? errors.published_at[0] : null"
      label="Should this album be published?"
    >
      <div class="field">
        <b-switch
          v-model="album.published_at"
          :true-value="album.published_at || new Date()"
          :false-value="null"
        >
          {{ album.published_at ? 'Published' : 'Draft' }}
        </b-switch>
      </div>
    </b-field>

    <b-field
      :type="errors.private ? 'is-danger' : ''"
      :message="errors.private ? errors.private[0] : null"
      label="Should it be accessible publicly?"
    >
      <div class="field">
        <b-switch
          v-model.numeric="album.private"
          :true-value="true"
          :false-value="false"
        >
          {{ album.private ? 'Publicly' : 'Private' }}
        </b-switch>
      </div>
    </b-field>

    <button class="button is-primary">
      Update
    </button>
  </section>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import 'quill/dist/quill.core.css';
import 'quill/dist/quill.snow.css';
import 'quill/dist/quill.bubble.css';
import Album from '../../models/album';
import Category from '../../models/category';
import Cosplayer from '../../models/cosplayer';
import { quillEditor } from 'vue-quill-editor';
import Buefy from '../../admin/Buefy.vue';

@Component({
    name: 'AlbumDesc',
    components: {
        quillEditor,
    },
})
export default class AlbumDesc extends Buefy {
    protected errors: object = {};
    protected album: Album = new Album();
    protected allowNew = false;
    protected allCategories: Array<Category> = [];
    protected allCosplayers: Array<Cosplayer> = [];
    protected filteredCategories: Array<object> = [];
    protected filteredCosplayers: Array<object> = [];
    protected editorOption: object = {
        placeholder: 'Enter your description...',
        theme: 'snow',
    };

    getFilteredCategories(text: string): void {
        this.axios
            .get('/api/admin/categories', {
                params: {
                    'filter[name]': text,
                },
            })
            .then(res => res.data)
            .then(res => {
                this.filteredCategories = res.data;
            })
            .catch(err => {
                // this.filteredCosplayers = [];
                this.$buefy.snackbar.open({
                    message: 'Unable to load categories, maybe you are offline?',
                    type: 'is-danger',
                    position: 'is-top',
                    actionText: 'Retry',
                    indefinite: true,
                    onAction: () => {
                        this.getFilteredCategories(text);
                    },
                });
                throw err;
            });
    }

    getFilteredCosplayers(text: string): void {
        this.axios
            .get('/api/admin/cosplayers', {
                params: {
                    'filter[name]': text,
                },
            })
            .then(res => res.data)
            .then(res => {
                this.filteredCosplayers = res.data;
            })
            .catch(err => {
                // this.filteredCosplayers = [];
                this.$buefy.snackbar.open({
                    message: 'Unable to load cosplayers, maybe you are offline?',
                    type: 'is-danger',
                    position: 'is-top',
                    actionText: 'Retry',
                    indefinite: true,
                    onAction: () => {
                        this.getFilteredCosplayers(text);
                    },
                });
                throw err;
            });
    }
}
</script>
