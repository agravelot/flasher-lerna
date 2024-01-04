import { FC } from "react";
import { api, HttpRequestError } from "@flasher/common";
import { Path, useForm } from "react-hook-form";
import { ContactInput } from "./ContactInput";
import { contactFormMatrix } from "./ContactFormMatrix";

enum PrestationTypeEnum {
  COMMON = 0,
  WEDDING = 1,
  PREGNANCY = 2,
  CASUAL = 3,
  ANIMAL = 4,
  FAMILY = 5,
  COSPLAY = 6,
  OTHER = 7,
}

type PrestationType = Lowercase<keyof typeof PrestationTypeEnum>;

interface ContactErrors {
  errors: { email?: string[]; name?: string[]; message?: string[] };
}

export interface MatrixType {
  prestationType: PrestationType;
  prestationName: string;
  fields: MatrixField[];
}

export interface SelectOption {
  label: string;
  value: string;
}

export interface MatrixField {
  isVisible?: (input: ContactFormRequestInputs) => boolean;
  idForm: Path<ContactFormRequestInputs>;
  idHtml: string;
  label: string;
  type: string;
  placeholder?: string;
  required?: boolean;
  tag?: string;
  checkboxTitle?: boolean;
  checkboxValue?: string;
  checkboxLabel?: string;
  selectOptions?: SelectOption[];
  otherInputInfo?: MatrixField;
}

export interface ContactFormRequestInputs {
  name: string;
  phone?: string;
  email: string;
  prestationType: PrestationType;
  location?: string;
  connect?: string;
  connectOther?: string;
  date?: string;
  weddingPartner?: string;
  weddingGuests?: string;
  weddingPrepare?: string;
  weddingCeremony?: string;
  weddingCocktail?: string;
  weddingDinner?: string;
  weddingOther?: string;
  familyMembers?: string;
  animalType?: string;
  cosplayName?: string;
  cosplayUniverse?: string;
  message: string;
}

const ContactForm: FC = () => {
  const {
    register,
    handleSubmit,
    watch,
    reset,
    setError,
    formState: { errors, isSubmitSuccessful, isValid, isSubmitting },
    getValues,
  } = useForm<ContactFormRequestInputs>();

  watch(["connect", "prestationType"]);

  const onSubmit = (options: ContactFormRequestInputs) => {
    let message = "";

    contactFormMatrix
      .find((formPart) => formPart.prestationType === "common")
      ?.fields.forEach((field) => {
        if (
          options[field.idForm] === "" ||
          field.idForm === "email" ||
          field.idForm === "name"
        ) {
          return;
        }
        message += `${field.label} : ${options[field.idForm]} <br/>`;
      });

    message +=
      "Type de prestation : " +
      contactFormMatrix.find(
        (formPart) => formPart.prestationType === getValues().prestationType,
      )?.prestationName +
      " <br/>";

    contactFormMatrix
      .find(
        (formPart) => formPart.prestationType === getValues().prestationType,
      )
      ?.fields.forEach((field) => {
        if (options[field.idForm] !== "") {
          switch (field.tag) {
            case "select": {
              const answer = field.selectOptions?.find(
                (option) => option.value === options[field.idForm],
              );
              message += `${field.label} : ${answer?.label} <br/>`;
              break;
            }
            case "checkbox":
              message += `${field.label} - ${field.checkboxLabel} : ${
                options[field.idForm] ? "Oui" : "Non"
              } <br/>`;
              break;
            default:
              if (
                field.idForm === "connectOther" &&
                options["connect"] !== "other"
              ) {
                break;
              }
              message += `${field.label} : ${options[field.idForm]} <br/>`;
              break;
          }
        }
      });

    api("/contact", {
      method: "POST",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        name: options.name,
        email: options.email,
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
              { shouldFocus: true },
            );
          }

          if (data.errors.name) {
            setError(
              "name",
              { message: data.errors.name[0] },
              { shouldFocus: true },
            );
          }

          if (data.errors.message) {
            setError(
              "message",
              { message: data.errors.message[0] },
              { shouldFocus: true },
            );
          }
        }

        throw e;
      });
  };

  return (
    <form onSubmit={handleSubmit(onSubmit)}>
      {contactFormMatrix
        .find((formPart) => formPart.prestationType === "common")
        ?.fields.map((field) => {
          if (field.isVisible && !field.isVisible(getValues())) {
            return null;
          }

          return (
            <ContactInput
              key={field.idForm}
              register={register}
              errors={errors}
              field={field}
            />
          );
        })}

      <div className="relative mb-3 w-full">
        <label
          className="mb-2 block text-xs font-bold uppercase text-gray-700"
          htmlFor="contact_prestation_type"
        >
          Type de prestation
        </label>
        <select
          {...register("prestationType", { required: false })}
          name="prestationType"
          id="contact_prestation_type"
          required={true}
          defaultValue=""
          className="w-full rounded bg-white px-3 py-3 text-sm text-gray-700 placeholder-gray-400 shadow focus:outline-none focus:ring"
          style={{ transition: "all 0.15s ease 0s" }}
        >
          <option value="" disabled>
            Sélectionner une prestation
          </option>
          <option value="wedding">Mariage</option>
          <option value="casual">Casual</option>
          <option value="family">Familial</option>
          <option value="animal">Animalier</option>
          <option value="cosplay">Cosplay</option>
          <option value="pregnancy">Grossesse</option>
          <option value="other">Autre</option>
        </select>
        {errors.prestationType && (
          <div className="text-xs italic text-red-800">
            <span className="text-xs italic text-red-800">
              {errors.prestationType.message}
            </span>
          </div>
        )}
      </div>
      {contactFormMatrix
        .find(
          (formPart) => formPart.prestationType === getValues().prestationType,
        )
        ?.fields.map((field) => {
          if (field.isVisible && !field.isVisible(getValues())) {
            return null;
          }

          return (
            <ContactInput
              key={field.idForm}
              register={register}
              errors={errors}
              field={field}
            />
          );
        })}
      <div className="mt-6 text-center">
        <button
          className={`inline-flex rounded-lg bg-gradient-to-r from-blue-700 to-red-700 px-12 py-3 text-sm font-semibold text-white shadow hover:from-pink-500 hover:to-orange-500 hover:shadow-lg active:bg-gray-700 ${
            (isSubmitting && isValid) || isSubmitSuccessful
              ? "cursor-not-allowed"
              : ""
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
          {(!isSubmitSuccessful && !isSubmitting) ||
            (!isValid && (
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
