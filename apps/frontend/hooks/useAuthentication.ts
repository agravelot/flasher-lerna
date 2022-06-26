import { useKeycloak } from "@react-keycloak/web";
import { KeycloakInstance, KeycloakTokenParsed } from "keycloak-js";

export type ParsedToken = KeycloakTokenParsed & {
  email?: string;
  preferred_username?: string;
  groups: string[];
};

type Authentication = {
  keycloak: KeycloakInstance;
  login: () => void;
  register: () => void;
  parsedToken: ParsedToken | undefined;
  isAdmin: boolean;
  initialized: boolean;
};

export const useAuthentication = (): Authentication => {
  const { initialized, keycloak } = useKeycloak();

  if (!keycloak) {
    throw new Error("Keycloak is not defined");
  }

  // Required when keycloak is not initialized
  const login = () => keycloak.login();
  const register = () => keycloak.register();

  const parsedToken = keycloak.tokenParsed as ParsedToken | undefined;

  const isAdmin: boolean = parsedToken?.groups.includes("admin") ?? false;

  return {
    initialized,
    keycloak,
    login,
    register,
    parsedToken,
    isAdmin,
  };
};
