import { FunctionComponent } from "react";
import Image from "next/image";

interface Props {
  name: string;
  className?: string;
  roundClasses?: string;
  src?: string | null;
  color?: string;
  size?: 16 | 24;
  border?: boolean;
}

const Avatar: FunctionComponent<Props> = ({
  name,
  src,
  className = "",
  roundClasses = "rounded-full",
  color = "bg-gradient-to-r from-blue-700 to-red-700",
  size = 16,
  border = false,
}: Props) => {
  const avatarText = (name: string): string => {
    return name?.charAt(0);
  };

  return (
    <div className={size === 16 ? "h-16 w-16" : "h-24 w-24"}>
      <div
        className={`flex items-center justify-center uppercase text-white font-medium shadow mx-auto sm:mx-0 sm:flex-shrink-0 rounded-full text-xl w-full h-full ${color} ${
          border ? "border-white border-2" : null
        }`}
      >
        {src && (
          <Image
            draggable={false}
            className={`w-full h-full overflow-hidden ${roundClasses} ${className}`}
            src={src}
            height={96}
            width={96}
          />
        )}
        {!src && (
          <div className="flex content-center items-center text-center flex-wrap h-full">
            <div>{avatarText(name)}</div>
          </div>
        )}
      </div>
    </div>
  );
};

export default Avatar;
