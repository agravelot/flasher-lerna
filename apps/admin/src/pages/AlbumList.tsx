import { apiRepository } from "@flasher/common/src/useRepository";
import { Album } from "@flasher/models";
import { FunctionComponent, useEffect, useState } from "react";
import AlbumTable from "../components/AlbumsTable";

const AlbumList: FunctionComponent = () => {
  const [albums, setAlbums] = useState<Album[]>([]);

  useEffect(() => {
    const repo = apiRepository();

    repo.albums.list({ page: 1, perPage: 10 }).then((res) => {
      setAlbums(res.data);
    });
  }, []);

  return (
    <div>
      <AlbumTable albums={albums} />
    </div>
  );
};

export default AlbumList;
