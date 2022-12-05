import { Dialog } from "@headlessui/react";
import { motion } from "framer-motion";
import { useCallback, useEffect, useRef, useState } from "react";
import type { ImageProps } from "../utils/types";
import SharedModal from "./SharedModal";

export default function Modal({
  images,
  onClose,
  startIndex,
  isOpen,
}: {
  images: ImageProps[];
  onClose?: () => void;
  startIndex: number;
  isOpen: boolean;
}) {
  const overlayRef = useRef<HTMLDivElement>(null);
  const [direction, setDirection] = useState(0);
  const [curIndex, setCurIndex] = useState(startIndex);

  function handleClose() {
    onClose?.();
  }

  useEffect(() => {
    if (curIndex < 0) {
      setCurIndex(0);
    } else if (curIndex > images.length - 1) {
      setCurIndex(images.length - 1);
    }
  }, [curIndex, setCurIndex, images]);

  const goTo = useCallback(
    (index: number) => {
      setDirection(curIndex < index ? 1 : -1);
      setCurIndex(index);
    },
    [curIndex]
  );

  const next = useCallback(() => {
    setDirection(1);
    setCurIndex((i) => i + 1);
  }, [setDirection, setCurIndex]);

  const previous = useCallback(() => {
    setDirection(-1);
    setCurIndex((i) => i - 1);
  }, [setDirection, setCurIndex]);

  const keydownHandler = useCallback(
    (e: KeyboardEvent) => {
      if (e.key === "ArrowRight") {
        next();
        return;
      }
      if (e.key === "ArrowLeft") {
        previous();
        return;
      }
    },
    [next, previous]
  );

  useEffect(() => {
    window.addEventListener("keydown", keydownHandler);

    return () => {
      window.removeEventListener("keydown", keydownHandler);
    };
  }, [keydownHandler]);

  return (
    <Dialog
      // static
      open={isOpen}
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
        next={next}
        previous={previous}
        index={curIndex}
        direction={direction}
        images={images}
        closeModal={handleClose}
        navigation={true}
        setIndex={goTo}
      />
    </Dialog>
  );
}
