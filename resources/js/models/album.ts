export default class Album {
    public id: number | null = null;
    public slug: string | null = null;
    public private: boolean;
    public medias: Array<object> = [];
}
