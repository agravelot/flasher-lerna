import { useEffect, useState } from "react";
import "./App.css";
import { apiRepository } from "@flasher/common/src/useRepository";
import ArticleTable from "./components/ArticleTable";
import Drawer from "./components/Drawer";
import { Article } from "@flasher/models/src";
import { BrowserRouter as Router, Switch, Route } from "react-router-dom";
import AlbumList from "./pages/AlbumList";
import Dashboard from "./components/Dashboard";

function App() {
  const [articles, setArticles] = useState<Article[]>([]);

  useEffect(() => {
    const repo = apiRepository();

    repo.articles.list({ page: 1, perPage: 10 }).then((res) => {
      setArticles(res.data);
    });
  }, []);

  return (
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
              <div>
                <Dashboard albumsCount={1} />
              </div>
            </Route>
          </Switch>
        </Drawer>
      </div>
    </Router>
  );
}

export default App;
