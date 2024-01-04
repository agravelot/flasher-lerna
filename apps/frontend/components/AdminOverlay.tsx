import { useRouter } from "next/dist/client/router";
import { FC } from "react";

interface Props {
  path?: string;
  targetBlank?: boolean;
}

const AdminOverlay: FC<Props> = ({ path, targetBlank = true }: Props) => {
  const router = useRouter();

  return (
    <div className="fixed bottom-0 right-0 z-30 p-8">
      <a
        className="flex h-12 w-12 items-center rounded-full bg-black text-center text-white"
        target={targetBlank ? "_blank" : "_self"}
        href={`https://admin.jkanda.fr${path ?? router.asPath}`}
        rel="noreferrer"
      >
        <svg
          className="p-3"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            strokeLinecap="round"
            strokeLinejoin="round"
            strokeWidth={1}
            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
          />
        </svg>
      </a>
    </div>
  );
};

export default AdminOverlay;
