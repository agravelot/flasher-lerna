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
import { Album, Media } from "@flasher/models";
import MediaUploader from "../components/MediaUploader";
import { MediaOrdering } from "../components/MediaOrdering";

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

  const updateMediasOrder = async (medias: Media[]): Promise<void> => {
    try {
      if (!album) {
        throw new Error("Unable to update medias from undefined album.");
      }
      if (!album.medias) {
        throw new Error("Unable to update medias from undefined medias.");
      }
      const repo = apiRepository(keycloak);

      await repo.admin.medias.order(
        medias.map((m) => m.id),
        slug
      );
      // showSuccess(this.$buefy, "Pictures successfully re-ordered");
    } catch (exception) {
      // showError(this.$buefy, "Unable to re-ordered the pictures");
      console.error(exception);

      throw exception;
    }
  };

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

      {album && album.medias && (
        <MediaOrdering
          medias={album.medias}
          setMedia={(medias: Media[]) => {
            // album.medias = medias;
            // setAlbum(album);
            updateMediasOrder(medias);
            fetchAlbum();
          }}
        />
      )}

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
