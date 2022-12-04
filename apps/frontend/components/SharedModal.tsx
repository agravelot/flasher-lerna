import {
    ArrowDownTrayIcon,
    ArrowTopRightOnSquareIcon,
    ArrowUturnLeftIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    XMarkIcon,
} from "@heroicons/react/24/outline";
import { AnimatePresence, motion, MotionConfig } from "framer-motion";
import Image from "next/image";
import {useEffect, useState} from "react";
import { useSwipeable } from "react-swipeable";
import { variants } from "../utils/animationVariants";
import type { SharedModalProps } from "../utils/types";
import {debounce} from "lodash-es";
import downloadPhoto from "../utils/downloadPhoto";

export default function SharedModal({
                                        index,
                                        images,
                                        changePhotoId,
                                        closeModal,
                                        navigation,
                                        currentPhoto,
                                        direction,
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

    const handlers = useSwipeable({
        onSwipedLeft: () => changePhotoId(index + 1),
        onSwipedRight: () => changePhotoId(index - 1),
        trackMouse: true,
    });

    const currentImage = images ? images[index] : currentPhoto;

    if (!currentImage ) {
        return null;
    }

    const aspectRatio = currentImage.width > currentImage.height ? "aspect-[3/2]" : "aspect-[2/3]";

    return (
        <MotionConfig
            transition={{
                x: { type: "spring", stiffness: 300, damping: 30 },
                opacity: { duration: 0.2 },
            }}
        >
            <div
                className={`relative z-50 flex w-full max-w-7xl items-center wide:h-full xl:taller-than-854:h-auto ${aspectRatio}`}
                {...handlers}
            >
                {/* Main image */}
                <div className="w-full overflow-hidden">
                    <div className={`relative flex items-center justify-center ${aspectRatio}`}>
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
                                <Image
                                    src={currentImage.url}
                                    width={currentImage.width}
                                    height={currentImage.height}
                                    sizes={
                                        screenSize.height > screenSize.width || currentImage.width > currentImage.height
                                            ? "100vw" // Image will be full width on mobile and landscape images.
                                            : `${Math.ceil((screenSize.height / currentImage.width) * currentImage.height)}px` // Determine wanted size
                                    }
                                    draggable={false}
                                    priority
                                    alt="TODO"
                                    onLoadingComplete={() => setLoaded(true)}
                                    style={{
                                        objectFit: "contain",
                                        height: "calc(100vh - 100px)",
                                        marginBottom: "100px"
                                    }}
                                />
                            </motion.div>
                        </AnimatePresence>
                    </div>
                </div>

                {/* Buttons + bottom nav bar */}
                <div className="absolute inset-0 mx-auto flex max-w-7xl items-center justify-center">
                    {/* Buttons */}
                    {loaded && (
                        <div className={"relative aspect-[3/2] max-h-full w-full"}>
                            {navigation && (
                                <>
                                    {index > 0 && (
                                        <button
                                            className="absolute left-3 top-[calc(50%-16px)] rounded-full bg-black/50 p-3 text-white/75 backdrop-blur-lg transition hover:bg-black/75 hover:text-white focus:outline-none"
                                            style={{ transform: "translate3d(0, 0, 0)" }}
                                            onClick={() => changePhotoId(index - 1)}
                                        >
                                            <ChevronLeftIcon className="h-6 w-6" />
                                        </button>
                                    )}
                                    {images && index + 1 < images.length && (
                                        <button
                                            className="absolute right-3 top-[calc(50%-16px)] rounded-full bg-black/50 p-3 text-white/75 backdrop-blur-lg transition hover:bg-black/75 hover:text-white focus:outline-none"
                                            style={{ transform: "translate3d(0, 0, 0)" }}
                                            onClick={() => changePhotoId(index + 1)}
                                        >
                                            <ChevronRightIcon className="h-6 w-6" />
                                        </button>
                                    )}
                                </>
                            )}
                            <div className="absolute top-0 right-0 flex items-center gap-2 p-3 text-white">
                                {navigation ? (
                                    <a
                                        href={currentImage.url}
                                        className="rounded-full bg-black/50 p-2 text-white/75 backdrop-blur-lg transition hover:bg-black/75 hover:text-white"
                                        target="_blank"
                                        title="Open fullsize version"
                                        rel="noreferrer"
                                    >
                                        <ArrowTopRightOnSquareIcon className="h-5 w-5" />
                                    </a>
                                ) : (
                                    <a
                                        href={`https://twitter.com/intent/tweet?text=Check%20out%20this%20pic%20from%20Next.js%20Conf!%0A%0Ahttps://nextjsconf-pics.vercel.app/p/${index}`}
                                        className="rounded-full bg-black/50 p-2 text-white/75 backdrop-blur-lg transition hover:bg-black/75 hover:text-white"
                                        target="_blank"
                                        title="Open fullsize version"
                                        rel="noreferrer"
                                    >
                                        {/*<Twitter className="h-5 w-5" />*/}
                                    </a>
                                )}
                                <button
                                    onClick={() =>
                                    {
                                        if (!currentImage) {
                                            throw new Error("current photo is not defined");
                                        }
                                        downloadPhoto(currentImage.url);
                                    }
                                    }
                                    className="rounded-full bg-black/50 p-2 text-white/75 backdrop-blur-lg transition hover:bg-black/75 hover:text-white"
                                    title="Download fullsize version"
                                >
                                    <ArrowDownTrayIcon className="h-5 w-5" />
                                </button>
                            </div>
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
                                    {filteredImages?.map(({ url, id , height, width}, i) => (
                                        <motion.button
                                            initial={{
                                                width: "0%",
                                                x: `${Math.max((index - 1) * -100, 15 * -100)}%`,
                                            }}
                                            animate={{
                                                scale: i === index ? 1.25 : 1,
                                                width: "100%",
                                                x: `${Math.max(index * -100, 15 * -100)}%`,
                                            }}
                                            exit={{ width: "0%" }}
                                            onClick={() => changePhotoId(id)}
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
                                                height={height  }
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