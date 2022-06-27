import { ArticlesApi, Configuration } from "@flasher/http-client/src";
import { useKeycloak } from "@react-keycloak/web";
import { FunctionComponent } from "react";
import { useHistory } from "react-router-dom";
import ArticleForm from "../components/ArticleForm";

const ArticleCreate: FunctionComponent = () => {
  const history = useHistory();
  const { initialized } = useKeycloak();

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
