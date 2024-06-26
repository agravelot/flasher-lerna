import "@mdxeditor/editor/style.css";
import { FunctionComponent } from "react";
import { useForm } from "react-hook-form";
import { format } from "date-fns";
import { MDXEditor, type MDXEditorMethods } from "@mdxeditor/editor";
import { headingsPlugin } from "@mdxeditor/editor";
import React from "react";

type Inputs = {
  name: string;
  content: string;
  metaDescription: string;
  publishedAt?: Date;
};

export type ArticleCreateProps = {
  onSubmit: (article: Inputs) => void;
} & (
  | {
      isNew: true;
      article: Inputs;
    }
  | {
      isNew: false;
      article: Partial<Inputs>;
    }
);

// TODO Add zod validation
// TODO USe react hook form

const ArticleForm: FunctionComponent<ArticleCreateProps> = ({
  article,
  onSubmit,
}: ArticleCreateProps) => {
  const ref = React.useRef<MDXEditorMethods>(null);

  const {
    formState: { errors, isValid },
    handleSubmit,
    register,
  } = useForm({
    defaultValues: {
      name: article?.name ?? "",
      metaDescription: article?.metaDescription ?? "",
      content: article?.content ?? "",
      // publishedAt: article?.publishedAt?.toISOString() ?? undefined,
      publishedAt: article?.publishedAt
        ? format(article.publishedAt, "yyyy-MM-dd'T'HH:mm")
        : undefined,
    },
  });

  return (
    <div>
      <form
        onSubmit={handleSubmit((data) => {
          return onSubmit({
            ...data,
            publishedAt: data.publishedAt
              ? new Date(data.publishedAt)
              : undefined,
          });
        })}
      >
        <div className="form-control">
          <label className="label">
            <span className="label-text">Titre</span>
          </label>
          <input
            placeholder="Entrez un titre"
            className="input input-bordered input-lg"
            type="text"
            {...register("name", { required: true })}
          />
          {errors.name && (
            <div className="text-error">Le titre est requis.</div>
          )}
        </div>

        <div className="form-control">
          <label className="label">
            <span className="label-text">Meta-description</span>
          </label>
          <textarea
            placeholder="Une description en moins de x caractères."
            className="textarea textarea-bordered h-24"
            {...register("metaDescription", { required: true })}
          />
          {errors.metaDescription && (
            <div className="text-error">La meta-description est requise.</div>
          )}
        </div>

        <div className="form-control">
          <label className="label">
            <span className="label-text">Contenu</span>
          </label>
          <textarea
            {...register("content", { required: true })}
            className="textarea textarea-bordered h-24"
            placeholder="Mon contenu"
          ></textarea>
          {errors.content && (
            <div className="text-error">Le contenu est requis.</div>
          )}

          <MDXEditor
            ref={ref}
            contentEditableClassName="prose"
            markdown="hello world"
            onChange={console.log}
            plugins={[headingsPlugin()]}
          />
        </div>

        <div className="form-control">
          <label className="label">
            <span className="label-text">Date de publication</span>
          </label>
          <input
            type="datetime-local"
            placeholder=""
            className="input input-bordered"
            {...register("publishedAt", { required: true })}
          />
        </div>

        <button
          className="btn btn-primary mt-8"
          type="submit"
          disabled={!isValid}
        >
          Envoyer
        </button>
      </form>
    </div>
  );
};

export default ArticleForm;
