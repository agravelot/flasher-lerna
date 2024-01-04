import { Cosplayer } from "@flasher/models";
import React, { FC } from "react";
import CosplayerItem from "./CosplayerItem";

type Props = {
  cosplayers: Cosplayer[];
  className?: string;
};

const CosplayerList: FC<Props> = ({ cosplayers, className = "" }: Props) => {
  return (
    <div className={"container mx-auto py-8" + className}>
      <div className="flex flex-wrap md:-mx-3">
        {cosplayers.map((cosplayer) => (
          <div className="w-full p-3 md:w-1/3" key={cosplayer.id}>
            <CosplayerItem cosplayer={cosplayer} />
          </div>
        ))}
      </div>
    </div>
  );
};

export default CosplayerList;
