export default class Sharer {
    public id: number;
    public name: string;
    public email: string;

    public constructor(id: number, name: string, email: string) {
        this.id = id;
        this.name = name;
        this.email = email;
    }
}
