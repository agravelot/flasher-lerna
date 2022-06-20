import { FunctionComponent } from "react";
import { Formik, Field, Form } from "formik";
import { Article } from "@flasher/models";
import { useKeycloak } from "@react-keycloak/web";
import { apiRepository } from "@flasher/common/src";

export interface ArticleCreateProps {
  article?: Article;
  onPostSubmit?: () => void;
}

interface ArticleForm {
  name: string;
  content: string;
  metaDescription: string;
}

const ArticleForm: FunctionComponent<ArticleCreateProps> = ({
  onPostSubmit,
  article,
}: ArticleCreateProps) => {
  const { initialized, keycloak } = useKeycloak();

  const onSubmit = async (values: ArticleForm) => {
    console.log({ values });

    if (!initialized) {
      return;
    }

    const repo = apiRepository(keycloak);
    const res = await repo.articles.create(values);
    console.log(res);

    onPostSubmit && onPostSubmit();
  };

  return (
    <div>
      <Formik
        initialValues={{
          name: article?.name ?? "",
          metaDescription: article?.meta_description ?? "",
          content: article?.content ?? "",
          publishedAt: article?.published_at ?? null,
        }}
        onSubmit={onSubmit}
      >
        <Form>
          <div className="form-control">
            <label className="label">
              <span className="label-text">Titre</span>
            </label>
            <Field
              placeholder="Entrez un titre"
              className="input input-bordered input-lg"
              name="name"
              type="text"
            />
          </div>

          <div className="form-control">
            <label className="label">
              <span className="label-text">Description</span>
            </label>
            <Field
              name="meta_description"
              type="text"
              placeholder="Une description en moins de x caractères."
              className="input input-bordered"
            />
          </div>

          <div className="form-control">
            <label className="label">
              <span className="label-text">Contenu</span>
            </label>
            <Field
              as="textarea"
              name="content"
              className="textarea textarea-bordered h-24"
              placeholder="Mon contenu"
            ></Field>
          </div>

          <div className="form-control">
            <label className="label">
              <span className="label-text">Status</span>
            </label>
            <Field
              name="published_at"
              type="text"
              placeholder=""
              className="input input-bordered"
            />
          </div>

          <button className="btn btn-primary" type="submit">
            Submit
          </button>
        </Form>
      </Formik>
    </div>
  );
};

export default ArticleForm;
