import { FC } from "react";
import { calculateReadingTime } from "./../utils/util";

interface Props {
  body: string;
}
const ReadingTime: FC<Props> = ({ body }: Props) => {
  const estimatedReadingInMinutes = calculateReadingTime(body);
  if (estimatedReadingInMinutes === 0) {
    return null;
  }
  return (
    <div className="flex content-end pb-8 italic">
      <span>
        Temps de lecture estimé : {estimatedReadingInMinutes} minutes.
      </span>
    </div>
  );
};

export default ReadingTime;
