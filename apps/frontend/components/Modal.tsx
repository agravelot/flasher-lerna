import { Dialog } from "@headlessui/react";
import { motion } from "framer-motion";
import {useEffect, useRef, useState} from "react";
import useKeypress from "react-use-keypress";
import type { ImageProps } from "../utils/types";
import SharedModal from "./SharedModal";

export default function Modal({
  images,
  onClose,
    startIndex,
}: {
  images: ImageProps[]
  onClose?: () => void
  startIndex: number
}) {

  console.log(images);
  const overlayRef = useRef<HTMLElement>();

  const [direction, setDirection] = useState(0);
  const [curIndex, setCurIndex] = useState(startIndex);

  useEffect(() => {
    console.log(curIndex);
  }, [curIndex]);

  function handleClose() {
    // router.push("/", undefined, { shallow: true });
    onClose?.();
  }

  function changePhotoId(newVal: number) {
    if (newVal > curIndex) {
      setDirection(1);
    } else {
      setDirection(-1);
    }
    setCurIndex(newVal);
    // router.push(
    //   {
    //     query: { photoId: newVal },
    //   },
    //   `/p/${newVal}`,
    //   { shallow: true }
    // );
  }

  useKeypress("ArrowRight", () => {
    if (curIndex + 1 < images.length) {
      changePhotoId(curIndex + 1);
    }
  });

  useKeypress("ArrowLeft", () => {
    if (curIndex > 0) {
      changePhotoId(curIndex - 1);
    }
  });

  // if (!overlayRef || !overlayRef.current) {
  //   return  null;
  // }

  return (
    <Dialog
      static
      open={true}
      onClose={handleClose}
      initialFocus={overlayRef}
      className="fixed inset-0 z-10 flex items-center justify-center"
    >
      <Dialog.Overlay
        ref={overlayRef}
        as={motion.div}
        key="backdrop"
        className="fixed inset-0 z-30 bg-black/70 backdrop-blur-2xl"
        initial={{ opacity: 0 }}
        animate={{ opacity: 1 }}
      />
      <SharedModal
        index={curIndex}
        direction={direction}
        images={images}
        changePhotoId={changePhotoId}
        closeModal={handleClose}
        navigation={true}
      />
    </Dialog>
  );
}
