import { FC } from "react";
import { ReactSortable } from "react-sortablejs";
import { Media } from "@flasher/models";

interface Props {
  medias: Media[];
  setMedia: (medias: Media[]) => void;
}

export const MediaOrdering: FC<Props> = ({ medias, setMedia }: Props) => {
  //   const [state, setState] = useState<ItemType[]>([
  //     { id: 1, name: "shrek" },
  //     { id: 2, name: "fiona" },
  //   ]);

  return (
    <ReactSortable list={medias} setList={setMedia}>
      {medias.map((media) => (
        <img key={media.id} src={media.url} width="150px" />
      ))}
    </ReactSortable>
  );
};
