import { FC } from "react";
import { Formik, Field, Form } from "formik";

export interface ArticleCreateProps {
  article?: ArticleForm;
  onSubmit: (article: ArticleForm) => void;
}

interface ArticleForm {
  slug?: string;
  name: string;
  content: string;
  metaDescription: string;
  publishedAt?: Date;
}

const ArticleForm: FC<ArticleCreateProps> = ({
  article,
  onSubmit,
}: ArticleCreateProps) => {
  return (
    <div>
      <Formik
        initialValues={{
          name: article?.name ?? "",
          metaDescription: article?.metaDescription ?? "",
          content: article?.content ?? "",
          publishedAt: article?.publishedAt ?? undefined,
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
