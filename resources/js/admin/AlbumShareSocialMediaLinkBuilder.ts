import Album from "../models/album";

export default class AlbumShareSocialMediaLinkBuilder {

    private album: Album;

    constructor(album: Album) {
        this.album = album;
    }

    getLink() : string {
        return this.album.links.view;
    }

    getFacebookLink() : string {
        return `https://www.facebook.com/sharer/sharer.php?u=${this.album.links.view}`;
    }

    getLinkedinLink() : string {
        return `https://www.linkedin.com/shareArticle?mini=true&url=${this.album.links.view}&title=${this.album.title}&summary=&source=`;
    }

    getTwitterLink() : string {
        return `https://twitter.com/home?status=${this.album.links.view}`;
    }
}
