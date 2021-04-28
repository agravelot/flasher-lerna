import React, { FunctionComponent } from "react";
import Link from "next/link";
import Image from "next/image";
import { sizes } from "../../utils/util";
import { Category } from "@flasher/models";

interface Props {
  category: Category;
}

const CategoryItem: FunctionComponent<Props> = ({ category }: Props) => {
  return (
    <div className="rounded-xl overflow-hidden shadow-lg transform hover:scale-105 transition duration-200 ease-in-out h-full">
      <div
        className="bg-gradient-to-r from-blue-700 to-red-700 mb- shadow-none bg-opacity-50 h-full"
        style={{
          WebkitBackgroundClip: "text",
        }}
      >
        <Link
          href={{
            pathname: "/categories/[slug]",
            query: { slug: category.slug },
          }}
        >
          <a>
            {category.cover && (
              <div className="absolute top-0 w-full h-full bg-center bg-cover">
                <span className="w-full h-full absolute opacity-50 bg-black" />
                <Image
                  className="opacity-75 h-full"
                  src={category.cover.url}
                  objectFit="cover"
                  layout="fill"
                  alt={category.name}
                  sizes={sizes(3, "container")}
                  draggable={false}
                />
              </div>
            )}

            <div className="relative h-full">
              <div className="flex align-middle justify-center items-center h-full">
                <div>
                  <Link
                    href={{
                      pathname: "/categories/[slug]",
                      query: { slug: category.slug },
                    }}
                  >
                    <a
                      className="text-white font-semibold text-5xl"
                      tabIndex={0}
                    >
                      <h2 className="p-8 py-32 text-center">{category.name}</h2>
                    </a>
                  </Link>
                </div>
              </div>
            </div>
          </a>
        </Link>
      </div>
    </div>
  );
};

export default CategoryItem;
