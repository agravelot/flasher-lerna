import { FunctionComponent } from "react";
import { api, HttpRequestError } from "@flasher/common";
import { useForm } from "react-hook-form";

interface ContactErrors {
  errors: { email?: string[]; name?: string[]; message?: string[] };
}

interface ContactFormInputs {
  name: string;
  email: string;
  message: string;
}

const ContactForm: FunctionComponent = () => {
  const {
    register,
    handleSubmit,
    reset,
    setError,
    formState: { errors, isSubmitSuccessful, isValid, isSubmitting },
  } = useForm<ContactFormInputs>();

  const onSubmit = ({ name, email, message }: ContactFormInputs) => {
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
        reset();
      })
      .catch(async (e: HttpRequestError) => {
        if (e.response.status === 422) {
          const data: ContactErrors = await e.response.json();
          if (data.errors.email) {
            setError(
              "email",
              { message: data.errors.email[0] },
              { shouldFocus: true }
            );
          }

          if (data.errors.name) {
            setError(
              "name",
              { message: data.errors.name[0] },
              { shouldFocus: true }
            );
          }

          if (data.errors.message) {
            setError(
              "message",
              { message: data.errors.message[0] },
              { shouldFocus: true }
            );
          }
        }

        throw e;
      });
  };

  return (
    <form onSubmit={handleSubmit(onSubmit)}>
      <div className="relative mb-3 w-full">
        <label
          className="mb-2 block text-xs font-bold uppercase text-gray-700"
          htmlFor="contact_name"
        >
          Nom
        </label>

        <input
          {...register("name", { required: true })}
          type="text"
          id="contact_name"
          required={true}
          autoComplete={"true"}
          className="w-full rounded bg-white px-3 py-3 text-sm text-gray-700 placeholder-gray-400 shadow focus:outline-none focus:ring"
          placeholder="Nom ou pseudonyme"
        />

        {errors.name && (
          <div className="text-xs italic text-red-800">
            <span className="text-xs italic text-red-800">
              {errors.name.message}
            </span>
          </div>
        )}
      </div>
      <div className="relative mb-3 w-full">
        <label
          className="mb-2 block text-xs font-bold uppercase text-gray-700"
          htmlFor="contact_email"
        >
          Email
        </label>
        <input
          {...register("email", { required: true })}
          name="email"
          type="email"
          id="contact_email"
          required={true}
          autoComplete={"true"}
          className="w-full rounded bg-white px-3 py-3 text-sm text-gray-700 placeholder-gray-400 shadow focus:outline-none focus:ring"
          placeholder="email@example.com"
          style={{ transition: "all 0.15s ease 0s" }}
        />

        {errors.email && (
          <div className="text-xs italic text-red-800">
            <span className="text-xs italic text-red-800">
              {errors.email.message}
            </span>
          </div>
        )}
      </div>
      <div className="relative mb-3 w-full">
        <label
          className="mb-2 block text-xs font-bold uppercase text-gray-700"
          htmlFor="contact_message"
        >
          Message
        </label>
        <textarea
          {...register("message", { required: true })}
          name="message"
          id="contact_message"
          required={true}
          rows={8}
          className="w-full rounded bg-white px-3 py-3 text-sm text-gray-700 placeholder-gray-400 shadow focus:outline-none focus:ring"
          placeholder="Ecrivez votre message..."
        />

        {errors.message && (
          <div className="text-xs italic text-red-800">
            <span className="text-xs italic text-red-800">
              {errors.message.message}
            </span>
          </div>
        )}
      </div>
      <div className="mt-6 text-center">
        <button
          className={`inline-flex rounded-lg bg-gradient-to-r from-blue-700 to-red-700 px-12 py-3 text-sm font-semibold text-white shadow hover:from-pink-500 hover:to-orange-500 hover:shadow-lg active:bg-gray-700 ${
            (isSubmitting && isValid) || isSubmitSuccessful
              ? "cursor-not-allowed"
              : null
          }`}
          type="submit"
          style={{ transition: "all 0.15s ease 0s" }}
        >
          {isSubmitting && isValid && (
            <svg
              className="-ml-1 mr-3 h-5 w-5 animate-spin text-white"
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
          {isSubmitSuccessful && (
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
          {(!isSubmitSuccessful && !isSubmitting) || (!isValid && (
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
    </form>
  );
};

export default ContactForm;
