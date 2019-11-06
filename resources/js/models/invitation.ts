export default class Invitation {
    id: number;
    email: string;
    message: string;
    created_at: Date;
    updated_at: Date;
    confirmed_at: Date|null;
}
