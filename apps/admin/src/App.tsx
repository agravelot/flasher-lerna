import { useEffect, useState } from "react";
import "./App.css";
import { apiRepository } from "@flasher/common/src/useRepository";
import ArticleTable from "./components/ArticleTable";
import Drawer from "./components/Drawer";
import { Article } from "@flasher/models/src";
import { BrowserRouter as Router, Switch, Route } from "react-router-dom";
import AlbumList from "./pages/AlbumList";
import Dashboard from "./components/Dashboard";
import Keycloak from "keycloak-js";
import { ReactKeycloakProvider } from "@react-keycloak/web";
import Home from "./pages/Home";

// Setup Keycloak instance as needed
// Pass initialization options as required or leave blank to load from 'keycloak.json'
const keycloak = Keycloak({
  url: "https://accounts.jkanda.fr/auth",
  realm: "jkanda",
  clientId: "flasher",
});

const eventLogger = (event: unknown, error: unknown) => {
  console.log("onKeycloakEvent", event, error);
};

const tokenLogger = (tokens: unknown) => {
  console.log("onKeycloakTokens", tokens);
};

function App() {
  const [articles, setArticles] = useState<Article[]>([]);

  useEffect(() => {
    const repo = apiRepository();

    repo.articles.list({ page: 1, perPage: 10 }).then((res) => {
      setArticles(res.data);
    });
  }, []);

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
              <Route path="/albums">
                <AlbumList />
              </Route>
              <Route path="/articles">
                <ArticleTable articles={articles} />
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
