<template>
  <section>
    <h1 class="title">
      Create album
    </h1>

    <div class="card">
      <div class="card-content">
        <form @submit.prevent="createAlbum">
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
                :true-value="album.published_at || new Date().toISOString()"
                :false-value="null"
              >
                {{ album.published_at ? 'Yes' : 'No' }}
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
                v-model="album.private"
                :false-value="true"
                :true-value="false"
              >
                {{ album.private ? 'No' : 'Yes' }}
              </b-switch>
            </div>
          </b-field>

          <button class="button is-primary">
            Create
          </button>
        </form>
      </div>
    </div>
  </section>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import AlbumDesc from './AlbumDesc.vue';

@Component({
    name: 'AlbumsCreate',
    extends: AlbumDesc,
    components: {
        'album-desc': AlbumDesc,
    },
})
export default class AlbumsCreate extends AlbumDesc {
    createAlbum(): void {
        this.axios
            .post(`/api/admin/albums/`, this.album)
            .then(res => res.data)
            .then(res => {
                this.showSuccess('Album successfully created');
                this.$router.push({ name: 'admin.albums.edit', params: { slug: res.data.slug } });
            })
            .catch(err => {
                this.showError(
                    `Unable to create the album <br><small>${err.response.data.message}</small>`
                );
                this.errors = err.response.data.errors;
            });
    }
}
</script>
