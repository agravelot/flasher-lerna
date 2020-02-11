import User from "./user";

export default class Contact {
    id: number;
    name: string;
    email: string;
    message: string;
    user: User | null;
}
