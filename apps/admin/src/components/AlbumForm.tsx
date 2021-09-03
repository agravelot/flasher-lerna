import { FunctionComponent } from "react";
import { Formik, Field, Form } from "formik";
import { Album } from "@flasher/models";
import { useKeycloak } from "@react-keycloak/web";
import { apiRepository } from "@flasher/common";

export type FormType = "edit" | "create";

export interface AlbumFormProps {
  album?: Album;
  type: FormType;
  onPostSubmit?: () => void;
  onPostDelete?: () => void;
}

interface AlbumForm {
  title: string;
  body: string;
  meta_description: string;
  published_at: string | null;
}

const AlbumForm: FunctionComponent<AlbumFormProps> = ({
  onPostSubmit,
  onPostDelete,
  album,
  type,
}: AlbumFormProps) => {
  const { initialized, keycloak } = useKeycloak();

  const onSubmit = async (values: AlbumForm) => {
    if (!initialized) {
      return;
    }

    const repo = apiRepository(keycloak);

    if (type === "edit") {
      if (!album) return;
      await repo.admin.albums.update(album.slug, values);
    } else {
      await repo.admin.albums.create(values);
    }

    onPostSubmit && onPostSubmit();
  };

  const deleteAlbum = async () => {
    if (type !== "edit" || !initialized || !album) {
      return;
    }

    const repo = apiRepository(keycloak);

    await repo.admin.albums.delete(album.slug);

    onPostDelete && onPostDelete();
  };

  return (
    <div className="m-8">
      <Formik
        initialValues={{
          title: album?.title ?? "",
          meta_description: album?.meta_description ?? "",
          body: album?.body ?? "",
          published_at: album?.published_at ?? null,
        }}
        enableReinitialize={true}
        onSubmit={onSubmit}
      >
        <Form>
          <div className="form-control">
            <label className="label">
              <span className="label-text">Titre</span>
            </label>
            <Field
              placeholder="Entrez un titre"
              className="input input-lg input-bordered"
              name="title"
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
              name="body"
              className="textarea h-24 textarea-bordered"
              placeholder="Mon contenu"
            ></Field>
          </div>

          <div className="form-control">
            <label className="label">
              <span className="label-text">Status</span>
            </label>
            <Field
              name="published_at"
              type="date"
              placeholder=""
              className="input input-bordered"
            />
          </div>

          <div className="m-8 justify-end flex">
            <button className="btn btn-primary m-2" type="submit">
              Envoyer
            </button>

            {type === "edit" && (
              <button
                className="btn btn-ghost m-2"
                onClick={() => deleteAlbum()}
              >
                Supprimer
              </button>
            )}
          </div>
        </Form>
      </Formik>
    </div>
  );
};

export default AlbumForm;
