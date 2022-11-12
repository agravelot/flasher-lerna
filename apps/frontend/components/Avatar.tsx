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
        className={`mx-auto flex h-full w-full items-center justify-center rounded-full text-xl font-medium uppercase text-white shadow sm:mx-0 sm:flex-shrink-0 ${color} ${
          border ? "border-2 border-white" : null
        }`}
      >
        {src && (
          <Image
            draggable={false}
            className={`h-full w-full overflow-hidden ${roundClasses} ${className}`}
            src={src}
            height={96}
            width={96}
            alt={`Avatar de ${name}`}
          />
        )}
        {!src && (
          <div className="flex h-full flex-wrap content-center items-center text-center">
            <div>{avatarText(name)}</div>
          </div>
        )}
      </div>
    </div>
  );
};

export default Avatar;
