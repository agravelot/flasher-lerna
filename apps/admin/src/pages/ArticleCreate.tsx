import { ArticlesApi, Configuration } from "@flasher/http-client/src";
import { FunctionComponent } from "react";
import { useHistory } from "react-router-dom";
import ArticleForm from "../components/ArticleForm";
import { useAuthentication } from "../hooks/useAuthentication";

const ArticleCreate: FunctionComponent = () => {
  const history = useHistory();
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

          history.push("/articles");
        }}
      />
    </div>
  );
};

export default ArticleCreate;
