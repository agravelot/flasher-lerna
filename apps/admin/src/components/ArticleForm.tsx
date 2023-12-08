import { FunctionComponent, useEffect } from "react";
import { useForm } from "react-hook-form";
import { format } from "date-fns";

type Inputs = {
  name: string;
  content: string;
  metaDescription: string;
  publishedAt?: Date;
};

export type ArticleCreateProps =
  | {
      isNew: true;
      article: Inputs;
      onSubmit: (article: Inputs) => void;
    }
  | {
      isNew: false;
      article: Partial<Inputs>;
      onSubmit: (article: Inputs) => void;
    };

// TODO Add zod validation
// TODO USe react hook form

const ArticleForm: FunctionComponent<ArticleCreateProps> = ({
  article,
  onSubmit,
}: ArticleCreateProps) => {
  const {
    getValues,
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

  useEffect(() => {
    console.log({ values: getValues(), errors, isValid });
  }, [errors, isValid]);

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
        </div>

        {/*<div className="form-control">*/}
        {/*  <label className="label">*/}
        {/*    <span className="label-text">Status</span>*/}
        {/*  </label>*/}
        {/*  <input*/}
        {/*      name="published_at"*/}
        {/*      type="text"*/}
        {/*      placeholder=""*/}
        {/*      className="input input-bordered"*/}
        {/*  />*/}
        {/*</div>*/}

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
