import NextAuth, { AuthOptions } from "next-auth";
import KeycloakProvider from "next-auth/providers/keycloak";

export const authOptions = {
  providers: [
    KeycloakProvider({
      clientId: "frontend-next-auth",
      // TODO Rotate it
      clientSecret: "4CSNSMuL062iKNOsRG9WopdWjSl4k91A",
      issuer: "https://accounts.jkanda.fr/auth/realms/jkanda",
    }),
  ],
  debug: true,
  // events: {
  //
  // }
  callbacks: {},
} satisfies AuthOptions;

export default NextAuth(authOptions);
