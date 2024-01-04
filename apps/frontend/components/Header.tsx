import { FC, ReactNode } from "react";
import Image from "next/image";
import Separator from "./Separator";
import { Media } from "@flasher/models";

///_next/image?url=https%3A%2F%2Fassets-jkanda.s3.fr-par.scw.cloud%2Fmedias%2F3750%2Fmariage-sofia-et-elyess.jpg&w=3840&q=75

const defaultMedia: Media = {
  id: 9999,
  url: "https://assets-jkanda.s3.fr-par.scw.cloud/medias/3750/mariage-sofia-et-elyess.jpg",
  name: "Image présentation JKanda",
  file_name: "aze",
  width: 6000,
  height: 4000,
};

class Props {
  title?: string;
  description?: string;
  children?: ReactNode;
  separatorClass?: string;
  src?: string;
  altDescription?: string;
  width?: number;
  height?: number;
  breadcrumb?: ReactNode;
}

const Header: FC<Props> = ({
  title,
  description,
  children,
  separatorClass = "text-white",
  src = defaultMedia.url,
  altDescription = defaultMedia.name,
  width = defaultMedia.width,
  height = defaultMedia.height,
  breadcrumb,
}: Props) => (
  <div
    className="relative flex items-center justify-center pt-16 pb-32"
    style={{ minHeight: "75vh" }}
  >
    <div className="absolute top-0 h-full w-full bg-black bg-cover bg-center">
      <Image
        style={{ filter: "brightness(80%)" }}
        src={src}
        alt={altDescription}
        width={width}
        height={height}
        className="h-full w-full object-cover"
        draggable={false}
        priority
        sizes="100vw"
      />
    </div>
    <div className="container absolute top-[64px]">{breadcrumb}</div>

    <div className="container relative mx-auto">
      <div className="flex flex-wrap items-center">
        <div className="ml-auto mr-auto w-full px-4 text-center lg:w-6/12">
          <h1 className="text-5xl font-semibold text-white drop-shadow-lg">
            {title}
          </h1>
          <p className="mt-4 text-lg text-gray-300 drop-shadow-lg">
            {description}
          </p>
          {children}
        </div>
      </div>
    </div>
    <Separator separatorClass={separatorClass} position={"bottom"} />
  </div>
);

export default Header;
