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

// Setup Keycloak instance as needed
// Pass initialization options as required or leave blank to load from 'keycloak.json'
const keycloak = Keycloak({
  url: process.env.REACT_APP_KEYCLOAK_URL,
  realm: process.env.REACT_APP_KEYCLOAK_REALM ?? "",
  clientId: process.env.REACT_APP_KEYCLOAK_CLIENT_ID ?? "",
});

const eventLogger = (event: unknown, error: unknown) => {
  console.log("onKeycloakEvent", event, error);
};

const tokenLogger = (tokens: unknown) => {
  console.log("onKeycloakTokens", tokens);
};

function App() {
  return (
    <ReactKeycloakProvider
      authClient={keycloak}
      onEvent={eventLogger}
      onTokens={tokenLogger}
      initOptions={{ onLoad: "login-required" }}
    >
      <Router>
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
      </Router>
    </ReactKeycloakProvider>
  );
}

export default App;
