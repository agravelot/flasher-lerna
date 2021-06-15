import { apiRepository } from "@flasher/common";
import { Article } from "@flasher/models";
import ArticleTable from "../components/ArticleTable";
import { FunctionComponent, useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { useKeycloak } from "@react-keycloak/web";

const ArticleList: FunctionComponent = () => {
  const { initialized, keycloak } = useKeycloak();
  const [articles, setArticles] = useState<Article[]>([]);
  const [selectedArticles, setSelectedArticles] = useState<Article[]>([]);

  useEffect(() => {
    if (!initialized) {
      return;
    }
    const repo = apiRepository(keycloak);

    repo.articles.list({ page: 1, perPage: 10 }).then((res) => {
      setArticles(res.data);
    });
  }, [initialized]);

  console.log(selectedArticles);

  return (
    <div>
      <Link to="/articles/create" className="btn">
        Créer
      </Link>
      <ArticleTable
        articles={articles}
        onSelectChange={(articles: Article[]) => {
          console.log({ articles });

          setSelectedArticles(articles);
        }}
        selected={selectedArticles}
      />
    </div>
  );
};

export default ArticleList;
