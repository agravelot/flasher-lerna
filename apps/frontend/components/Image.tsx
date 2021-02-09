import { FunctionComponent } from "react";
import ImageNext from "next/image";

interface Props {
  src: string;
  srcSet?: string;
  alt?: string;
  sizes?: string;
  className?: string;
  width: number;
  height: number;
}

const Image: FunctionComponent<Props> = ({
  src,
  alt,
  className = "",
  width,
  height,
}: Props) => {
  return (
    <ImageNext
      draggable={false}
      src={src}
      alt={alt}
      width={width}
      height={height}
      className={className}
      layout="responsive"
    />
  );
};

export default Image;
