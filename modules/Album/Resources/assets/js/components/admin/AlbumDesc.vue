<template>
    <section>
        <b-field label="Title"
                 :type="errors.title ? 'is-danger' : ''"
                 :message="errors.title ? errors.title[0] : null">
            <b-input type="text"
                     v-model="album.title"
                     maxlength="30">
            </b-input>
        </b-field>

        <quill-editor v-model="album.body" ref="myQuillEditor" :options="editorOption"></quill-editor>

        <b-field label="Enter some categories"
                 :type="errors.categories ? 'is-danger' : ''"
                 :message="errors.categories ? errors.categories[0] : null">
            <b-taginput
                    v-model="album.categories"
                    :data="filteredCategories"
                    autocomplete
                    :allow-new="false"
                    field="name"
                    placeholder="Add a category"
                    icon-pack="fas"
                    icon="tag"
                    @typing="getFilteredCategories">
            </b-taginput>
        </b-field>

        <b-field label="Enter some cosplayers"
                 :type="errors.cosplayers ? 'is-danger' : ''"
                 :message="errors.cosplayers ? errors.cosplayers[0] : null">
            <b-taginput
                    v-model="album.cosplayers"
                    :data="filteredCosplayers"
                    autocomplete
                    :allow-new="false"
                    field="name"
                    placeholder="Add a cosplayer"
                    icon-pack="fas"
                    icon="user-tag"
                    @typing="getFilteredCosplayers">
            </b-taginput>
        </b-field>

        <b-field label="Should this album be published?"
                 :type="errors.published_at ? 'is-danger' : ''"
                 :message="errors.published_at ? errors.published_at[0] : null">
            <div class="field">
                <b-switch v-model="album.published_at"
                          :true-value="album.published_at || new Date()"
                          :false-value=null>
                    {{ album.published_at ? 'Published' : 'Draft' }}
                </b-switch>
            </div>
        </b-field>

        <b-field label="Should it be accessible publicly?"
                 :type="errors.private ? 'is-danger' : ''"
                 :message="errors.private ? errors.private[0] : null">
            <div class="field">
                <b-switch v-model.numeric="album.private"
                          :true-value=1
                          :false-value=0>
                    {{ album.private ? 'Publicly' : 'Private' }}
                </b-switch>
            </div>
        </b-field>

        <button class="button is-primary">Update</button>
    </section>
</template>

<script lang="ts">
    import Component from 'vue-class-component';
    import 'quill/dist/quill.core.css'
    import 'quill/dist/quill.snow.css'
    import 'quill/dist/quill.bubble.css'
    import Album from '../../album'
    import Category from '../../category'
    import Cosplayer from '../../cosplayer'
    import { quillEditor } from 'vue-quill-editor'
    import VueBuefy from "../../../../../../../resources/js/buefy";

    @Component({
        name: "AlbumDesc",
        components: {
            quillEditor,
        },
    })
    export default class AlbumDesc extends VueBuefy {

        protected errors: object = {};
        protected album: Album = new Album();
        protected allowNew: boolean = false;
        protected allCategories: Array<Category> = [];
        protected allCosplayers: Array<Cosplayer> = [];
        protected filteredCategories: Array<object> = [];
        protected filteredCosplayers: Array<object> = [];
        protected editorOption: object = {
            placeholder: 'Enter your description...',
            theme: 'snow',
        };

        getFilteredCategories(text: string): void {
            this.filteredCategories = this.allCategories.filter((option : Category) => {
                return option.name
                    .toString()
                    .toLowerCase()
                    .indexOf(text.toLowerCase()) >= 0
            });
        }

        getFilteredCosplayers(text: string): void {
            this.filteredCosplayers = this.allCosplayers.filter((option : Cosplayer) => {
                return option.name
                    .toString()
                    .toLowerCase()
                    .indexOf(text.toLowerCase()) >= 0
            });
        }
    }
</script>
