import { apiRepository } from "@flasher/common";
import { FunctionComponent } from "react";
import AlbumTable from "../components/AlbumsTable";
import { useLocation } from "react-router-dom";
import { Pagination } from "../components/Pagination";
import { useAuthentication } from "../hooks/useAuthentication";
import { useQuery } from "@tanstack/react-query";

const AlbumList: FunctionComponent = () => {
  const location = useLocation();
  const params = new URLSearchParams(location.search);
  const { keycloak, initialized } = useAuthentication();

  const { data } = useQuery({
    queryKey: ["albums-list"],
    enabled: initialized && !!keycloak,
    queryFn: () => {
      if (!initialized || !keycloak) {
        return;
      }
      return apiRepository(keycloak).admin.albums.list({
        page: Number(params.get("page")) ?? 1,
        perPage: 10,
      });
    },
  });

  return (
    <div>
      <AlbumTable albums={data?.data ?? []} />
      {data?.meta ? (
        <Pagination
          showInfo={true}
          currentPage={data.meta.current_page}
          perPage={data.meta.per_page}
          totalItems={data.meta.total}
          from={data.meta.from}
          to={data.meta.to}
          lastPage={data.meta.last_page}
          routeName="/albums"
        />
      ) : null}
    </div>
  );
};

export default AlbumList;
