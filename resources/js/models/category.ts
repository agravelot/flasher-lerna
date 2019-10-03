export default class Category {
    public id: number;
    public slug: string;
    public name: string;
    public description: string | null;
    public created_at: Date;
    public updated_at: Date;
    public cover: object;
}
