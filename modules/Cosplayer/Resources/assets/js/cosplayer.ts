import User from '../../../../User/Resources/assets/js/user';

export default class Cosplayer {
    public name: string = '';
    public slug: string = '';
    public description: string | null;
    public avatar: File | null;
    public user: User;
}
