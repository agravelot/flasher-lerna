import type { AuthOptions, DefaultSession, Session } from "next-auth";
import NextAuth from "next-auth";
import KeycloakProvider, {
  KeycloakProfile,
} from "next-auth/providers/keycloak";
import * as process from "node:process";
import { Awaitable } from "next-auth/core/types";
import { JWT } from "next-auth/jwt";
import { CustomJWT, CustomSession, Role } from "./types";

type CustomAuthOptions = AuthOptions & {
  callbacks: AuthOptions["callbacks"] &
    Partial<{
      session: (params: {
        session: Session;
        token: CustomJWT;
      }) =>
        | Awaitable<Session | DefaultSession>
        | NonNullable<AuthOptions["callbacks"]>["session"];
    }>;
};

type SessionParams = {
  session: Session;
  token: JWT & { access_token?: string };
};

const clientSecret = process.env.NEXT_AUTH_SECRET_KEYCLOAK;

if (!clientSecret) {
  throw new Error("Missing secret");
}

export const authOptions = {
  providers: [
    KeycloakProvider<KeycloakProfile>({
      clientId: "next-auth",
      clientSecret,
      issuer: "https://accounts.jkanda.fr/auth/realms/jkanda",
      profile(profile) {
        return {
          id: profile.sub,
          name: profile.name ?? profile.preferred_username,
          email: profile.email,
          image: profile.picture,
        };
      },
    }),
  ],

  callbacks: {
    async session({ session, token }: SessionParams): Promise<CustomSession> {
      let role: Role = "user";
      if (token.access_token) {
        // The token already been validated by jwt.decode, quick decode without validation
        // Find a way to implement it directly on jwt.decode to avoid double token processing.
        const payload: CustomJWT = JSON.parse(
          atob(token.access_token.split(".")[1]),
        );
        if (payload.realm_access?.roles?.includes("admin")) {
          role = "admin";
        }
      }

      return {
        ...session,
        user: session.user ? { ...session.user, role } : undefined,
      };
    },
    async jwt({ token, account }) {
      if (account?.access_token) {
        token.access_token = account.access_token;
      }
      return token;
    },
  },
} satisfies CustomAuthOptions;

export default NextAuth(authOptions);
