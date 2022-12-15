import { ArticlesApi, Configuration } from "@flasher/http-client";
import ArticleTable from "../components/ArticleTable";
import { FunctionComponent, useState } from "react";
import { Link } from "react-router-dom";
import { useQuery } from "react-query";
import { useAuthentication } from "../hooks/useAuthentication";

interface Article {
  id: string;
  name: string;
  slug: string;
  metaDescription: string;
  content: string;
  publishedAt?: Date;
}

const config = new Configuration({
  basePath: "",
});

const api = new ArticlesApi(config);

const ArticleList: FunctionComponent = () => {
  const { initialized, keycloak } = useAuthentication();
  const [selectedArticles, setSelectedArticles] = useState<Article[]>([]);

  const {
    data: articles,
    error,
    isFetching,
    status,
  } = useQuery("article-index", () => {
    if (!initialized || !keycloak) {
      return;
    }
    api
      .articleServiceIndex(
        { limit: 15 },
        { headers: { Authorization: `Bearer ${keycloak.token}` } }
      )
      .then((r) => r.data),
      {
        enabled: initialized,
        // getNextPageParam: (lastPage, pages) => lastPage.nextCursor,
      };
  });

  return (
    <>
      <Link to="/articles/create" className="btn">
        Créer
      </Link>
      {error && <div>{String(error)}</div>}
      {articles && (
        <ArticleTable
          articles={articles}
          onSelectChange={(articles: Article[]) => {
            setSelectedArticles(articles);
          }}
          selected={selectedArticles}
        />
      )}
    </>
  );
};

export default ArticleList;
