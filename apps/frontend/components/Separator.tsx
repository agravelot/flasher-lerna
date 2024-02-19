import { FC, ReactElement } from "react";

interface Props {
  separatorClass: string;
  position: "top" | "bottom";
}

const Separator: FC<Props> = ({
  separatorClass,
  position,
}: Props): ReactElement => {
  const classes =
    position === "bottom" ? "top-auto bottom-0" : "bottom-auto top-0 -mt-20";

  return (
    <div
      className={`${classes} left-0 right-0 w-full absolute overflow-hidden`}
      style={{ height: "80px", bottom: "-1px", transform: "translateZ(0)" }}
    >
      <svg
        className="absolute bottom-0 overflow-hidden"
        xmlns="http://www.w3.org/2000/svg"
        preserveAspectRatio="none"
        version="1.1"
        viewBox="0 0 2560 100"
        x="0"
        y="0"
      >
        <polygon
          className={`${separatorClass} fill-current`}
          points="2560 0 2560 100 0 100"
        ></polygon>
      </svg>
    </div>
  );
};

export default Separator;
