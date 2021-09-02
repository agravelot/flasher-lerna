import { useKeycloak } from "@react-keycloak/web";
import {
  FunctionComponent,
  useCallback,
  useEffect,
  useMemo,
  useState,
} from "react";
import { useHistory, useParams } from "react-router-dom";
import AlbumForm from "../components/AlbumForm";
import { apiRepository } from "@flasher/common";
import { Album } from "@flasher/models";
import MediaUploader from "../components/MediaUploader";

const AlbumEdit: FunctionComponent = () => {
  const history = useHistory();
  const { initialized, keycloak } = useKeycloak();

  const [album, setAlbum] = useState<Album>();
  const albumIdMemo = useMemo(() => album?.id, [album]);

  const { slug } = useParams<{ slug: string }>();

  const fetchAlbum = useCallback(() => {
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
  }, []);

  useEffect(() => {
    document.title = "Create Album";

    if (!initialized) return;

    fetchAlbum();
  }, [initialized]);

  return (
    <div>
      <AlbumForm
        album={album}
        type="edit"
        // onPostSubmit={() => {
        //   history.push("/albums");
        // }}
        onPostDelete={() => {
          history.push("/albums");
        }}
      />
      {album &&
        album.medias?.map((media, i) => {
          return <img key={i} src={media.url} width="150px" />;
        })}

      {albumIdMemo && (
        <MediaUploader
          modelClass="album"
          modelId={albumIdMemo}
          onUploadSuccess={fetchAlbum}
        />
      )}
    </div>
  );
};

export default AlbumEdit;
