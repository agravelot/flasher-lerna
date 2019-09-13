import User from '../../../../../resources/js/user';

export default class Cosplayer {
    public name: string = '';
    public slug: string = '';
    public description: string | null;
    public avatar: File | null;
    public user: User;
}
