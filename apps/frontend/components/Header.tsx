import { FunctionComponent } from "react";
import Image from "next/image";
import Separator from "./Separator";
import { Media } from "@flasher/models";

const defaultMedia: Media = {
  id: 9999,
  url:
    "https://assets-jkanda.s3.fr-par.scw.cloud/medias/3499/oceane-avec-un-style-androgyne.jpg",
  name: "",
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
    className="relative pt-16 pb-32 flex content-center items-center justify-center"
    style={{ minHeight: "75vh" }}
  >
    <div
      className="absolute top-0 w-full h-full bg-center bg-cover"
      style={{ filter: "brightness(80%);" }}
    >
      <Image
        src={src}
        alt={altDescription}
        layout="fill"
        objectFit="cover"
        draggable={false}
        priority
      />
    </div>
    <div className="container relative mx-auto">
      <div className="items-center flex flex-wrap">
        <div className="w-full lg:w-6/12 px-4 ml-auto mr-auto text-center">
          <h1 className="text-white font-semibold text-5xl">{title}</h1>
          <p className="mt-4 text-lg text-gray-300">{description}</p>
          {children}
        </div>
      </div>
    </div>
    <Separator separatorClass={separatorClass} position={"bottom"} />
  </div>
);

export default Header;
