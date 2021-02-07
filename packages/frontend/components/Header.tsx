import { FunctionComponent } from "react";
import Media from "~/models/media";
import Image from "next/image";
import Separator from "~/components/Separator";

const defaultMedia: Media = {
  id: 9999,
  src_set:
    "https://assets.jkanda.fr/production/medias/3151/responsive-images/shooting-magicluna-en-saber___responsive_6000_4000.jpg 6000w," +
    "https://assets.jkanda.fr/production/medias/3151/responsive-images/shooting-magicluna-en-saber___responsive_5019_3346.jpg 5019w," +
    "https://assets.jkanda.fr/production/medias/3151/responsive-images/shooting-magicluna-en-saber___responsive_4200_2800.jpg 4200w," +
    "https://assets.jkanda.fr/production/medias/3151/responsive-images/shooting-magicluna-en-saber___responsive_3513_2342.jpg 3513w," +
    "https://assets.jkanda.fr/production/medias/3151/responsive-images/shooting-magicluna-en-saber___responsive_2939_1959.jpg 2939w," +
    "https://assets.jkanda.fr/production/medias/3151/responsive-images/shooting-magicluna-en-saber___responsive_2459_1639.jpg 2459w," +
    "https://assets.jkanda.fr/production/medias/3151/responsive-images/shooting-magicluna-en-saber___responsive_2058_1372.jpg 2058w," +
    "https://assets.jkanda.fr/production/medias/3151/responsive-images/shooting-magicluna-en-saber___responsive_1721_1147.jpg 1721w," +
    "https://assets.jkanda.fr/production/medias/3151/responsive-images/shooting-magicluna-en-saber___responsive_1440_960.jpg 1440w," +
    "https://assets.jkanda.fr/production/medias/3151/responsive-images/shooting-magicluna-en-saber___responsive_1205_803.jpg 1205w," +
    "https://assets.jkanda.fr/production/medias/3151/responsive-images/shooting-magicluna-en-saber___responsive_1008_672.jpg 1008w," +
    "https://assets.jkanda.fr/production/medias/3151/responsive-images/shooting-magicluna-en-saber___responsive_843_562.jpg 843w," +
    "https://assets.jkanda.fr/production/medias/3151/responsive-images/shooting-magicluna-en-saber___responsive_705_470.jpg 705w," +
    "https://assets.jkanda.fr/production/medias/3151/responsive-images/shooting-magicluna-en-saber___responsive_590_393.jpg 590w," +
    "https://assets.jkanda.fr/production/medias/3151/responsive-images/shooting-magicluna-en-saber___responsive_494_329.jpg 494w," +
    "https://assets.jkanda.fr/production/medias/3151/responsive-images/shooting-magicluna-en-saber___responsive_413_275.jpg 413w," +
    "https://assets.jkanda.fr/production/medias/3151/responsive-images/shooting-magicluna-en-saber___responsive_345_230.jpg 345w," +
    "data:image/svg+xml;base64,PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHg9IjAiCiB5PSIwIiB2aWV3Qm94PSIwIDAgNjAwMCA0MDAwIj4KCTxpbWFnZSB3aWR0aD0iNjAwMCIgaGVpZ2h0PSI0MDAwIiB4bGluazpocmVmPSJkYXRhOmltYWdlL2pwZWc7YmFzZTY0LC85ai80QUFRU2taSlJnQUJBUUFBQVFBQkFBRC8yd0JEQUFNQ0FnTUNBZ01EQXdNRUF3TUVCUWdGQlFRRUJRb0hCd1lJREFvTURBc0tDd3NORGhJUURRNFJEZ3NMRUJZUUVSTVVGUlVWREE4WEdCWVVHQklVRlJULzJ3QkRBUU1FQkFVRUJRa0ZCUWtVRFFzTkZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlQvd0FBUkNBQVZBQ0FEQVJFQUFoRUJBeEVCLzhRQUdRQUFBZ01CQUFBQUFBQUFBQUFBQUFBQUJBY0NCZ2dGLzhRQUl4QUFBZ0VFQVFRREFRQUFBQUFBQUFBQUFRSUVBQU1GQmhFSEVqRlJDQ0ZCY2YvRUFCa0JBQU1CQVFFQUFBQUFBQUFBQUFBQUFBTUVCUUlCQnYvRUFCMFJBQUlEQVFBREFRQUFBQUFBQUFBQUFBRUNBQU1SQkJJaFFUSC8yZ0FNQXdFQUFoRURFUUEvQUdualpJazQ5bEYwTjllNmtnYkhkbkdzaVRHbHNReDdlZnlwOTdGVDZqOUZZWVNHZDZrU2NGSE5zT1FmN1c2TEQ5Z3I2L0V5aXpOMmxiRVdSMkxBMDl1eEg4aTMwUHFybDVjNjNFVHVjRWdVYXBDNXdRWmNLTk0wN3JHTGt6SVNYNUNFRmh6OTEyL2pLalRHdVRxQk9TbWRUOUVuekVhOUhSaW85Q2dEaVlMNUNjdDZsWjhNWG10NFNja2cyVGJZT0R4NG9TQWc0WmdrZklmOGM5T3g4NmRhdlhVRE55UElxeHllakpQVVNCTlpadUZheHNDMHRsUW80SGlyVnFCeDdpWE5ZVjBpRlFJTmlkaG5GMjJHNUg2S010U3JYa3diR2F5TENkcjBXRm1IZTJvQjd2VmVYNkVDc2NsdXNuSi8vOWs9Ij4KCTwvaW1hZ2U+Cjwvc3ZnPgoK   32w",
  thumb:
    "https://assets.jkanda.fr/production/medias/3151/shooting-magicluna-en-saber.jpg",
  url:
    "https://assets.jkanda.fr/production/medias/3151/shooting-magicluna-en-saber.jpg",
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
  srcSet?: string;
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
    <div className="absolute top-0 w-full h-full bg-center bg-cover">
      <span className="w-full h-full absolute bg-black"></span>
      <Image
        className="opacity-75"
        // className="object-cover w-full h-full"
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
