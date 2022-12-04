import { Dialog } from "@headlessui/react";
import { motion } from "framer-motion";
import { useEffect, useRef, useState } from "react";
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
  const overlayRef = useRef<HTMLDivElement>(null);
  const [direction, setDirection] = useState(0);
  const [curIndex, setCurIndex] = useState(startIndex);

  useEffect(() => {
    console.log({curIndex});
  }, [curIndex]);

  function handleClose() {
    onClose?.();
  }

  // Deprecated
  function changePhoto(newIndex: number) {
    console.log("changePhoto");
    setDirection(newIndex > curIndex ? 1 : -1);
    setCurIndex(newIndex);
  }

  const next = () => {
    if (curIndex + 1 < images.length) {
      console.log("next");
      setDirection(1);
      setCurIndex(i => i+1);
    }
  };

  const previous = () => {
    if (curIndex > 0) {
      console.log("previous");
      setDirection(-1);
      setCurIndex(i => i-1);
    }
  };

  const keydownHandler = (e: KeyboardEvent) => {
    if (e.key === "ArrowRight") {
      next();
      return;
    }
    if (e.key === "ArrowLeft") {
      previous();
      return;
    }
  };

  useEffect(() => {
    window.addEventListener("keydown", keydownHandler);

    return () => {
      window.removeEventListener("keydown", keydownHandler);
    };
  }, []);

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
        changePhotoId={changePhoto}
        closeModal={handleClose}
        navigation={true}
      />
    </Dialog>
  );
}
