import { FunctionComponent } from "react";
import { Formik, Field, Form } from "formik";
import { Album } from "@flasher/models";
import { useKeycloak } from "@react-keycloak/web";
import { apiRepository } from "@flasher/common";

export type FormType = "edit" | "create";

export interface AlbumFormProps {
  album?: Album;
  onPostSubmit?: () => void;
  type: FormType;
}

interface AlbumForm {
  title: string;
  body: string;
  meta_description: string;
  published_at: string | null;
}

const AlbumForm: FunctionComponent<AlbumFormProps> = ({
  onPostSubmit,
  album,
  type,
}: AlbumFormProps) => {
  const { initialized, keycloak } = useKeycloak();

  const onSubmit = async (values: AlbumForm) => {
    console.log({ values });

    if (!initialized) {
      return;
    }

    const repo = apiRepository(keycloak);

    if (type === "edit") {
      if (!album) return;
      const res = await repo.admin.albums.update(album.slug, values);
      console.log(res);
    } else {
      const res = await repo.admin.albums.create(values);
      console.log(res);
    }

    onPostSubmit && onPostSubmit();
  };

  return (
    <div>
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

          <button className="btn btn-primary" type="submit">
            Submit
          </button>
        </Form>
      </Formik>
    </div>
  );
};

export default AlbumForm;
