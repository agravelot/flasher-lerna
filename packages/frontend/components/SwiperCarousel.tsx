import { FunctionComponent } from "react";
import Media from "~/models/media";
import Image from "next/image";
import { Swiper, SwiperSlide } from "swiper/react";
import SwiperCore, {
  Navigation,
  Pagination,
  Scrollbar,
  A11y,
  Keyboard,
  Mousewheel,
} from "swiper";
import "swiper/swiper-bundle.css";

export type Props = {
  medias: Media[];
  beginAt: number;
};

export const SwiperCarousel: FunctionComponent<Props> = ({
  medias,
  beginAt,
}: Props) => {
  SwiperCore.use([
    Navigation,
    Pagination,
    Scrollbar,
    A11y,
    Keyboard,
    Mousewheel,
  ]);

  return (
    <Swiper
      initialSlide={beginAt}
      spaceBetween={350}
      slidesPerView={1}
      navigation
      pagination={{ clickable: true }}
      loop
      keyboard={{ enabled: true }}
      mousewheel
      zoom
      grabCursor
    >
      {medias.map((m) => (
        <SwiperSlide key={m.id} zoom>
          <div className="flex items-center align-middle justify-center h-screen">
            <Image
              objectFit="contain"
              className="max-h-screen w-full"
              layout="fill"
              src={m.url}
              alt={m.name}
              draggable={false}
            />
          </div>
        </SwiperSlide>
      ))}
    </Swiper>
  );
};
