import { Album } from "@flasher/models";
import { MetaPaginatedReponse, apiClient } from "@flasher/common";
import { FunctionComponent, useEffect, useState } from "react";
import AlbumTable from "../components/AlbumsTable";
import { useLocation } from "react-router-dom";
import { Pagination } from "../components/Pagination";
import { useAuthentication } from "../hooks/useAuthentication";

function useQuery() {
  return new URLSearchParams(useLocation().search);
}

const AlbumList: FunctionComponent = () => {
  const query = useQuery();
  const [albums, setAlbums] = useState<Album[]>([]);
  const [meta, setMeta] = useState<MetaPaginatedReponse | null>(null);
  const { keycloak, initialized } = useAuthentication();

  const location = useLocation();

  useEffect(() => {
    if (!initialized || !keycloak) {
      return;
    }

    const repo = apiClient(keycloak);

    repo.admin.albums
      .list({ page: Number(query.get("page")) ?? 1, perPage: 10 })
      .then((res) => {
        setAlbums(res.data);
        setMeta(res.meta);
      });
  }, [location, initialized]);

  return (
    <div>
      <AlbumTable albums={albums} />
      {meta && (
        <Pagination
          showInfo={true}
          currentPage={meta.current_page}
          perPage={meta.per_page}
          totalItems={meta.total}
          from={meta.from}
          to={meta.to}
          lastPage={meta.last_page}
          routeName="/albums"
        />
      )}
    </div>
  );
};

export default AlbumList;
