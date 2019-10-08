import Album from "../models/album";

export default class AlbumShareSocialMediaLinkBuilder {

    private album: Album;

    public constructor(album: Album) {
        this.album = album;
    }

    public getLink() : string {
        return this.album.links.view;
    }

    public getFacebookLink() : string {
        return `https://www.facebook.com/sharer/sharer.php?u=${this.album.links.view}`;
    }

    public getLinkedinLink() : string {
        return `https://www.linkedin.com/shareArticle?mini=true&url=${this.album.links.view}&title=${this.album.title}`;
    }

    public getTwitterLink() : string {
        return `https://twitter.com/share?text=${this.album.title}&url=${this.album.links.view}&hashtags=${this.getCategoriesHashTags().join(',')}`;
    }

    private getCategoriesHashTags() : Array<string> {
        return this.album.categories.map((category) =>  {
            return category.name;
        }).map((name) => {
            return name.replace(/(?:^|\s)\S/g, (word) => { return word.toUpperCase(); });
        }).map((name) => {
            return name.replace(/\s/g,'');
        });
    }
}
