import User from './user';

export default class Album {
    public id: number;
    public slug: string;
    public title: string;
    public body: string | null;
    public published_at: Date | null;
    public private: boolean;
    public medias: Array<object> = [];
    public user: User;
    public created_at: Date;
    public updated_at: Date;
}
