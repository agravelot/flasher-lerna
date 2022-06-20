import { ArticlesApi, Configuration } from "@flasher/http-client";
import ArticleTable from "../components/ArticleTable";
import { FunctionComponent, useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { useKeycloak } from "@react-keycloak/web";

interface Article {
  id: string;
  name: string;
  slug: string;
  metaDescription: string;
  content: string;
}

const config = new Configuration({
  basePath: "",
});

const api = new ArticlesApi(config);

const ArticleList: FunctionComponent = () => {
  const { initialized, keycloak } = useKeycloak();
  const [articles, setArticles] = useState<Article[]>([]);
  const [selectedArticles, setSelectedArticles] = useState<Article[]>([]);

  useEffect(() => {
    const fetchArticles = async () => {
      if (!initialized) {
        return;
      }
      const res = await api.articleServiceIndex(
        { limit: 15 },
        { headers: { Authorization: `Bearer ${keycloak.token}` } }
      );
      if (!res.data) {
        throw new Error("No data");
      }
      setArticles(res.data);
    };
    fetchArticles();
  }, [initialized]);

  return (
    <div>
      <Link to="/articles/create" className="btn">
        Créer
      </Link>
      <ArticleTable
        articles={articles}
        onSelectChange={(articles: Article[]) => {
          setSelectedArticles(articles);
        }}
        selected={selectedArticles}
      />
    </div>
  );
};

export default ArticleList;
