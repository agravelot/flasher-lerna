import type { AuthOptions, DefaultSession, Session } from "next-auth";
import NextAuth from "next-auth";
import KeycloakProvider, {
  KeycloakProfile,
} from "next-auth/providers/keycloak";
import * as process from "process";
import { Awaitable } from "next-auth/core/types";
import { JWT } from "next-auth/jwt";
import { CustomJWT, Role } from "../../../types";

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

const clientSecret = process.env.CLIENT_SECRET_KEYCLOAK ?? "";
const secret = process.env.NEXTAUTH_SECRET ?? "";

// if (!clientSecret) {
//   throw new Error("Missing secret");
// }

console.log({ secret, clientSecret });

export const authOptions = {
  secret,
  debug: true,
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
    async session({ session, token }: SessionParams) {
      let role: Role = "user";
      if (token.access_token) {
        // TODO Use import { getToken } from "next-auth/jwt"
        // https://next-auth.js.org/tutorials/securing-pages-and-api-routes#using-gettoken
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
        access_token: token.access_token,
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
