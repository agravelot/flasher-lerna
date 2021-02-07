import React, { FunctionComponent } from "react";
import CategoryItem from "~/components/category/CategoryItem";
import Category from "~/models/category";

type Props = {
  categories: Category[];
};

const CategoryList: FunctionComponent<Props> = ({ categories }: Props) => (
  <div className="container mx-auto py-8 -mt-24">
    <div className="flex flex-wrap md:-mx-3">
      {categories.map((category) => (
        <div key={category.id} className="w-full md:w-1/3 p-3">
          <CategoryItem category={category} />
        </div>
      ))}
    </div>
  </div>
);

export default CategoryList;
