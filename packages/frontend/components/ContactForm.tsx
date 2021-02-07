import { FunctionComponent, useState } from "react";
import { api, HttpRequestError } from "~/utils/api";
import { Formik, Field, Form, FormikHelpers, ErrorMessage } from "formik";

// interface ContactError {
//   errors: { email: string[]; name: string[]; message: string[] };
// }

interface ContactFormContent {
  name: string;
  email: string;
  message: string;
}

const initialValues: ContactFormContent = {
  name: "",
  email: "",
  message: "",
};

const ContactForm: FunctionComponent = () => {
  const [isCompleted, setIsCompleted] = useState(false);

  const onSubmit = (
    { name, email, message }: ContactFormContent,
    { resetForm, setErrors }: FormikHelpers<ContactFormContent>
  ) => {
    api("/contact", {
      method: "POST",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        name,
        email,
        message,
      }),
    })
      .then((res) => res.json())
      .then(() => {
        resetForm();
        setIsCompleted(true);
      })
      .catch(async (e: HttpRequestError) => {
        if (e.response.status === 422) {
          const data = await e.response.json();
          setErrors(data.errors);
          return;
        }

        throw e;
      });
  };

  return (
    <Formik initialValues={initialValues} onSubmit={onSubmit}>
      {({ isSubmitting, isValid }) => (
        <Form>
          <div className="relative w-full mb-3">
            <label
              className="block uppercase text-gray-700 text-xs font-bold mb-2"
              htmlFor="contact_name"
            >
              Nom
            </label>
            <Field
              name="name"
              type="text"
              id="contact_name"
              required={true}
              autoComplete={"true"}
              className="px-3 py-3 placeholder-gray-400 text-gray-700 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full"
              placeholder="Nom ou pseudonyme"
            />

            <ErrorMessage className="text-red-800 text-xs italic" name="name">
              {(msg) => (
                <span className="text-red-800 text-xs italic">{msg}</span>
              )}
            </ErrorMessage>
          </div>
          <div className="relative w-full mb-3">
            <label
              className="block uppercase text-gray-700 text-xs font-bold mb-2"
              htmlFor="contact_email"
            >
              Email
            </label>
            <Field
              name="email"
              type="email"
              id="contact_email"
              required={true}
              autoComplete={"true"}
              className="px-3 py-3 placeholder-gray-400 text-gray-700 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full"
              placeholder="email@example.com"
              style={{ transition: "all 0.15s ease 0s" }}
            />

            <ErrorMessage className="text-red-800 text-xs italic" name="email">
              {(msg) => (
                <span className="text-red-800 text-xs italic">{msg}</span>
              )}
            </ErrorMessage>
          </div>
          <div className="relative w-full mb-3">
            <label
              className="block uppercase text-gray-700 text-xs font-bold mb-2"
              htmlFor="contact_message"
            >
              Message
            </label>
            <Field
              component="textarea"
              name="message"
              type="text"
              id="contact_message"
              required={true}
              rows={4}
              cols={80}
              className="px-3 py-3 placeholder-gray-400 text-gray-700 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full"
              placeholder="Ecrivez votre message..."
            />

            <ErrorMessage
              className="text-red-800 text-xs italic"
              name="message"
            >
              {(msg) => (
                <span className="text-red-800 text-xs italic">{msg}</span>
              )}
            </ErrorMessage>
          </div>
          <div className="text-center mt-6">
            <button
              className={`inline-flex bg-gradient-to-r from-blue-700 to-red-700 hover:from-pink-500 hover:to-orange-500 text-white active:bg-gray-700 text-sm font-semibold px-12 py-3 rounded-lg shadow hover:shadow-lg ${
                (isSubmitting && isValid) || isCompleted
                  ? "cursor-not-allowed"
                  : null
              }`}
              type="submit"
              style={{ transition: "all 0.15s ease 0s" }}
            >
              {isSubmitting && isValid && (
                <svg
                  className="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <circle
                    className="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    strokeWidth="4"
                  ></circle>
                  <path
                    className="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                  ></path>
                </svg>
              )}
              {isCompleted && (
                <svg
                  className="-ml-1 mr-3 h-5 w-5 text-white"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                >
                  <path
                    fillRule="evenodd"
                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                    clipRule="evenodd"
                  />
                </svg>
              )}
              {(isCompleted == false && isSubmitting === false) ||
                (isValid === false && (
                  <svg
                    className="-ml-1 mr-3 h-5 w-5 text-white"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      strokeLinecap="round"
                      strokeLinejoin="round"
                      strokeWidth="2"
                      d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                    />
                  </svg>
                ))}
              <span>Envoyer</span>
            </button>
          </div>
        </Form>
      )}
    </Formik>
  );
};

export default ContactForm;
