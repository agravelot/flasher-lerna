import { useKeycloak } from "@react-keycloak/ssr";
import { useKeycloakHookResults } from "@react-keycloak/ssr/lib/useKeycloak";
import { KeycloakInstance, KeycloakTokenParsed } from "keycloak-js";
import { configuration } from "~/utils/configuration";

export type ParsedToken = KeycloakTokenParsed & {
  email?: string;
  preferred_username?: string;
  groups: string[];
};

type Authentication = useKeycloakHookResults<KeycloakInstance> & {
  keycloak: KeycloakInstance;
  login: () => void;
  register: () => void;
  administration: () => string;
  parsedToken: ParsedToken | undefined;
  isAdmin: boolean;
};

const useAuthentication = (): Authentication => {
  const { initialized, keycloak } = useKeycloak<KeycloakInstance>();

  if (!keycloak) {
    throw new Error("Keycloak is not defined");
  }

  // Required when keycloak is not initialized
  const login = () => keycloak.login();
  const register = () => keycloak.register();

  const administration = () => configuration.administration;

  const parsedToken = keycloak.tokenParsed as ParsedToken | undefined;

  const isAdmin: boolean = parsedToken?.groups.includes("admin") ?? false;

  return {
    initialized,
    keycloak,
    login,
    register,
    administration,
    parsedToken,
    isAdmin,
  };
};

export default useAuthentication;
