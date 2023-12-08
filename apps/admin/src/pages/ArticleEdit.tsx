import { ArticlesApi, Configuration } from "@flasher/http-client/src";
import { FunctionComponent } from "react";
import ArticleForm from "../components/ArticleForm";
import { useAuthentication } from "../hooks/useAuthentication";
import { useQuery } from "@tanstack/react-query";
import { useParams } from "react-router-dom";

const ArticleEdit: FunctionComponent = () => {
  const { initialized, keycloak } = useAuthentication();

  const config = new Configuration({
    basePath: "",
  });
  const api = new ArticlesApi(config);

  const { slug } = useParams<{ slug: string }>();

  const { data: article, error } = useQuery({
    queryKey: ["article-show", slug],
    enabled: initialized && !!slug && !!keycloak,
    queryFn: () => {
      if (!initialized || !keycloak || !slug) {
        return;
      }
      return api.articleServiceGetBySlug(
        { slug },
        { headers: { Authorization: `Bearer ${keycloak.token}` } },
      );
    },
  });

  return (
    <div className="container mx-auto px-8">
      {error && <div>{String(error)}</div>}
      {article && (
        <ArticleForm
          isNew={false}
          article={{
            name: article.name,
            metaDescription: article.metaDescription,
            content: article.content,
            publishedAt: article.publishedAt,
          }}
          onSubmit={async (values) => {
            if (!initialized || !keycloak) {
              return;
            }

            await api.articleServiceUpdate(
              {
                body: { ...values, slug: article.slug },
              },
              { headers: { Authorization: `Bearer ${keycloak.token}` } },
            );
          }}
        />
      )}
    </div>
  );
};

export default ArticleEdit;
