import { FunctionComponent } from "react";
import Separator from "./Separator";
import ContactForm from "./ContactForm";

export const ContactSection: FunctionComponent = () => {
  return (
    <section className="relative bg-gray-900 pb-20">
      <Separator separatorClass="text-gray-900" position="top" />
      <div id="contact" className="container mx-auto px-4 py-12 lg:pt-24">
        <div className="flex flex-wrap justify-center text-center">
          <div className="w-full px-4 lg:w-6/12">
            <h2 className="text-4xl font-semibold text-white">
              Pourquoi pas vous ?
            </h2>
            <p className="mt-4 mb-4 text-lg leading-relaxed text-gray-200">
              D'autres avant vous ont fait le grand saut, pourquoi pas vous ? En couple, en famille, seul(e), faites vous plaisir. Chaque prestations est unique, alors, faisons ce petit bout de chemin ensemble, cela vous tente ?
            </p>
            <div className="flex text-white">
              <div className="mx-auto flex">
                <svg
                  className="my-auto mr-4 h-6 w-6"
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
                  <a className="text-xl font-semibold" href="tel:+33766648588">
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
          <div className="w-full px-4 lg:w-6/12">
            <div className="relative mb-6 flex w-full min-w-0 flex-col break-words rounded-lg bg-gray-300 shadow-lg">
              <div className="flex-auto p-5 lg:p-10">
                <div className="flex justify-center">
                  <h3 className="mb-4 text-xl font-medium">
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
