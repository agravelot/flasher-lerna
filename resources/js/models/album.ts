import User from './user';
import Category from './category';
import Cosplayer from "./cosplayer";

interface LinksInterface {
    view: string;
    download?: string;
    edit?: string;
}

export default class Album {
    public id!: number;
    public slug!: string;
    public title!: string;
    public body?: string | null;
    public published_at!: Date | null;
    public private!: boolean;
    public medias: Array<object> = [];
    public user!: User;
    public cosplayers?: Cosplayer[];
    public created_at!: Date;
    public updated_at?: Date;
    public links!: LinksInterface;
    public categories?: Array<Category>;
}
