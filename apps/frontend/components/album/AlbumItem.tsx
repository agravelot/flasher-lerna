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
    <div className="rounded overflow-hidden shadow-lg bg-white transform hover:scale-105 transition duration-200 ease-in-out motion-reduce:transition-none motion-reduce:transform-none">
      <Link
        href={`/galerie/${album.slug}`}
      >
        <a
          className="font-bold text-xl mb-2"
          tabIndex={-1}
          aria-label={album.title}
        >
          {album.media && (
            <Image
              className="h-64 w-full"
              src={album.media.url}
              alt={album.title}
              layout="responsive"
              height={album.media.height}
              width={album.media.width}
              sizes={sizes(3, "container")}
              objectFit="cover"
              draggable={false}
            />
          )}
        </a>
      </Link>

      <div className="px-6 py-4">
        <Link
          href={{ pathname: "/galerie/[slug]", query: { slug: album.slug } }}
          //  :to="{ name: 'albums-slug', params: { slug: album.slug } }"
        >
          <a tabIndex={0} aria-label={album.title}>
            <h3 className="font-bold text-xl mb-2 leading-tight">
              {album.title}
            </h3>
          </a>
        </Link>
      </div>
      <div className="px-6 py-4">
        {album.categories?.map((category) => (
          <Link key={category.id} href={`/categories/${category.slug}`}>
            <a tabIndex={0}>
              <span className="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 m-1">
                {category.name}
              </span>
            </a>
          </Link>
        ))}
      </div>
    </div>
  );
};

export default AlbumItem;
