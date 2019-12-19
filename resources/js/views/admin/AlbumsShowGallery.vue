<script lang="ts">
import Component from 'vue-class-component';
import BaseAlbumsShowGallery from '../../components/front/AlbumsShowGallery.vue';

@Component({
  name: 'AlbumsShowGallery'
})
export default class AlbumsShowGallery extends BaseAlbumsShowGallery {
  fetchAlbum (): void {
    let slug = window.location.pathname.split('/')[
      window.location.pathname.split('/').length - 1
    ];
    // Workaround until moved to vue router
    if (slug === 'edit') {
      slug = this.$route.params.slug;
    }
    this.axios
      .get(`/api/admin/albums/${slug}`)
      .then(res => res.data)
      .then(res => {
        this.loading = false;
        this.album = res.data;
      })
      .catch(err => {
        this.album = {};
        this.loading = false;
        this.$buefy.snackbar.open({
          message: 'Unable to load album, maybe you are offline?',
          type: 'is-danger',
          position: 'is-top',
          actionText: 'Retry',
          indefinite: true,
          onAction: () => {
            this.fetchAlbum();
          }
        });
        throw err;
      });
  }
}
</script>
