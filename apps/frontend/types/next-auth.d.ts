import { Role } from "../pages/api/auth/types";
import { DefaultUser } from "next-auth/core/types";
import { DefaultSession } from "next-auth";

declare module "next-auth" {
  /**
   * Returned by `useSession`, `getSession` and received as a prop on the `SessionProvider` React Context
   */
  interface Session {
    user?: DefaultSession["user"] & { role?: Role };
  }

  interface User extends DefaultUser {
    role?: Role;
  }
}
