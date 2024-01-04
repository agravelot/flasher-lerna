import { FC, useEffect } from "react";
import { SearchStatus, useSearch } from "../../contexts/AppContext";
import dynamic from "next/dynamic";
import { Transition } from "@headlessui/react";

const LazySearch = dynamic(() => import("./Search"), {
  ssr: false,
});

export const SearchModal: FC = () => {
  const { status, googleSearch, close } = useSearch();

  const handleEscape = (e: KeyboardEvent) => {
    if (e.key === "Esc" || e.key === "Escape") {
      close();
    }
  };

  useEffect(() => {
    document.addEventListener("keydown", handleEscape);
    return () => {
      document.removeEventListener("keydown", handleEscape);
    };
  });

  return (
    <Transition
      className="fixed inset-0 z-50 overflow-y-auto"
      appear={true} // Transitioning on initial mount
      show={status === SearchStatus.Opened}
      enter="transition ease-out duration-100"
      enterFrom="transform opacity-0 scale-95"
      enterTo="transform opacity-100 scale-100"
      leave="transition ease-in duration-100"
      leaveFrom="transform opacity-100 scale-100"
      leaveTo="transform opacity-0 scale-95"
    >
      <div className="relative flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div
          className="absolute inset-0 transition-opacity"
          style={{ background: "rgba(28, 33, 41, 0.97)" }}
        />
        <div
          className="absolute z-50 h-10 w-10 rounded-full bg-gray-600 text-white"
          style={{ top: "2.5rem", right: "3.125rem" }}
        >
          <button
            role="menuitem"
            tabIndex={0}
            className="h-10 w-10 p-1 focus:outline-none"
            onClick={() => close()}
          >
            <i>
              <svg viewBox="0 0 20 20" fill="currentColor">
                <path
                  fillRule="evenodd"
                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                  clipRule="evenodd"
                ></path>
              </svg>
            </i>
          </button>
        </div>
        {/* This element is to trick the browser into centering the modal contents. */}
        <span className="hidden sm:inline-block sm:h-screen sm:align-middle"></span>
        &#8203;
        <div
          className="inline-block transform overflow-hidden rounded-lg text-left align-bottom transition-all sm:my-8 sm:w-full sm:align-middle"
          role="dialog"
          aria-modal="true"
          aria-labelledby="modal-headline"
        >
          <div className="container mx-auto">
            <LazySearch search={googleSearch} />
          </div>
        </div>
      </div>
    </Transition>
  );
};
