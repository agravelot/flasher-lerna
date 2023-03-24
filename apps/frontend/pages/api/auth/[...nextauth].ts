import NextAuth from "next-auth";
import KeycloakProvider from "next-auth/providers/keycloak";

export const authOptions = {
  // Configure one or more authentication providers
  providers: [
    KeycloakProvider({
      clientId: "frontend-next-auth",
      clientSecret: "4CSNSMuL062iKNOsRG9WopdWjSl4k91A",
      issuer: "https://accounts.jkanda.fr/auth/realms/jkanda",
    }),
    // ...add more providers here
  ],
};
export default NextAuth(authOptions);
