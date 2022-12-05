import React, { FunctionComponent } from "react";
import Image from "next/image";
import { ImageGallery, ImageObject, Person } from "schema-dts";
import { useRouter } from "next/dist/client/router";
import { sizes } from "../../utils/util";
import { configuration } from "../../utils/configuration";
import { Album } from "@flasher/models";

type Props = {
  album: Album;
  openGalleryAt: (index: number) => void;
};

const AlbumMediaList: FunctionComponent<Props> = ({
  album,
  openGalleryAt,
}: Props) => {
  const { asPath } = useRouter();

  const person: Person = {
    "@type": "Person",
    name: "JKanda",
    url: configuration.appUrl,
  };

  const image: ImageObject = {
    "@type": "ImageObject",
    thumbnailUrl: album.medias?.[0].url,
    contentUrl: album.medias?.[0].url,
    author: person,
    name: album.medias?.[0].name,
    license: "",
    acquireLicensePage: "",
  };

  const jsonLd: ImageGallery = {
    "@type": "ImageGallery",
    name: album.title,
    keywords: album.categories?.map((c) => c.name) ?? [],
    isAccessibleForFree: true,
    primaryImageOfPage: image,
    image,
    author: [person],
    creator: person,
    dateCreated: album.created_at,
    dateModified: album.updated_at ?? undefined,
    datePublished: album.published_at ?? undefined,
    thumbnailUrl: album.medias?.[0].url,
    url: process.env.APP_URL + asPath,
    abstract: album.meta_description,
    // copyrightHolder: getModule(JsonLdModule, $store).getOrganization,
    copyrightYear: new Date(album.created_at).getFullYear(),
    license: "",
    inLanguage: { "@type": "Language", name: "French" },
  };

  return (
    <div className="-mx-2 flex flex-wrap items-center">
      <div>
        <script
          type="application/ld+json"
          dangerouslySetInnerHTML={{
            __html: `${JSON.stringify(jsonLd)}`,
          }}
        ></script>
      </div>

      {album.medias?.map((media, index) => (
        <div
          key={media.id}
          className="w-full flex-auto flex-grow-0 cursor-pointer cursor-zoom-in md:w-1/2"
          onClick={() => openGalleryAt(index)}
          tabIndex={0}
        >
          <Image
            className="w-full object-contain p-1"
            src={media.url}
            alt={media.name}
            height={media.height}
            width={media.width}
            sizes={sizes(2, "container")}
            draggable={false}
          />
        </div>
      ))}
    </div>
  );
};

export default AlbumMediaList;
