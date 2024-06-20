import {
  FunctionComponent,
  useCallback,
  useEffect,
  useMemo,
  useState,
} from "react";
import { useNavigate, useParams } from "react-router-dom";
import AlbumForm from "../components/AlbumForm";
import { apiClient } from "@flasher/common";
import { Album, Media } from "@flasher/models";
import MediaUploader from "../components/MediaUploader";
import { MediaOrdering } from "../components/MediaOrdering";
import { useAuthentication } from "../hooks/useAuthentication";

const AlbumEdit: FunctionComponent = () => {
  const navigate = useNavigate();
  const { initialized, keycloak } = useAuthentication();

  const [album, setAlbum] = useState<Album>();
  const albumIdMemo = useMemo(() => album?.id, [album]);

  const { slug } = useParams<{ slug: string }>();

  const fetchAlbum = useCallback(() => {
    if (!initialized || !keycloak) {
      return;
    }

    if (!slug) {
      throw new Error("missing slug");
    }

    const repo = apiClient(keycloak);
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
      if (!initialized || !keycloak) {
        return;
      }
      if (!album) {
        throw new Error("Unable to update medias from undefined album.");
      }
      if (!album.medias) {
        throw new Error("Unable to update medias from undefined medias.");
      }

      if (!slug) {
        throw new Error("missing slug");
      }

      const repo = apiClient(keycloak);

      await repo.admin.medias.order(
        medias.map((m) => m.id),
        slug,
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
          navigate("/albums");
        }}
      />

      {album?.medias && (
        <MediaOrdering
          medias={album.medias}
          setMedia={(medias: Media[]) => {
            album.medias = medias;
            setAlbum(album);
          }}
          onEnd={() => {
            if (!album.medias) {
              return;
            }
            updateMediasOrder(album.medias);
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
