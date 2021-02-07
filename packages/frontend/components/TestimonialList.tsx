import { FunctionComponent } from "react";
import { AggregateRating } from "schema-dts";
import { configuration } from "../utils/configuration";
import TestimonialItem from "../components/TestimonialItem";
import { Testimonial } from "@flasher/models";

interface Props {
  testimonials: Testimonial[];
  appName: string;
}

export const TestimonialList: FunctionComponent<Props> = ({
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
    <>
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{
          __html: JSON.stringify(jsonLd),
        }}
      ></script>
      {testimonials.map((testimonial) => (
        <div
          className="flex-none items-center content-center w-full md:w-1/2 lg:w-1/3 px-4 mb-8"
          key={testimonial.id}
        >
          <TestimonialItem testimonial={testimonial} />
        </div>
      ))}
    </>
  );
};

export default TestimonialList;
