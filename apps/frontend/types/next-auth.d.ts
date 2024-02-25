import { CustomSession, Role } from "../pages/api/auth/types";
import { DefaultUser } from "next-auth/src/core/types";

declare module "next-auth" {
  /**
   * Returned by `useSession`, `getSession` and received as a prop on the `SessionProvider` React Context
   */
  interface Session extends CustomSession {}

  interface User extends DefaultUser {
    role?: Role;
  }
}
