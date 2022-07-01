import Keycloak,{
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

type Authentication = {
  keycloak: Keycloak | null;
  parsedToken: ParsedToken | undefined;
  isAdmin: boolean;
  isAuthenticated: boolean;
  initialized: boolean;
};


export interface AuthenticationStateProviderProps {
  keycloakConfig: KeycloakConfig;
  keycloakInitOptions?: KeycloakInitOptions;
  children: JSX.Element;
  mustBeAuthenticated?: boolean;
  loadingComponent?: JSX.Element;
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

const LOCAL_STORAGE_ACCESS_TOKEN_KEY = "at";
const LOCAL_STORAGE_REFRESH_TOKEN_KEY = "rt";

export const AuthenticationProvider = ({
  children,
  keycloakConfig,
  keycloakInitOptions,
  mustBeAuthenticated,
  loadingComponent
}: AuthenticationStateProviderProps): JSX.Element => {
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [initialized, setInitialized] = useState(false);
  const keycloakInstance: Keycloak | null = useMemo(() => {
    // eslint-disable-next-line @typescript-eslint/no-var-requires
    return new Keycloak(keycloakConfig);
  }, [keycloakConfig]);

  useEffect(() => {
    console.log('init keycloak')
    if (!keycloakInstance) {
      console.log('keycloakInstance is null')
      return;
    }
   keycloakInstance
      .init({
        onLoad: undefined,
        checkLoginIframe: false,
        token: getToken(LOCAL_STORAGE_ACCESS_TOKEN_KEY) ?? undefined,
        refreshToken: getToken(LOCAL_STORAGE_REFRESH_TOKEN_KEY) ?? undefined,
        enableLogging: true,
        ...keycloakInitOptions,
      })
      .then((authentication) => {
        setInitialized(true);
        setIsAuthenticated(authentication);
      })
      .catch((error) => {
        console.error("unable init keycloak client");
        console.error(error);
        deleteToken(LOCAL_STORAGE_ACCESS_TOKEN_KEY);
        deleteToken(LOCAL_STORAGE_REFRESH_TOKEN_KEY);
      });

    keycloakInstance.onTokenExpired = () => {
      keycloakInstance.updateToken(30);
    };
  }, [keycloakInstance, keycloakInitOptions]);

  useEffect(() => {
    if (!initialized) {
      return
    }
    if (
      isAuthenticated &&
      keycloakInstance.token &&
      keycloakInstance.refreshToken
    ) {
      saveToken(LOCAL_STORAGE_ACCESS_TOKEN_KEY, keycloakInstance.token);
      saveToken(LOCAL_STORAGE_REFRESH_TOKEN_KEY, keycloakInstance.refreshToken);
    } else if (mustBeAuthenticated) {
      console.log('force login')
      keycloakInstance.login()
    }
  }, [initialized, isAuthenticated, keycloakInstance, mustBeAuthenticated]);


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
      {!initialized && loadingComponent ? loadingComponent : children}
    </AuthContext.Provider>
  );
};

const saveToken = (key: string, token: string): void => {
  if (typeof window === "undefined") {
    return;
  }
  localStorage.setItem(key, token);
};

const getToken = (key: string): string | undefined => {
  if (typeof window === "undefined") {
    return;
  }
  return localStorage.getItem(key) ?? undefined;
};

const deleteToken = (key: string): void => {
  if (typeof window === "undefined") {
    return;
  }
  localStorage.removeItem(key);
};
