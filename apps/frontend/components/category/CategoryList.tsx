import { Category } from "@flasher/models";
import React, { FunctionComponent } from "react";
import CategoryItem from "./CategoryItem";

type Props = {
  categories: Category[];
};

const CategoryList: FunctionComponent<Props> = ({ categories }: Props) => (
  <div className="container mx-auto py-8">
    <div className="flex flex-wrap md:-mx-3">
      {categories.map((category) => (
        <div key={category.id} className="w-full p-3 md:w-1/3">
          <CategoryItem category={category} />
        </div>
      ))}
    </div>
  </div>
);

export default CategoryList;
