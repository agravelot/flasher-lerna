import Keycloak, {
  KeycloakConfig,
  KeycloakInitOptions,
  KeycloakTokenParsed,
} from "keycloak-js";
import {
  createContext,
  useContext,
  useEffect,
  useMemo,
  useRef,
  useState,
} from "react";

export type ParsedToken = KeycloakTokenParsed & {
  email?: string;
  preferred_username?: string;
  groups: string[];
};

type Authentication =
  | {
      keycloak: Keycloak | null;
      parsedToken: ParsedToken | undefined;
      isAdmin: boolean;
      isAuthenticated: boolean;
      initialized: false;
    }
  | {
      keycloak: Keycloak;
      parsedToken: ParsedToken;
      isAdmin: boolean;
      isAuthenticated: boolean;
      initialized: true;
    };

export const isServer = (): boolean => typeof window === "undefined";

export interface AuthenticationStateProviderProps {
  keycloakConfig: KeycloakConfig;
  keycloakInitOptions?: KeycloakInitOptions;
  children: JSX.Element;
}

export interface AuthenticationContextProps {
  isAuthenticated: boolean;
  initialized: boolean;
  keycloak: Keycloak | null;
  parsedToken: ParsedToken | undefined;
  isAdmin: boolean;
}

export const useAuthentication = (): Authentication => {
  const context = useContext(AuthContext);

  if (!context) {
    throw new Error("Keycloak is not defined");
  }

  return context;
};

const AuthContext = createContext<AuthenticationContextProps>({
  isAuthenticated: false,
  initialized: false,
  keycloak: null,
  parsedToken: undefined,
  isAdmin: false,
});

export const AuthenticationProvider = ({
  children,
  keycloakConfig,
  keycloakInitOptions,
}: AuthenticationStateProviderProps): JSX.Element => {
  const keycloakRef = useRef<Promise<void>>();
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [initialized, setInitialized] = useState(false);
  const keycloakInstance: Keycloak | null = useMemo(() => {
    return new Keycloak(keycloakConfig);
  }, [keycloakConfig]);

  useEffect(() => {
    if (!keycloakInstance) {
      return;
    }
    keycloakRef.current = keycloakInstance
      .init({
        // onLoad: undefined,
        // checkLoginIframe: false,
        onLoad: "check-sso",
        enableLogging: true,
        silentCheckSsoRedirectUri: `${window.location.origin}/silent-check-sso.html`,
        ...keycloakInitOptions,
      })
      .then((authentication) => {
        setIsAuthenticated(authentication);
      })
      .catch((error) => {
        console.error(error);
      })
      .finally(() => {
        setInitialized(true);
      });
  }, [keycloakInstance, keycloakInitOptions]);

  const parsedToken = keycloakInstance?.tokenParsed as ParsedToken | undefined;

  return (
    <AuthContext.Provider
      value={{
        isAuthenticated,
        initialized,
        keycloak: keycloakInstance,
        parsedToken,
        isAdmin: parsedToken?.groups.includes("admin") ?? false,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
};
