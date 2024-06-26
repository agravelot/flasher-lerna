import { FC } from "react";
import { AggregateRating } from "schema-dts";
import { configuration } from "../utils/configuration";
import TestimonialItem from "./TestimonialItem";
import { Testimonial } from "@flasher/models";

interface Props {
  testimonials: Testimonial[];
  appName: string;
}

export const TestimonialList: FC<Props> = ({
  testimonials,
  appName,
}: Props) => {
  const jsonLd: AggregateRating = {
    "@type": "AggregateRating",
    itemReviewed: {
      "@type": "CreativeWork",
      url: configuration.appUrl,
      name: appName,
    },
    ratingValue: 5,
    bestRating: 5,
    ratingCount: testimonials.length,
  };

  return (
    <div className="relative">
      {/* <div className="absolute -top-4 -left-8 w-72 h-72 bg-blue-700 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob"></div>
      <div className="absolute top-8 -right-8 w-72 h-72 bg-pink-700 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob animation-delay-2000"></div>
      <div className="absolute -bottom-8 left-20 w-72 h-72 bg-yellow-400 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob animation-delay-4000 hidden md:block"></div> */}
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{
          __html: JSON.stringify(jsonLd),
        }}
      ></script>
      <div className="relative">
        <div className="mb-8 flex w-full flex-nowrap items-center overflow-x-auto pt-12">
          {testimonials.map((testimonial) => (
            <div
              className="w-full flex-none content-center items-center px-4 md:w-1/2 lg:w-1/3"
              key={testimonial.id}
            >
              <TestimonialItem testimonial={testimonial} />
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

export default TestimonialList;
