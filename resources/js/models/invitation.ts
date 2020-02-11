export default class Invitation {
    id: number;
    email: string;
    message: string;
    cosplayer_id: number;
    created_at: Date;
    updated_at: Date;
    confirmed_at: Date | null;
    expired: boolean;
}
