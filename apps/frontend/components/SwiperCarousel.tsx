import { FC, useEffect, useState } from "react";
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
import "swiper/css/bundle";
import { Media } from "@flasher/models";
import { debounce } from "lodash-es";

export type Props = {
  medias: Media[];
  beginAt: number;
};

export const SwiperCarousel: FC<Props> = ({ medias, beginAt }: Props) => {
  SwiperCore.use([
    Navigation,
    Pagination,
    Scrollbar,
    A11y,
    Keyboard,
    Mousewheel,
  ]);

  const [currentIndex, setCurrentIndex] = useState(beginAt);

  const [screenSize, setScreenSize] = useState({
    width: window.innerWidth,
    height: window.innerHeight,
  });

  // Will force rerender on screen resize, allow dynamic `sizes`to wark
  useEffect(() => {
    const handleResize = debounce(() => {
      setScreenSize({
        width: window.innerWidth,
        height: window.innerHeight,
      });
    }, 100);

    window.addEventListener("resize", handleResize);

    return () => {
      window.removeEventListener("resize", handleResize);
    };
  }, []);

  const isNearbyOfCurrentIndex = (index: number): boolean =>
    currentIndex + 1 === index ||
    currentIndex === index ||
    currentIndex - 1 === index;

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
      onSlideChangeTransitionEnd={
        (swiper) => setCurrentIndex(swiper.realIndex) // Force re-render to loas next/previous images
      }
    >
      {medias.map((m, index) => {
        return (
          <SwiperSlide key={m.id} zoom>
            <div className="flex h-screen items-center justify-center align-middle">
              <Image
                className="max-h-screen object-contain"
                priority={isNearbyOfCurrentIndex(index)}
                src={m.url}
                alt={m.name}
                draggable={false}
                width={m.width}
                height={m.height}
                sizes={
                  screenSize.height > screenSize.width || m.width > m.height
                    ? "100vw" // Image will be full width on mobile and landscape images.
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
