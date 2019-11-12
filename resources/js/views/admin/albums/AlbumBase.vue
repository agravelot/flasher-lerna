<script lang="ts">
import { Component } from 'vue-property-decorator';
import 'quill/dist/quill.core.css';
import 'quill/dist/quill.snow.css';
import 'quill/dist/quill.bubble.css';
import Album from '../../../models/album';
import Category from '../../../models/category';
import Cosplayer from '../../../models/cosplayer';
import Buefy from '../../../admin/Buefy.vue';

@Component({})
export default class AlbumBase extends Buefy {
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

    debounce(callback: any, text: string, time: number): void {
        (window as any).lastCall = (window as any).lastCall ? (window as any).lastCall : 0;

        if (Date.now() - (window as any).lastCall > time) {
            (window as any).timeout = setTimeout(() => callback(text), time)
        } else {
            clearTimeout(((window as any).timeout as number));
            (window as any).timeout = setTimeout(() => callback(text), time)
        }
        (window as any).lastCall = Date.now()
    }

    getFilteredCategories(text: string): void {
        const callback =  (text: string): void => {
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
        this.debounce(callback, text, 200);
    }
}
</script>
