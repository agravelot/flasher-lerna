import { FunctionComponent, useEffect, useState } from "react";
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
import { Media } from "@flasher/models";
import { debounce } from "lodash-es";

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

  const [screenSize, setScreenSize] = useState({
    width: window.innerWidth,
    height: window.innerHeight,
  });

  // Will force rerender on screen resize, allow dynamic `sizes`to wark
  useEffect(() => {
    const handleResize = debounce(
      () =>
        setScreenSize({
          width: window.innerWidth,
          height: window.innerHeight,
        }),
      100
    );

    window.addEventListener("resize", handleResize);

    return () => {
      window.removeEventListener("resize", handleResize);
    };
  }, []);

  return (
    <Swiper
      initialSlide={beginAt}
      spaceBetween={50}
      slidesPerView={1}
      navigation
      pagination={{ clickable: true }}
      loop
      keyboard={{ enabled: true }}
      mousewheel
      zoom
      grabCursor
    >
      {medias.map((m) => {
        return (
          <SwiperSlide key={m.id} zoom>
            <div className="flex items-center align-middle justify-center h-screen">
              <Image
                objectFit="contain"
                className="max-h-screen"
                layout="fill"
                priority={true}
                src={m.url}
                alt={m.name}
                draggable={false}
                sizes={
                  screenSize.height > screenSize.width
                    ? "100vw" // Image will be full width in almost any cases.
                    : `${Math.ceil((screenSize.height / m.width) * m.height)}px` // Determine wanted size
                }
              />
            </div>
          </SwiperSlide>
        );
      })}
    </Swiper>
  );
};
