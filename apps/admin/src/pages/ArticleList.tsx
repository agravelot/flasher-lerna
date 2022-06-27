import { ArticlesApi, Configuration } from "@flasher/http-client";
import ArticleTable from "../components/ArticleTable";
import { FunctionComponent, useState } from "react";
import { Link } from "react-router-dom";
import { useKeycloak } from "@react-keycloak/web";
import { useQuery } from "react-query";

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
  const { initialized, keycloak } = useKeycloak();
  const [selectedArticles, setSelectedArticles] = useState<Article[]>([]);

  const {
    data: articles,
    error,
    isFetching,
    status,
  } = useQuery(
    "article-index",
    () =>
      api
        .articleServiceIndex(
          { limit: 15 },
          { headers: { Authorization: `Bearer ${keycloak.token}` } }
        )
        .then((r) => r.data),
    {
      enabled: initialized,
      // getNextPageParam: (lastPage, pages) => lastPage.nextCursor,
    }
  );

  return (
    <div>
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
    </div>
  );
};

export default ArticleList;
