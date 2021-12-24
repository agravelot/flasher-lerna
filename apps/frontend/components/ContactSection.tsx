import { FunctionComponent } from "react";
import Separator from "./Separator";
import ContactForm from "./ContactForm";


export const ContactSection: FunctionComponent = () => {
  return (
    <section className="pb-20 relative bg-gray-900">
    <Separator separatorClass="text-gray-900" position="top" />
    <div id="contact" className="container mx-auto px-4 py-12 lg:pt-24">
      <div className="flex flex-wrap text-center justify-center">
        <div className="w-full lg:w-6/12 px-4">
          <h2 className="text-4xl font-semibold text-white">
            Envie de réaliser un projet ?
          </h2>
          <p className="text-lg leading-relaxed mt-4 mb-4 text-gray-200">
            Réservez votre séance photo grâce à ce formulaire de contact !
            N’hésitez pas non plus à poser vos questions si vous en avez
            grâce à celui-ci. Je vous répondrai dans les 24 heures.
          </p>
          <div className="text-white flex">
            <div className="flex mx-auto">
              <svg
                className="h-6 w-6 mr-4 my-auto"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth={2}
                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                />
              </svg>
              <div>
                <a
                  className="text-xl font-semibold"
                  href="tel:+33766648588"
                >
                  07 66 64 85 88
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div className="container mx-auto px-4">
      <div className="flex flex-wrap justify-center">
        <div className="w-full lg:w-6/12 px-4">
          <div className="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-gray-300">
            <div className="flex-auto p-5 lg:p-10">
              <div className="flex justify-center">
                <h3 className="text-xl font-medium my-4">
                  Formulaire de contact
                </h3>
              </div>
              <ContactForm />
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  );
};
