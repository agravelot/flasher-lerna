import {User} from './user';

export  class Contact {
  id!: number;
  name!: string;
  email!: string;
  message!: string;
  user!: User | null;
}
