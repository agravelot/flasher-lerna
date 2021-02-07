import User from "~/models/user";

export default class Contact {
  id!: number;
  name!: string;
  email!: string;
  message!: string;
  user!: User | null;
}
