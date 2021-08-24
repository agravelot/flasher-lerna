import { useKeycloak } from "@react-keycloak/web";
import { FunctionComponent, useEffect, useState } from "react";
import { useHistory, useParams } from "react-router-dom";
import AlbumForm from "../components/AlbumForm";
import { apiRepository } from "@flasher/common";
import { Album } from "@flasher/models";

const ArticleCreate: FunctionComponent = () => {
  const history = useHistory();
  const { initialized, keycloak } = useKeycloak();

  const [album, setAlbum] = useState<Album>();

  const { slug } = useParams<{ slug: string }>();

  useEffect(() => {
    document.title = "Create Album";

    if (!initialized) return;

    const repo = apiRepository(keycloak);
    repo.admin.albums
      .retrieve(slug)
      .then((res) => {
        console.log(res.data);
        setAlbum(res.data);
      })
      .catch((err) => {
        console.error(err);
      });
  }, [initialized]);

  return (
    <div>
      <AlbumForm
        album={album}
        type="edit"
        onPostSubmit={() => {
          history.push("/albums");
        }}
      />
    </div>
  );
};

export default ArticleCreate;
