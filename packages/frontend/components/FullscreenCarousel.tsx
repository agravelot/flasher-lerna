import { FunctionComponent, useEffect } from "react";
import { Props as CarouselProps } from "~/components/SwiperCarousel";
import { SwiperCarousel } from "~/components/SwiperCarousel";

export type Props = { openned: boolean; close: () => void } & CarouselProps;

const FullscreenCarousel: FunctionComponent<Props> = ({
  medias,
  beginAt,
  openned,
  close,
}: Props) => {
  const handleEscape = (e: KeyboardEvent) => {
    if (e.key === "Esc" || e.key === "Escape") {
      close();
    }
  };

  useEffect(() => {
    document.addEventListener("keydown", handleEscape);
    return () => document.removeEventListener("keydown", handleEscape);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  if (openned) {
    return (
      <div className="h-full w-full bg-black fixed z-50 top-0 left-0">
        <div
          className="fixed text-white z-10"
          style={{ top: "20px", right: "20px" }}
        >
          <button
            className="w-6 h-6 focus:outline-none"
            onClick={() => close()}
          >
            <i>
              <svg
                viewBox="0 0 20 20"
                fill="currentColor"
                className="x w-6 h-6"
              >
                <path
                  fillRule="evenodd"
                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                  clipRule="evenodd"
                  style={{
                    stroke: "#646464",
                    strokeWidth: "0.8px",
                    strokeLinejoin: "round",
                  }}
                />
              </svg>
            </i>
          </button>
        </div>
        <SwiperCarousel medias={medias} beginAt={beginAt} />
      </div>
    );
  }

  return <></>;
};

export default FullscreenCarousel;
