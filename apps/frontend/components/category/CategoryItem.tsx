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
    <div className="h-full transform overflow-hidden shadow-lg transition duration-200 ease-in-out hover:scale-105">
      <div className="mb- h-full bg-opacity-50 bg-gradient-to-r from-blue-700 to-red-700 shadow-none">
        <Link
          href={{
            pathname: "/categories/[slug]",
            query: { slug: category.slug },
          }}
        >
          {category.cover && (
            <div className="absolute top-0 h-full w-full bg-cover bg-center">
              <span className="absolute h-full w-full bg-black" />
              <Image
                style={{ filter: "brightness(80%)" }}
                className="h-full w-full object-cover"
                src={category.cover.url}
                alt={category.name}
                width={category.cover.width}
                height={category.cover.height}
                sizes={sizes(3, "container")}
                draggable={false}
              />
            </div>
          )}

          <div className="relative h-full">
            <div className="flex h-full items-center justify-center align-middle">
              <div className="text-5xl font-semibold text-white">
                <h2 className="p-8 py-32 text-center">{category.name}</h2>
              </div>
            </div>
          </div>
        </Link>
      </div>
    </div>
  );
};

export default CategoryItem;
