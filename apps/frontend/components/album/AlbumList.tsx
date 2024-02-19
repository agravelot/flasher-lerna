import { Album } from "@flasher/models";
import React, { FC } from "react";
import AlbumItem from "./AlbumItem";

type Props = {
  albums: Album[];
  className?: string;
};

const AlbumList: FC<Props> = ({ albums, className = "" }: Props) => {
  return (
    <div className={"container mx-auto py-8" + className}>
      <div className="flex flex-wrap md:-mx-3">
        {albums.map((album) => (
          <div className="w-full p-3 md:w-1/3" key={album.id}>
            <AlbumItem album={album} />
          </div>
        ))}
      </div>
    </div>
  );
};

export default AlbumList;
