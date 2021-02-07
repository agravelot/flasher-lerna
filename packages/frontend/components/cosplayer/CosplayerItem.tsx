import { FunctionComponent } from "react";
import Avatar from "../../components/Avatar";
import Link from "next/link";
import { Cosplayer } from "@flasher/models";

interface Props {
  cosplayer: Cosplayer;
}

const CosplayerItem: FunctionComponent<Props> = ({ cosplayer }: Props) => {
  return (
    <div className="max-w-sm mx-auto">
      <div className="flex flex-col md:flex-row items-center px-6 py-4">
        <Avatar name={cosplayer.name} src={cosplayer.avatar?.url} />
        <div className="mt-4 md:mt-0 md:ml-4 text-center md:text-left">
          <div className="text-xl leading-tight">{cosplayer.name}</div>
          <div className="text-sm leading-tight text-gray-800">Cosplayer</div>
          <div className="mt-4">
            <Link
              href={{
                pathname: "/cosplayers/[slug]",
                query: { slug: cosplayer.slug },
              }}
            >
              <a
                tabIndex={0}
                className="text-red-700 hover:text-white hover:bg-red-700 border border-red-700 text-xs font-semibold rounded-full px-4 py-1 leading-normal"
              >
                Voir le profil
              </a>
            </Link>
          </div>
        </div>
      </div>
    </div>
  );
};

export default CosplayerItem;
