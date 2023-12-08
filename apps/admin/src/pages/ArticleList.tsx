import { ArticlesApi, Configuration } from "@flasher/http-client";
import ArticleTable from "../components/ArticleTable";
import { FunctionComponent, useState } from "react";
import { Link } from "react-router-dom";
import { useQuery } from "@tanstack/react-query";
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

  // const { data: article, error } = useQuery({
  //   queryKey: ["article-show", slug],
  //   enabled: initialized && !!slug && !!keycloak,
  //   queryFn: () => {
  //     if (!initialized || !keycloak || !slug) {
  //       return;
  //     }
  //     return api.articleServiceGetBySlug(
  //         { slug },
  //         { headers: { Authorization: `Bearer ${keycloak.token}` } },
  //     );
  //   },
  // });

  const { data: articles, error } = useQuery({
    queryKey: ["article-index"],
    enabled: initialized && !!keycloak,
    queryFn: () => {
      if (!initialized || !keycloak) {
        return;
      }
      return api
        .articleServiceIndex(
          { limit: 15 },
          { headers: { Authorization: `Bearer ${keycloak.token}` } },
        )
        .then((r) => r.data);
    },
  });

  return (
    <>
      <Link to="create" className="btn">
        Créer
      </Link>
      {error && <div>{String(error)}</div>}
      <ArticleTable
        articles={articles ?? []}
        onSelectChange={(articles: Article[]) => {
          setSelectedArticles(articles);
        }}
        selected={selectedArticles}
      />
    </>
  );
};

export default ArticleList;
