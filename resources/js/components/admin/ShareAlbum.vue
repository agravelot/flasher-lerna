<template>
  <div v-if="album && album.links">
    <b-button
      :href="getFacebookLink()"
      tag="a"
      target="_blank"
      icon-pack="fab"
      icon-right="facebook"
    />
    <b-button
      :href="getTwitterLink()"
      tag="a"
      target="_blank"
      icon-pack="fab"
      icon-right="twitter"
    />
    <b-button
      @click="addToClipboard(getLink())"
      icon-right="link"
    />
  </div>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import Buefy from '../../admin/Buefy.vue';
import Album from '../../models/album';
import { Prop } from 'vue-property-decorator';

@Component({
    name: 'share-album',
})
export default class ShareAlbum extends Buefy {
    @Prop()
    protected album: Album;

    addToClipboard(value: any): void {
        (navigator as any).permissions.query({ name: 'clipboard-write' }).then(result => {
            if (result.state == 'granted' || result.state == 'prompt') {
                navigator.clipboard.writeText(value).then(
                    () => {
                        this.showSuccess('Link copied');
                    },
                    () => {
                        this.showError('Something went wrong');
                    }
                );
            }
        });
    }

    public getLink(): string {
        return this.album.links.view;
    }

    public getFacebookLink(): string {
        return `https://www.facebook.com/sharer/sharer.php?u=${this.album.links.view}`;
    }

    public getLinkedinLink(): string {
        return `https://www.linkedin.com/shareArticle?mini=true&url=${
            this.album.links.view
        }&title=${this.album.title}`;
    }

    public getTwitterLink(): string {
        return `https://twitter.com/share?text=${this.album.title}&url=${
            this.album.links.view
        }&hashtags=${this.getCategoriesHashTags().join(',')}`;
    }

    private getCategoriesHashTags(): Array<string> {
        return (
            this.album.categories
                .map(category => {
                    return category.name;
                })
                // each words uppercase
                .map(name => {
                    return name.replace(/(?:^|\s)\S/g, word => {
                        return word.toUpperCase();
                    });
                })
                // remove whitespaces
                .map(name => {
                    return name.replace(/\s/g, '');
                })
        );
    }
}
</script>
