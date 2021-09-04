import { FunctionComponent } from "react";
import { DiscussionEmbed } from "disqus-react";

interface Props {
    url: string;
    identifier: string,
    title: string
}

const Comments: FunctionComponent<Props> = ({
    url, 
    identifier, 
    title
}: Props) => {
  return (
    <DiscussionEmbed
          shortname='jkanda'
          config={
              {
                  url,
                  identifier,
                  title,
                  language: "fr"
              }
          }
        />
  );
};

export default Comments;
