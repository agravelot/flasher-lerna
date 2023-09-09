import Link from "next/link";
import { FC } from "react";
import { ChevronRightIcon } from "@heroicons/react/24/outline";
import { BreadcrumbJsonLd } from "next-seo";
import { ItemListElements } from "next-seo/lib/types";

interface Level {
  name: string;
  path: string;
}
interface Props {
  levels: Level[];
}

export const Breadcrumb: FC<Props> = ({ levels }) => {
  return (
    <nav className="flex" aria-label="Breadcrumb">
      <BreadcrumbJsonLd
        itemListElements={levels.map(
          (l, i): ItemListElements => ({
            name: l.name,
            item: l.path,
            position: i + 1,
          })
        )}
      />
      <ol className="inline-flex items-center space-x-1">
        {levels.map((l, i) => {
          const isFirstItem = i === 0;
          const isLastItem = levels.length - 1 === i;
          return (
            <li key={i} aria-current={isLastItem ? "page" : undefined}>
              <div className="flex items-center">
                {!isFirstItem && (
                  <ChevronRightIcon className="h-4 w-4 text-gray-200" />
                )}
                <Link
                  href={l.path}
                  className={`py-2 first:pl-4 text-xs font-medium ${
                    isLastItem
                      ? "text-white hover:text-gray-400"
                      : "text-gray-200 hover:text-gray-400"
                  }`}
                >
                  {l.name}
                </Link>
              </div>
            </li>
          );
        })}
      </ol>
    </nav>
  );
};
