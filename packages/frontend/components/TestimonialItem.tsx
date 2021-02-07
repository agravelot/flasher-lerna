import { FunctionComponent } from "react";
import Testimonial from "~/models/testimonial";
import Avatar from "~/components/Avatar";
import { Review } from "schema-dts";
import { configuration } from "~/utils/configuration";

interface Props {
  testimonial: Testimonial;
}

const TestimonialItem: FunctionComponent<Props> = ({ testimonial }: Props) => {
  const jsonLd: Review = {
    "@type": "Review",
    itemReviewed: {
      "@type": "CreativeWork",
      name: "Shooting photo",
      url: configuration.appUrl,
      image: "/icon-512x512.png",
    },
    author: {
      "@type": "Person",
      name: testimonial.name,
    },
    datePublished: String(testimonial.created_at),
    reviewBody: testimonial.body,
    name: testimonial.body,
    reviewRating: {
      "@type": "Rating",
      ratingValue: 5,
    },
  };

  return (
    <div className="h-full text-center">
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{
          __html: JSON.stringify(jsonLd),
        }}
      ></script>
      <div className="flex justify-center mb-8">
        <Avatar name={testimonial.name} className="mb-8 content-center" />
      </div>
      <p className="leading-relaxed">{testimonial.body}</p>
      <span className="inline-block h-1 w-10 rounded bg-gradient-to-r from-blue-700 to-red-700 mt-6 mb-4"></span>
      <p className="text-gray-700 font-medium title-font tracking-wider text-sm">
        {testimonial.name}
      </p>
    </div>
  );
};

export default TestimonialItem;
