import "./App.css";
import Drawer from "./components/Drawer";
import { Route, Routes } from "react-router-dom";
import AlbumList from "./pages/AlbumList";
import Home from "./pages/Home";
import ArticleList from "./pages/ArticleList";
import ArticleCreate from "./pages/ArticleCreate";
import AlbumCreate from "./pages/AlbumCreate";
import AlbumEdit from "./pages/AlbumEdit";
import { setBaseUrl } from "@flasher/common";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { AuthenticationProvider } from "./hooks/useAuthentication";
import { StrictMode } from "react";
import ArticleEdit from "./pages/ArticleEdit";

const queryClient = new QueryClient();

// Setup Keycloak instance as needed
// Pass initialization options as required or leave blank to load from 'keycloak.json'
// const keycloak = Keycloak({
//   url: import.meta.env.VITE_KEYCLOAK_URL,
//   realm: import.meta.env.VITE_KEYCLOAK_REALM,
//   clientId: import.meta.env.VITE_KEYCLOAK_CLIENT_ID,
// });

function App() {
  void setBaseUrl(import.meta.env.VITE_API_URL);

  return (
    <AuthenticationProvider
      keycloakConfig={{
        url: import.meta.env.VITE_KEYCLOAK_URL,
        realm: import.meta.env.VITE_KEYCLOAK_REALM,
        clientId: import.meta.env.VITE_KEYCLOAK_CLIENT_ID,
      }}
      keycloakInitOptions={{
        enableLogging: process.env.NODE_ENV !== "production",
        onLoad: undefined,
      }}
      mustBeAuthenticated
    >
      <StrictMode>
        <QueryClientProvider client={queryClient}>
          <div className="h-screen">
            <Drawer>
              <Routes>
                <Route path="/albums/create" element={<AlbumCreate />} />
                <Route path="/albums/:slug" element={<AlbumEdit />} />
                <Route path="/albums" element={<AlbumList />} />
                <Route path="/articles">
                  <Route path="create" element={<ArticleCreate />} />
                  <Route path=":slug" element={<ArticleEdit />} />
                  <Route index element={<ArticleList />} />
                </Route>
                <Route path="/" element={<Home />} />
              </Routes>
            </Drawer>
          </div>
        </QueryClientProvider>
      </StrictMode>
    </AuthenticationProvider>
  );
}

export default App;
