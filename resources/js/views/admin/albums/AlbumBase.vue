<script lang="ts">
import { Component } from 'vue-property-decorator';
import 'quill/dist/quill.core.css';
import 'quill/dist/quill.snow.css';
import 'quill/dist/quill.bubble.css';
import Album from '../../../models/album';
import Category from '../../../models/category';
import Cosplayer from '../../../models/cosplayer';
import Buefy from '../../../admin/Buefy.vue';
import FilterableById from '../../../models/interfaces/filterableById';

@Component({})
export default class AlbumBase extends Buefy {
    protected errors: object = {};

    protected album: Album | undefined;

    protected allowNew = false;

    protected allCategories: Array<Category> = [];

    protected allCosplayers: Array<Cosplayer> = [];

    protected filteredCategories: Array<Category> = [];

    protected filteredCosplayers: Array<Cosplayer> = [];

    protected editorOption: object = {
      placeholder: 'Enter your description...',
      theme: 'snow',
    };

    debounce(callback: (text: string) => void, text: string, time: number): void {
      // eslint-disable-next-line @typescript-eslint/no-explicit-any
      (window as any).lastCall = (window as any).lastCall ? (window as any).lastCall : 0;

      // eslint-disable-next-line @typescript-eslint/no-explicit-any
      if (Date.now() - (window as any).lastCall > time) {
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        (window as any).timeout = setTimeout(() => callback(text), time);
      } else {
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        clearTimeout(((window as any).timeout as number));
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        (window as any).timeout = setTimeout(() => callback(text), time);
      }
      // eslint-disable-next-line @typescript-eslint/no-explicit-any
      (window as any).lastCall = Date.now();
    }

    isCategoryAlreadySelected(filterable: FilterableById): boolean {
      return !!this.album?.categories?.some((c) => c.id === filterable.id);
    }

    isCategoryNotAlreadySelected(filterable: FilterableById): boolean {
      return !this.isCategoryAlreadySelected(filterable);
    }

    isCosplayerAlreadySelected(filterable: FilterableById): boolean {
      return !!this.album?.cosplayers?.some((c) => c.id === filterable.id);
    }

    isCosplayerNotAlreadySelected(filterable: FilterableById): boolean {
      return !this.isCategoryAlreadySelected(filterable);
    }

    getFilteredCategories(text: string): void {
      const callback = (text: string): void => {
        this.axios
          .get('/api/admin/categories', {
            params: {
              'filter[name]': text,
            },
          })
          .then((res) => res.data)
          .then((res) => {
            this.filteredCategories = res.data.filter(this.isCategoryNotAlreadySelected);
          })
          .catch((err) => {
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
      };
      this.debounce(callback, text, 200);
    }

    getFilteredCosplayers(text: string): void {
      const callback = (text: string): void => {
        this.axios
          .get('/api/admin/cosplayers', {
            params: {
              'filter[name]': text,
            },
          })
          .then((res) => res.data)
          .then((res) => {
            this.filteredCosplayers = res.data.filter(this.isCosplayerNotAlreadySelected);
          })
          .catch((err) => {
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
      };
      this.debounce(callback, text, 200);
    }
}
</script>
