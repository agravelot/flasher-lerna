import { ArticlesApi, Configuration } from "@flasher/http-client/src";
import { FC } from "react";
import { useNavigate } from "react-router-dom";
import ArticleForm from "../components/ArticleForm";
import { useAuthentication } from "../hooks/useAuthentication";

const ArticleCreate: FC = () => {
  const navigate = useNavigate();
  const { initialized } = useAuthentication();

  const config = new Configuration({
    basePath: "",
  });
  const api = new ArticlesApi(config);

  return (
    <div>
      <ArticleForm
        onSubmit={async (values: ArticleForm) => {
          if (!initialized) {
            return;
          }

          await api.articleServiceCreate({
            body: values,
          });

          navigate("/articles");
        }}
      />
    </div>
  );
};

export default ArticleCreate;
