import User from "./user";

export default class Cosplayer {
    public id: number;
    public name: string;
    public slug: string;
    public description: string | null;
    public avatar: File | null;
    public user: User | null;
    public created_at: Date;
    public updated_at: Date;
}
