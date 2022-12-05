import {
  ArrowUturnLeftIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  XMarkIcon,
} from "@heroicons/react/24/outline";
import { AnimatePresence, motion, MotionConfig } from "framer-motion";
import Image from "next/image";
import { useEffect, useState } from "react";
import { useSwipeable } from "react-swipeable";
import { variants } from "../utils/animationVariants";
import type { ImageProps, SharedModalProps } from "../utils/types";
import { debounce } from "lodash-es";

export default function SharedModal({
  index,
  images,
  closeModal,
  navigation,
  currentPhoto,
  direction,
  next,
  previous,
  setIndex,
}: SharedModalProps) {
  const [loaded, setLoaded] = useState(false);

  // const filteredImages = images?.filter((img: ImageProps) =>
  //     range(index - 15, index + 15).includes(img.id)
  // );

  const filteredImages = images;

  const [screenSize, setScreenSize] = useState({
    width: window.innerWidth,
    height: window.innerHeight,
  });

  useEffect(() => {
    setLoaded(false);
  }, [index]);

  // Will force rerender on screen resize, allow dynamic `sizes` to work
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

  const handlers = useSwipeable({
    onSwipedLeft: () => next(),
    onSwipedRight: () => previous(),
    trackMouse: true,
  });

  const previousMedia = images?.[index - 1];
  const currentImage = images ? images[index] : currentPhoto;
  const nextMedia = images?.[index + 1];

  const getSizes = (media: ImageProps) =>
    screenSize.height > screenSize.width || media.width > media.height
      ? "100vw" // Image will be full width on mobile and landscape images.
      : `${Math.ceil((screenSize.height / media.width) * media.height)}px`; // Determine wanted size

  if (!currentImage) {
    return null;
  }

  const aspectRatio =
    currentImage.width > currentImage.height ? "aspect-[3/2]" : "aspect-[2/3]";

  const preloadImage = (media: ImageProps) => {
    return (
      <Image
        src={media.url}
        width={media.width}
        height={media.height}
        sizes={getSizes(media)}
        draggable={false}
        priority
        alt="TODO"
        style={{
          display: "none",
        }}
      />
    );
  };

  return (
    <MotionConfig
      transition={{
        x: { type: "spring", stiffness: 300, damping: 30 },
        opacity: { duration: 0.2 },
      }}
    >
      <div
        className={`relative z-50 flex w-full items-center wide:h-full xl:taller-than-854:h-auto ${aspectRatio}`}
        {...handlers}
      >
        {/* Main image */}
        <div className="w-full overflow-hidden">
          <div
            className={`relative flex items-center justify-center ${aspectRatio}`}
          >
            <AnimatePresence initial={false} custom={direction}>
              <motion.div
                key={index}
                custom={direction}
                variants={variants}
                initial="enter"
                animate="center"
                exit="exit"
                className="absolute"
              >
                <div className="relative">
                  {!loaded && (
                    <svg
                      className="absolute inset-1/2"
                      width="38"
                      height="38"
                      viewBox="0 0 38 38"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <defs>
                        <linearGradient
                          x1="8.042%"
                          y1="0%"
                          x2="65.682%"
                          y2="23.865%"
                          id="a"
                        >
                          <stop stopColor="#fff" stopOpacity="0" offset="0%" />
                          <stop
                            stopColor="#fff"
                            stopOpacity=".631"
                            offset="63.146%"
                          />
                          <stop stopColor="#fff" offset="100%" />
                        </linearGradient>
                      </defs>
                      <g fill="none" fillRule="evenodd">
                        <g transform="translate(1 1)">
                          <path
                            d="M36 18c0-9.94-8.06-18-18-18"
                            id="Oval-2"
                            stroke="url(#a)"
                            strokeWidth="2"
                          >
                            <animateTransform
                              attributeName="transform"
                              type="rotate"
                              from="0 18 18"
                              to="360 18 18"
                              dur="0.9s"
                              repeatCount="indefinite"
                            />
                          </path>
                          <circle fill="#fff" cx="36" cy="18" r="1">
                            <animateTransform
                              attributeName="transform"
                              type="rotate"
                              from="0 18 18"
                              to="360 18 18"
                              dur="0.9s"
                              repeatCount="indefinite"
                            />
                          </circle>
                        </g>
                      </g>
                    </svg>
                  )}

                  {previousMedia && preloadImage(previousMedia)}

                  <Image
                    src={currentImage.url}
                    width={currentImage.width}
                    height={currentImage.height}
                    sizes={getSizes(currentImage)}
                    draggable={false}
                    priority
                    alt="TODO"
                    onLoadingComplete={() => setLoaded(true)}
                    style={{
                      objectFit: "contain",
                      height: "calc(100vh - 100px)",
                      marginBottom: "100px",
                    }}
                  />

                  {nextMedia && preloadImage(nextMedia)}
                </div>
              </motion.div>
            </AnimatePresence>
          </div>
        </div>

        {/* Buttons + bottom nav bar */}
        <div className="absolute inset-0 mx-auto flex items-center justify-center">
          {/* Buttons */}
          {loaded && (
            <div className={"relative h-screen w-full"}>
              {navigation && (
                <>
                  {index > 0 && (
                    <button
                      className="absolute left-3 top-[calc(50%-16px)] rounded-full bg-black/50 p-3 text-white/75 backdrop-blur-lg transition hover:bg-black/75 hover:text-white focus:outline-none"
                      style={{ transform: "translate3d(0, 0, 0)" }}
                      onClick={() => previous()}
                    >
                      <ChevronLeftIcon className="h-6 w-6" />
                    </button>
                  )}
                  {images && index + 1 < images.length && (
                    <button
                      className="absolute right-3 top-[calc(50%-16px)] rounded-full bg-black/50 p-3 text-white/75 backdrop-blur-lg transition hover:bg-black/75 hover:text-white focus:outline-none"
                      style={{ transform: "translate3d(0, 0, 0)" }}
                      onClick={() => next()}
                    >
                      <ChevronRightIcon className="h-6 w-6" />
                    </button>
                  )}
                </>
              )}
              {/*<div className="absolute top-0 right-0 flex items-center gap-2 p-3 text-white">*/}
              {/*  {navigation ? (*/}
              {/*    <a*/}
              {/*      href={currentImage.url}*/}
              {/*      className="rounded-full bg-black/50 p-2 text-white/75 backdrop-blur-lg transition hover:bg-black/75 hover:text-white"*/}
              {/*      target="_blank"*/}
              {/*      title="Open fullsize version"*/}
              {/*      rel="noreferrer"*/}
              {/*    >*/}
              {/*      <ArrowTopRightOnSquareIcon className="h-5 w-5" />*/}
              {/*    </a>*/}
              {/*  ) : (*/}
              {/*    <a*/}
              {/*      href={`https://twitter.com/intent/tweet?text=Check%20out%20this%20pic%20from%20Next.js%20Conf!%0A%0Ahttps://nextjsconf-pics.vercel.app/p/${index}`}*/}
              {/*      className="rounded-full bg-black/50 p-2 text-white/75 backdrop-blur-lg transition hover:bg-black/75 hover:text-white"*/}
              {/*      target="_blank"*/}
              {/*      title="Open fullsize version"*/}
              {/*      rel="noreferrer"*/}
              {/*    >*/}
              {/*      /!*<Twitter className="h-5 w-5" />*!/*/}
              {/*    </a>*/}
              {/*  )}*/}
              {/*  <button*/}
              {/*    onClick={() => {*/}
              {/*      if (!currentImage) {*/}
              {/*        throw new Error("current photo is not defined");*/}
              {/*      }*/}
              {/*      downloadPhoto(currentImage.url);*/}
              {/*    }}*/}
              {/*    className="rounded-full bg-black/50 p-2 text-white/75 backdrop-blur-lg transition hover:bg-black/75 hover:text-white"*/}
              {/*    title="Download fullsize version"*/}
              {/*  >*/}
              {/*    <ArrowDownTrayIcon className="h-5 w-5" />*/}
              {/*  </button>*/}
              {/*</div>*/}
              <div className="absolute top-0 left-0 flex items-center gap-2 p-3 text-white">
                <button
                  onClick={() => closeModal()}
                  className="rounded-full bg-black/50 p-2 text-white/75 backdrop-blur-lg transition hover:bg-black/75 hover:text-white"
                >
                  {navigation ? (
                    <XMarkIcon className="h-5 w-5" />
                  ) : (
                    <ArrowUturnLeftIcon className="h-5 w-5" />
                  )}
                </button>
              </div>
            </div>
          )}
          {/* Bottom Nav bar */}
          {navigation && (
            <div className="fixed inset-x-0 bottom-0 z-40 overflow-hidden bg-gradient-to-b from-black/0 to-black/60">
              <motion.div
                initial={false}
                className={"mx-auto mt-6 mb-6 flex aspect-[3/2] h-14"}
              >
                <AnimatePresence initial={false}>
                  {filteredImages?.map(({ url, id, height, width }, i) => (
                    <motion.button
                      initial={{
                        width: "0%",
                        x: `${Math.max((index - 1) * -100, 15 * -100)}%`,
                      }}
                      animate={{
                        scale: i === index ? 1.25 : 1,
                        width: "100%",
                        x: `${index * -100}%`,
                      }}
                      exit={{ width: "0%" }}
                      onClick={() => setIndex?.(i)}
                      key={id}
                      className={`${
                        i === index
                          ? "z-20 rounded-md shadow shadow-black/50"
                          : "z-10"
                      } ${i === 0 ? "rounded-l-md" : ""} ${
                        images && i === images.length - 1 ? "rounded-r-md" : ""
                      } relative inline-block w-full shrink-0 transform-gpu overflow-hidden focus:outline-none`}
                    >
                      <Image
                        alt="small photos on the bottom"
                        width={width}
                        height={height}
                        className={`${
                          i === index
                            ? "brightness-110 hover:brightness-110"
                            : "brightness-50 contrast-125 hover:brightness-75"
                        } h-full transform object-contain transition`}
                        src={url}
                        sizes={"128px"}
                      />
                    </motion.button>
                  ))}
                </AnimatePresence>
              </motion.div>
            </div>
          )}
        </div>
      </div>
    </MotionConfig>
  );
}
