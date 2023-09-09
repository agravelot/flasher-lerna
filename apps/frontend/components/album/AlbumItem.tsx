import Link from "next/link";
import React, { FunctionComponent } from "react";
import Image from "next/image";
import { Album } from "@flasher/models";
import { sizes } from "../../utils/util";

interface Props {
  album: Album;
}

const AlbumItem: FunctionComponent<Props> = ({ album }: Props) => {
  return (
    <div className="transform overflow-hidden rounded bg-white shadow-lg transition duration-200 ease-in-out hover:scale-105 motion-reduce:transform-none motion-reduce:transition-none">
      <Link
        href={`/galerie/${album.slug}`}
        className="mb-2 text-xl font-bold"
        tabIndex={-1}
        aria-label={album.title}
      >
        {album.media && (
          <Image
            className="h-64 w-full object-cover"
            src={album.media.url}
            alt={album.title}
            height={album.media.height}
            width={album.media.width}
            sizes={sizes(3, "container")}
            draggable={false}
          />
        )}
      </Link>

      <div className="px-6 py-4">
        <Link
          href={{ pathname: "/galerie/[slug]", query: { slug: album.slug } }}
          tabIndex={0}
          aria-label={album.title}
          //  :to="{ name: 'albums-slug', params: { slug: album.slug } }"
        >
          <h3 className="text-xl font-bold leading-tight">
            {album.title}
          </h3>
        </Link>
      </div>
      <div className="px-6 pb-4">
        {album.categories?.map((category) => (
          <Link
            key={category.id}
            href={`/categories/${category.slug}`}
            tabIndex={0}
          >
            <span className="m-1 inline-block rounded-full bg-gray-200 px-3 py-1 text-sm text-gray-700">
              {category.name}
            </span>
          </Link>
        ))}
      </div>
    </div>
  );
};

export default AlbumItem;
