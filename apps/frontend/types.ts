import { DefaultSession } from "next-auth";
import { JWT } from "next-auth/jwt";

export type CustomJWT = JWT & {
  client_roles?: string[];
  realm_access?: { roles?: string[] };
  aud?: string;
};

export type Role = "admin" | "user";

export type CustomSession = DefaultSession & {
  user?: DefaultSession["user"] & { role?: Role };
};
