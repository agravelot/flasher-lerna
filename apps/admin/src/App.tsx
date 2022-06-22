import "./App.css";
import Drawer from "./components/Drawer";
import { BrowserRouter as Router, Switch, Route } from "react-router-dom";
import AlbumList from "./pages/AlbumList";
import Keycloak from "keycloak-js";
import { ReactKeycloakProvider } from "@react-keycloak/web";
import Home from "./pages/Home";
import ArticleList from "./pages/ArticleList";
import ArticleCreate from "./pages/ArticleCreate";
import AlbumCreate from "./pages/AlbumCreate";
import AlbumEdit from "./pages/AlbumEdit";
import { setBaseUrl } from "@flasher/common/src";
import { QueryClient, QueryClientProvider, useQuery } from 'react-query'
2 
3 const queryClient = new QueryClient();

// Setup Keycloak instance as needed
// Pass initialization options as required or leave blank to load from 'keycloak.json'
const keycloak = Keycloak({
  url: import.meta.env.VITE_KEYCLOAK_URL,
  realm: import.meta.env.VITE_KEYCLOAK_REALM,
  clientId: import.meta.env.VITE_KEYCLOAK_CLIENT_ID,
});

const eventLogger = (event: unknown, error: unknown) => {
  console.log("onKeycloakEvent", event, error);
};

const tokenLogger = (tokens: unknown) => {
  console.log("onKeycloakTokens", tokens);
};

function App() {
  void setBaseUrl(import.meta.env.VITE_API_URL);

  return (
    <ReactKeycloakProvider
      authClient={keycloak}
      onEvent={eventLogger}
      onTokens={tokenLogger}
      initOptions={{ onLoad: "login-required" }}
    >
      <Router>
        <QueryClientProvider client={queryClient}>
          <div className="h-screen">
            <Drawer>
              <Switch>
                <Route exact path="/albums/create">
                  <AlbumCreate />
                </Route>
                <Route path="/albums/:slug">
                  <AlbumEdit />
                </Route>
                <Route path="/albums">
                  <AlbumList />
                </Route>
                <Route exact path="/articles/create">
                  <ArticleCreate />
                </Route>
                <Route path="/articles">
                  <ArticleList />
                </Route>
                <Route path="/">
                  <Home />
                </Route>
              </Switch>
            </Drawer>
          </div>
        </QueryClientProvider>
      </Router>
    </ReactKeycloakProvider>
  );
}

export default App;
