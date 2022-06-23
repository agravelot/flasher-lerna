import { FunctionComponent } from "react";
import { calculateReadingTime } from "./../utils/util";

interface Props {
    body: string
}
const ReadingTime: FunctionComponent<Props> = ({
    body
}: Props) => {  
  const estimatedReadingInMinutes = Math.round(calculateReadingTime(body));
  if(estimatedReadingInMinutes === 0){
    return null;
  } 
  return (
    <div className="flex italic pb-8 content-end">
        <span>Temps de lecture estimé : {estimatedReadingInMinutes} minutes.</span>
    </div>
  );
};

export default ReadingTime;
