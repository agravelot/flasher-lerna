import { FunctionComponent } from "react";
import Image from "next/future/image";
import Separator from "./Separator";
import { Media } from "@flasher/models";

const defaultMedia: Media = {
  id: 9999,
  url: "https://assets-jkanda.s3.fr-par.scw.cloud/medias/4191/JKanda_JKA_5053.jpg",
  name: "Image présentation JKanda",
  file_name: "aze",
  width: 6000,
  height: 4000,
};

class Props {
  title?: string;
  description?: string;
  children?: React.ReactNode;
  separatorClass?: string;
  src?: string;
  altDescription?: string;
  width?: number;
  height?: number;
}

const Header: FunctionComponent<Props> = ({
  title,
  description,
  children,
  separatorClass = "text-white",
  src = defaultMedia.url,
  altDescription = defaultMedia.name,
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
        className="h-full w-full object-cover"
        draggable={false}
        priority
      />
    </div>
    <div className="container relative mx-auto">
      <div className="flex flex-wrap items-center">
        <div className="ml-auto mr-auto w-full px-4 text-center lg:w-6/12">
          <h1 className="text-5xl font-semibold text-white">{title}</h1>
          <p className="mt-4 text-lg text-gray-300">{description}</p>
          {children}
        </div>
      </div>
    </div>
    <Separator separatorClass={separatorClass} position={"bottom"} />
  </div>
);

export default Header;
