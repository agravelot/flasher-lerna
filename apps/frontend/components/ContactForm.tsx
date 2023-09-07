import { FunctionComponent, useState } from "react";
import { api, HttpRequestError } from "@flasher/common";
import { Path, useForm } from "react-hook-form";
import { ContactInput } from "./ContactInput";

enum PrestationType {
  Mariage = "wedding",
  Grossesse = "pregnancy",
  Casual = "casual",
  Animalier = "animal",
  Familial = "family",
  Cosplay = "cosplay",
  Autre = "other"
}

interface ContactErrors {
  errors: { email?: string[]; name?: string[]; message?: string[] };
}

interface MatrixType {
  prestationType: string;
  prestationName: string;
  fields: MatrixField[];
}

export interface SelectOption {
  label: string;
  value: string;
}

interface MatrixField {
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
  selectOptions?: SelectOption[]
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
  weddingMoments?: string;
  weddingMoments1?: string;
  weddingMoments2?: string;
  weddingMoments3?: string;
  weddingMoments4?: string;
  weddingMoments5?: string;
  familyMembers?: string;
  animalType?: string;
  cosplayName?: string;
  cosplayUniverse?: string;
  message: string;
}

const ContactForm: FunctionComponent = () => {
  const {
    register,
    handleSubmit,
    reset,
    setError,
    formState: { errors, isSubmitSuccessful, isValid, isSubmitting },
  } = useForm<ContactFormRequestInputs>();

  const [selectedPrestationType, setSelectedPrestationType] = useState<PrestationType>();

  const matrix: MatrixType[] = [
    {
      prestationType: "common",
      prestationName: "Commun",
      fields:
        [
          {
            idForm: "name",
            idHtml: "contact_name",
            label: "Prénom",
            type: "text",
            placeholder: "Nom ou pseudonyme",
          },
          {
            idForm: "email",
            idHtml: "contact_email",
            label: "Email",
            type: "text",
            placeholder: "email@example.com",
          },
          {
            idForm: "phone",
            idHtml: "contact_phone",
            label: "Numéro de téléphone",
            type: "text",
            placeholder: "06 XX XX XX XX",
            required: false,
          }
        ],
    },
    {
      prestationType: "wedding",
      prestationName: "Mariage",
      fields:
        [
          {
            idForm: "location",
            idHtml: "contact_location",
            label: "Lieu du mariage",
            type: "text",
            placeholder: "Indiquez où se déroule la cérémonie",
          },
          {
            idForm: "date",
            idHtml: "contact_date",
            label: "Date du mariage",
            type: "text",
            placeholder: "",
            required: false,
          },
          {
            idForm: "weddingPartner",
            idHtml: "contact_wedding_partner",
            label: "Prénom du futur époux/(se)",
            type: "text",
            placeholder: "",
            required: false,
          },
          {
            idForm: "weddingGuests",
            idHtml: "contact_wedding_guests",
            label: "Nombre d'invités",
            type: "text",
            placeholder: "",
          },
          {
            idForm: "weddingMoments1",
            idHtml: "contact_wedding_moments1",
            label: "Moments d'intervention",
            type: "checkbox",
            placeholder: "",
            required: false,
            checkboxLabel: "Préparatifs",
            checkboxTitle: true,
            checkboxValue: "prepare",
            tag: "checkbox"
          },
          {
            idForm: "weddingMoments2",
            idHtml: "contact_wedding_moments2",
            label: "Moments d'intervention",
            type: "checkbox",
            placeholder: "",
            required: false,
            checkboxLabel: "Cérémonie(s)",
            checkboxTitle: false,
            checkboxValue: "ceremony",
            tag: "checkbox"
          },
          {
            idForm: "weddingMoments3",
            idHtml: "contact_wedding_moments3",
            label: "Moments d'intervention",
            type: "checkbox",
            placeholder: "",
            required: false,
            checkboxLabel: "Cocktail",
            checkboxTitle: false,
            checkboxValue: "cocktail",
            tag: "checkbox"
          },
          {
            idForm: "weddingMoments4",
            idHtml: "contact_wedding_moments4",
            label: "Moments d'intervention",
            type: "checkbox",
            placeholder: "",
            required: false,
            checkboxLabel: "Dîner et soirée",
            checkboxTitle: false,
            checkboxValue: "dinner",
            tag: "checkbox"
          },
          {
            idForm: "weddingMoments5",
            idHtml: "contact_wedding_moments5",
            label: "Moments d'intervention",
            type: "checkbox",
            placeholder: "",
            required: false,
            checkboxLabel: "Autre",
            checkboxTitle: false,
            checkboxValue: "other",
            tag: "checkbox"
          },
          {
            idForm: "connect",
            idHtml: "contact_connect",
            label: "Comment m'avez vous connue?",
            type: "text",
            tag: "select",
            placeholder: "",
            required: false,
            selectOptions: [
              {
                label: "Sélectionner une option",
                value: "",
              },
              {
                label: "Bouche à oreille",
                value: "wordOfMouth",
              },
              {
                label: "Google",
                value: "google",
              },
              {
                label: "Facebook",
                value: "facebook",
              },
              {
                label: "Instagram",
                value: "instagram",
              },
              {
                label: "Autre",
                value: "other",
              },
            ],
            otherInputInfo: {
              idForm: "connectOther",
              idHtml: "contact_connect_other",
              label: "Si Autre, préciser",
              type: "text",
              placeholder: "",
              required: false
            }
          }, {
            idForm: "connectOther",
            idHtml: "contact_connect_other",
            label: "Si Autre, préciser",
            type: "text",
            placeholder: "",
            required: false
          },
          {
            idForm: "message",
            idHtml: "contact_message",
            label: "Parlez moi de vos attentes",
            type: "text",
            tag: "textarea",
            placeholder: "Ecrivez votre message...",
          },
        ],
    },
    {
      prestationType: "pregnancy",
      prestationName: "Grossesse",
      fields:
        [
          {
            idForm: "location",
            idHtml: "contact_location",
            label: "Lieu",
            type: "text",
            placeholder: "",
            required: false
          },
          {
            idForm: "date",
            idHtml: "contact_date",
            label: "Date",
            type: "text",
            placeholder: "",
            required: false,
          },
          {
            idForm: "connect",
            idHtml: "contact_connect",
            label: "Comment m'avez vous connue?",
            type: "text",
            tag: "select",
            placeholder: "",
            required: false,
            selectOptions: [
              {
                label: "Sélectionner une option",
                value: "",
              },
              {
                label: "Bouche à oreille",
                value: "wordOfMouth",
              },
              {
                label: "Google",
                value: "google",
              },
              {
                label: "Facebook",
                value: "facebook",
              },
              {
                label: "Instagram",
                value: "instagram",
              },
              {
                label: "Autre",
                value: "other",
              },
            ],
            otherInputInfo: {
              idForm: "connectOther",
              idHtml: "contact_connect_other",
              label: "Si Autre, préciser",
              type: "text",
              placeholder: "",
              required: false
            }
          }, {
            idForm: "connectOther",
            idHtml: "contact_connect_other",
            label: "Si Autre, préciser",
            type: "text",
            placeholder: "",
            required: false
          },
          {
            idForm: "message",
            idHtml: "contact_message",
            label: "Parlez moi de vos attentes",
            type: "text",
            tag: "textarea",
            placeholder: "Ecrivez votre message...",
          },
        ],
    },
    {
      prestationType: "family",
      prestationName: "Familial",
      fields:
        [
          {
            idForm: "location",
            idHtml: "contact_location",
            label: "Lieu",
            type: "text",
            placeholder: "",
            required: false
          },
          {
            idForm: "familyMembers",
            idHtml: "contact_family_members",
            label: "Nombre de personnes à prendre en photo",
            type: "text",
            placeholder: "",
            required: true,
          },
          {
            idForm: "connect",
            idHtml: "contact_connect",
            label: "Comment m'avez vous connue?",
            type: "text",
            tag: "select",
            placeholder: "",
            required: false,
            selectOptions: [
              {
                label: "Sélectionner une option",
                value: "",
              },
              {
                label: "Bouche à oreille",
                value: "wordOfMouth",
              },
              {
                label: "Google",
                value: "google",
              },
              {
                label: "Facebook",
                value: "facebook",
              },
              {
                label: "Instagram",
                value: "instagram",
              },
              {
                label: "Autre",
                value: "other",
              },
            ],
            otherInputInfo: {
              idForm: "connectOther",
              idHtml: "contact_connect_other",
              label: "Si Autre, préciser",
              type: "text",
              placeholder: "",
              required: false
            }
          }, {
            idForm: "connectOther",
            idHtml: "contact_connect_other",
            label: "Si Autre, préciser",
            type: "text",
            placeholder: "",
            required: false
          },
          {
            idForm: "message",
            idHtml: "contact_message",
            label: "Parlez moi de vos attentes",
            type: "text",
            tag: "textarea",
            placeholder: "Ecrivez votre message...",
          },
        ],
    },
    {
      prestationType: "animal",
      prestationName: "Animalier",
      fields:
        [
          {
            idForm: "location",
            idHtml: "contact_location",
            label: "Lieu",
            type: "text",
            placeholder: "",
            required: false
          },
          {
            idForm: "animalType",
            idHtml: "contact_animal_type",
            label: "Type d'animal",
            type: "text",
            placeholder: "",
            required: true,
          },
          {
            idForm: "connect",
            idHtml: "contact_connect",
            label: "Comment m'avez vous connue?",
            type: "text",
            tag: "select",
            placeholder: "",
            required: false,
            selectOptions: [
              {
                label: "Sélectionner une option",
                value: "",
              },
              {
                label: "Bouche à oreille",
                value: "wordOfMouth",
              },
              {
                label: "Google",
                value: "google",
              },
              {
                label: "Facebook",
                value: "facebook",
              },
              {
                label: "Instagram",
                value: "instagram",
              },
              {
                label: "Autre",
                value: "other",
              },
            ],
            otherInputInfo: {
              idForm: "connectOther",
              idHtml: "contact_connect_other",
              label: "Si Autre, préciser",
              type: "text",
              placeholder: "",
              required: false
            }
          }, {
            idForm: "connectOther",
            idHtml: "contact_connect_other",
            label: "Si Autre, préciser",
            type: "text",
            placeholder: "",
            required: false
          },
          {
            idForm: "message",
            idHtml: "contact_message",
            label: "Parlez moi de vos attentes",
            type: "text",
            tag: "textarea",
            placeholder: "Ecrivez votre message...",
          },
        ],
    },
    {
      prestationType: "cosplay",
      prestationName: "Cosplay",
      fields:
        [
          {
            idForm: "location",
            idHtml: "contact_location",
            label: "Lieu",
            type: "text",
            placeholder: "",
            required: false
          },
          {
            idForm: "date",
            idHtml: "contact_date",
            label: "Date",
            type: "text",
            placeholder: "",
            required: false,
          },
          {
            idForm: "cosplayName",
            idHtml: "contact_cosplay_name",
            label: "Nom du personnage incarné",
            type: "text",
            placeholder: "",
            required: true,
          },
          {
            idForm: "cosplayUniverse",
            idHtml: "contact_cosplay_universe",
            label: "Univers du personnage",
            type: "text",
            placeholder: "",
            required: true,
          },
          {
            idForm: "connect",
            idHtml: "contact_connect",
            label: "Comment m'avez vous connue?",
            type: "text",
            tag: "select",
            placeholder: "",
            required: false,
            selectOptions: [
              {
                label: "Sélectionner une option",
                value: "",
              },
              {
                label: "Bouche à oreille",
                value: "wordOfMouth",
              },
              {
                label: "Google",
                value: "google",
              },
              {
                label: "Facebook",
                value: "facebook",
              },
              {
                label: "Instagram",
                value: "instagram",
              },
              {
                label: "Autre",
                value: "other",
              },
            ],
            otherInputInfo: {
              idForm: "connectOther",
              idHtml: "contact_connect_other",
              label: "Si Autre, préciser",
              type: "text",
              placeholder: "",
              required: false
            }
          }, {
            idForm: "connectOther",
            idHtml: "contact_connect_other",
            label: "Si Autre, préciser",
            type: "text",
            placeholder: "",
            required: false
          },
          {
            idForm: "message",
            idHtml: "contact_message",
            label: "Parlez moi de vos attentes",
            type: "text",
            tag: "textarea",
            placeholder: "Ecrivez votre message...",
          },
        ],
    },
    {
      prestationType: "casual",
      prestationName: "Casual",
      fields:
        [
          {
            idForm: "location",
            idHtml: "contact_location",
            label: "Lieu",
            type: "text",
            placeholder: "",
            required: false
          },

          {
            idForm: "date",
            idHtml: "contact_date",
            label: "Date",
            type: "text",
            placeholder: "",
            required: false,
          },
          {
            idForm: "connect",
            idHtml: "contact_connect",
            label: "Comment m'avez vous connue?",
            type: "text",
            tag: "select",
            placeholder: "",
            required: false,
            selectOptions: [
              {
                label: "Sélectionner une option",
                value: "",
              },
              {
                label: "Bouche à oreille",
                value: "wordOfMouth",
              },
              {
                label: "Google",
                value: "google",
              },
              {
                label: "Facebook",
                value: "facebook",
              },
              {
                label: "Instagram",
                value: "instagram",
              },
              {
                label: "Autre",
                value: "other",
              },
            ],
            otherInputInfo: {
              idForm: "connectOther",
              idHtml: "contact_connect_other",
              label: "Si Autre, préciser",
              type: "text",
              placeholder: "",
              required: false
            }
          }, {
            idForm: "connectOther",
            idHtml: "contact_connect_other",
            label: "Si Autre, préciser",
            type: "text",
            placeholder: "",
            required: false
          },
          {
            idForm: "message",
            idHtml: "contact_message",
            label: "Parlez moi de vos attentes",
            type: "text",
            tag: "textarea",
            placeholder: "Ecrivez votre message...",
          },
        ],
    },
    {
      prestationType: "other",
      prestationName: "Autre",
      fields:
        [
          {
            idForm: "location",
            idHtml: "contact_location",
            label: "Lieu",
            type: "text",
            placeholder: "",
            required: false
          },
          {
            idForm: "connect",
            idHtml: "contact_connect",
            label: "Comment m'avez vous connue?",
            type: "text",
            tag: "select",
            placeholder: "",
            required: false,
            selectOptions: [
              {
                label: "Sélectionner une option",
                value: "",
              },
              {
                label: "Bouche à oreille",
                value: "wordOfMouth",
              },
              {
                label: "Google",
                value: "google",
              },
              {
                label: "Facebook",
                value: "facebook",
              },
              {
                label: "Instagram",
                value: "instagram",
              },
              {
                label: "Autre",
                value: "other",
              },
            ],
            otherInputInfo: {
              idForm: "connectOther",
              idHtml: "contact_connect_other",
              label: "Si Autre, préciser",
              type: "text",
              placeholder: "",
              required: false
            }
          }, {
            idForm: "connectOther",
            idHtml: "contact_connect_other",
            label: "Si Autre, préciser",
            type: "text",
            placeholder: "",
            required: false
          },
          {
            idForm: "message",
            idHtml: "contact_message",
            label: "Parlez moi de vos attentes",
            type: "text",
            tag: "textarea",
            placeholder: "Ecrivez votre message...",

          },
        ],
    }
  ];

  const onSubmit = (options: ContactFormRequestInputs) => {
    const email = options.email;
    const name = options.name;
    let message = "";

    matrix.find(formPart => formPart.prestationType === "common")?.fields.map((field) => {
      if (options[field.idForm] != "" && field.idForm != "email" && field.idForm != "name") {
        message += `${field.label} : ${options[field.idForm]} \r\n`;
      }
    });

    message += "Type de prestation : " + matrix.find(formPart => formPart.prestationType === selectedPrestationType)?.prestationName + " \r\n";

    matrix.find(formPart => formPart.prestationType === selectedPrestationType)?.fields.map((field) => {
      if (options[field.idForm] !== "") {
        switch (field.tag) {
          case "select": {
            const answer = field.selectOptions?.find(option => option.value === options[field.idForm]);
            message += `${field.label} : ${answer?.label} \r\n`;
            break;
          }
          case "checkbox":
            message += `${field.label} - ${field.checkboxLabel} : ${(options[field.idForm] ? "Oui" : "Non")} \r\n`;
            break;
          default:
            if (field.idForm === "connectOther" && options["connect"] !== "other") {
              break;
            }
            message += `${field.label} : ${options[field.idForm]} \r\n`;
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
      {
        matrix.find(formPart => formPart.prestationType === "common")?.fields.map((field) => {
          return <ContactInput
            key={field.idForm}
            register={register}
            errors={errors}
            idForm={field.idForm}
            idHtml={field.idHtml}
            label={field.label}
            required={field.required}
            placeholder={field.placeholder}
            inputType={field.type}
            tag={field.tag}
            checkboxLabel={field.checkboxLabel}
            checkboxTitle={field.checkboxTitle}
            checkboxValue={field.checkboxValue}
            selectOptions={field.selectOptions}
          />;
        })
      }

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
          value={selectedPrestationType}
          className="w-full rounded bg-white px-3 py-3 text-sm text-gray-700 placeholder-gray-400 shadow focus:outline-none focus:ring"
          style={{ transition: "all 0.15s ease 0s" }}
          onChange={(e) => {
            setSelectedPrestationType(e.target.value);
          }
          }
        >
          <option value="">Sélectionner une prestation</option>
          <option value="wedding">Mariage</option>
          <option value="pregnancy">Grossesse</option>
          <option value="family">Familial</option>
          <option value="animal">Animalier</option>
          <option value="cosplay">Cosplay</option>
          <option value="casual">Casual</option>
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
      {
        matrix.find(formPart => formPart.prestationType === selectedPrestationType)?.fields.map((field) => {
          return <ContactInput
            key={field.idForm}
            register={register}
            errors={errors}
            idForm={field.idForm}
            idHtml={field.idHtml}
            label={field.label}
            required={field.required}
            placeholder={field.placeholder}
            inputType={field.type}
            tag={field.tag}
            checkboxLabel={field.checkboxLabel}
            checkboxTitle={field.checkboxTitle}
            checkboxValue={field.checkboxValue}
            selectOptions={field.selectOptions}
          />;
        })
      }
      <div className="mt-6 text-center">
        <button
          className={`inline-flex rounded-lg bg-gradient-to-r from-blue-700 to-red-700 px-12 py-3 text-sm font-semibold text-white shadow hover:from-pink-500 hover:to-orange-500 hover:shadow-lg active:bg-gray-700 ${(isSubmitting && isValid) || isSubmitSuccessful
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
    </form >
  );
};

export default ContactForm;
