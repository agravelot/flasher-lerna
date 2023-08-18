import { FunctionComponent } from "react";
import Avatar from "../Avatar";
import Link from "next/link";
import { Cosplayer } from "@flasher/models";

interface Props {
  cosplayer: Cosplayer;
}

const CosplayerItem: FunctionComponent<Props> = ({ cosplayer }: Props) => {
  return (
    <div className="mx-auto max-w-sm">
      <div className="flex flex-col items-center px-6 py-4 md:flex-row">
        <Avatar name={cosplayer.name} src={cosplayer.avatar?.url} width={cosplayer.avatar?.width} height={cosplayer.avatar?.height} />
        <div className="mt-4 text-center md:mt-0 md:ml-4 md:text-left">
          <div className="text-xl leading-tight">{cosplayer.name}</div>
          <div className="text-sm leading-tight text-gray-800">Modèle</div>
          <div className="mt-4">
            <Link
              href={{
                pathname: "/cosplayers/[slug]",
                query: { slug: cosplayer.slug },
              }}
              tabIndex={0}
              className="rounded-full border border-red-700 px-4 py-1 text-xs font-semibold leading-normal text-red-700 hover:bg-red-700 hover:text-white"
            >
              Voir le profil
            </Link>
          </div>
        </div>
      </div>
    </div>
  );
};

export default CosplayerItem;
