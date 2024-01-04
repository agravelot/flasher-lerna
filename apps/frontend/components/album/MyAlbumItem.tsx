import { FC } from "react";
import Image from "next/image";
import Link from "next/link";
import { Album } from "@flasher/models";
import { api } from "@flasher/common";
import { sizes } from "utils/util";
import { useAuthentication } from "hooks/useAuthentication";

export interface Props {
  album: Album;
  showDownload: boolean;
}

interface GenerateDownloakLink {
  url: string;
}

function downloadURI(uri: string, fileName: string) {
  const link = document.createElement("a");
  link.download = fileName;
  link.href = uri;
  document.body.appendChild(link); // Needed for Firefox
  link.click();
  document.body.removeChild(link);
}

const MyAlbumItem: FC<Props> = ({ album, showDownload }: Props) => {
  const { keycloak } = useAuthentication();

  const downloadAlbum = async (): Promise<void> => {
    const response = await api<GenerateDownloakLink>(
      `/generate-download-albums/${album.slug}`,
      {
        headers: {
          Authorization: `Bearer ${keycloak?.token}`,
        },
      },
    )
      .then((res) => {
        if (!res.response.ok) {
          // TODO Notify user here
        }
        return res.json();
      })
      .then((data) => data);

    downloadURI(response.url, `${album.slug}.zip`);
  };

  return (
    <div className="w-full lg:flex lg:max-w-full">
      <div className="flex-none overflow-hidden rounded-t text-center lg:w-64 lg:rounded-t-none lg:rounded-l">
        {album.media && (
          <Image
            className="h-64 w-full rounded-t object-cover lg:rounded-t-none lg:rounded-l"
            src={album.media.url}
            alt={album.title}
            width={album.media.width}
            height={album.media.height}
            sizes={sizes(2, "container")}
            draggable={false}
          />
        )}
      </div>
      <div className="flex w-full flex-col justify-between rounded-b border-r border-b border-l border-gray-400 bg-white p-4 leading-normal lg:rounded-b-none lg:rounded-r lg:border-l-0 lg:border-t lg:border-gray-400">
        <div className="mb-8">
          {album.private && (
            <p className="flex items-center text-sm text-gray-600">
              <svg
                className="mr-2 h-3 w-3 fill-current text-gray-500"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
              >
                <path d="M4 8V6a6 6 0 1 1 12 0v2h1a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-8c0-1.1.9-2 2-2h1zm5 6.73V17h2v-2.27a2 2 0 1 0-2 0zM7 6v2h6V6a3 3 0 0 0-6 0z" />
              </svg>
              Privé
            </p>
          )}
          <div className="mb-2 text-xl font-bold text-gray-900">
            {album.title}
          </div>
        </div>
        <div className="flex items-center">
          {!album.private && (
            <div className="w-1/2">
              <Link
                href={{
                  pathname: "/albums/[slug]",
                  query: { slug: album.slug },
                }}
                className="mx-3 inline-flex flex-grow items-center rounded bg-gray-300 py-2 px-4 text-gray-800 hover:bg-gray-400"
              >
                Voir l&apos;album
              </Link>
            </div>
          )}

          <div className="w-1/2">
            {showDownload && (
              <button
                className="mx-3 inline-flex flex-grow items-center rounded bg-gray-300 py-2 px-4 text-gray-800 hover:bg-gray-400"
                onClick={() => downloadAlbum()}
              >
                <svg
                  className="mr-2 h-4 w-4 fill-current"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 20 20"
                >
                  <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z" />
                </svg>
                <span>Télécharger</span>
              </button>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};

export default MyAlbumItem;
