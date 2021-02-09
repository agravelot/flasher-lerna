import { FunctionComponent } from "react";
import Image from "next/image";
import Link from "next/link";
import { useKeycloak } from "@react-keycloak/ssr";
import { useAnalytics } from "../../hooks/useAnalytics";
import { Album } from "@flasher/models";
import { api } from "@flasher/common";

export interface Props {
  album: Album;
  showDownload: boolean;
}

interface GenerateDownloakLink {
  url: string;
}

const MyAlbumItem: FunctionComponent<Props> = ({
  album,
  showDownload,
}: Props) => {
  const { keycloak } = useKeycloak();
  const { exception } = useAnalytics();

  const downloadAlbum = async (): Promise<void> => {
    const response = await api<GenerateDownloakLink>(
      `/generate-download-albums/${album.slug}`,
      {
        headers: {
          Authorization: `Bearer ${keycloak?.token}`,
        },
      }
    )
      .then((res) => {
        if (!res.response.ok) {
          exception(res.response);
          // TODO Notify user here
        }
        return res.json();
      })
      .then((data) => data);

    window.open(response.url, "_blank");
  };

  return (
    <div className="w-full lg:max-w-full lg:flex">
      <div className="h-48 lg:h-auto lg:w-48 flex-none bg-cover rounded-t lg:rounded-t-none lg:rounded-l text-center overflow-hidden">
        {album.media && (
          <Image
            className="h-64 w-full object-cover"
            src={album.media.url}
            alt={album.title}
            width={album.media.width}
            height={album.media.height}
            draggable={false}
            layout="responsive"
          />
        )}
      </div>
      <div className="w-full border-r border-b border-l border-gray-400 lg:border-l-0 lg:border-t lg:border-gray-400 bg-white rounded-b lg:rounded-b-none lg:rounded-r p-4 flex flex-col justify-between leading-normal">
        <div className="mb-8">
          {album.private && (
            <p
              v-if="album.private"
              className="text-sm text-gray-600 flex items-center"
            >
              <svg
                className="fill-current text-gray-500 w-3 h-3 mr-2"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
              >
                <path d="M4 8V6a6 6 0 1 1 12 0v2h1a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-8c0-1.1.9-2 2-2h1zm5 6.73V17h2v-2.27a2 2 0 1 0-2 0zM7 6v2h6V6a3 3 0 0 0-6 0z" />
              </svg>
              Privé
            </p>
          )}
          <div className="text-gray-900 font-bold text-xl mb-2">
            {album.title}
          </div>
        </div>
        <div className="flex items-center">
          <div className="w-1/2">
            <Link
              v-if="!album.private"
              href={{
                pathname: "/albums/[slug]",
                query: { slug: album.slug },
              }}
            >
              <a className="bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-4 rounded inline-flex items-center mx-3 flex-grow">
                Voir l'album
              </a>
            </Link>
          </div>

          <div className="w-1/2">
            {showDownload && (
              <button
                className="bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-4 rounded inline-flex items-center mx-3 flex-grow"
                onClick={() => downloadAlbum()}
              >
                <svg
                  className="fill-current w-4 h-4 mr-2"
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
