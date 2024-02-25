import { FunctionComponent } from "react";
import { ReactSortable } from "react-sortablejs";
import { Media } from "@flasher/models";

interface Props {
  medias: Media[];
  setMedia: (medias: Media[]) => void;
  onEnd: () => void;
}

export const MediaOrdering: FunctionComponent<Props> = ({
  medias,
  setMedia,
  onEnd,
}: Props) => {
  return (
    <div>
      <ReactSortable
        list={medias}
        setList={setMedia}
        animation={200}
        className="flex flex-wrap"
        onEnd={onEnd}
      >
        {medias.map((media) => (
          <div key={media.id} className="w-1/3 h-96 mt-4">
            <img
              src={media.url}
              width={media.width}
              height={media.height}
              className="max-h-full max-w-full mx-auto"
            />
          </div>
        ))}
      </ReactSortable>
    </div>
  );
};
