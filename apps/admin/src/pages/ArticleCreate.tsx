import { FunctionComponent } from "react";
import { useHistory } from "react-router-dom";
import ArticleForm from "../components/ArticleForm";

const ArticleCreate: FunctionComponent = () => {
  const history = useHistory();

  return (
    <div>
      <ArticleForm
        onPostSubmit={() => {
          history.push("/articles");
        }}
      />
    </div>
  );
};

export default ArticleCreate;
