import { FC } from "react";
import { FieldErrors, UseFormRegister } from "react-hook-form";
import { ContactFormRequestInputs } from "./ContactForm";
import { MatrixField } from "./ContactForm";

interface Props {
  register: UseFormRegister<ContactFormRequestInputs>;
  errors: FieldErrors<ContactFormRequestInputs>;
  field: MatrixField;
}

export const ContactInput: FC<Props> = ({ register, errors, field }) => {
  const {
    tag = "input",
    idHtml,
    label,
    checkboxTitle = false,
    required = true,
    idForm,
    placeholder = "",
    checkboxValue = "",
    checkboxLabel = "",
    selectOptions = [],
    inputType,
  } = field;
  return (
    <div
      className={
        tag !== "checkbox" ? "relative mb-3 w-full" : "relative w-full"
      }
    >
      {(tag != "checkbox" || checkboxTitle) && (
        <label
          className="mb-2 block text-xs font-bold uppercase text-gray-700"
          htmlFor={idHtml}
        >
          {label}
          {required ? (
            <div className="inline-block pl-0.5 text-red-700">*</div>
          ) : (
            ""
          )}
        </label>
      )}
      {tag === "textarea" && (
        <textarea
          {...register(`${idForm}`, {
            required: required ? "Ce champ est requis" : undefined,
          })}
          name={idForm}
          id={idHtml}
          rows={8}
          className="w-full rounded bg-white px-3 py-3 text-sm text-gray-700 placeholder-gray-400 shadow focus:outline-none focus:ring"
          placeholder={placeholder}
        />
      )}
      {tag === "checkbox" && (
        <div className="relative flex">
          <input
            {...register(`${idForm}`, {
              required: required ? "Ce champ est requis" : undefined,
            })}
            name={idForm}
            type={tag}
            value={checkboxValue}
            id={idHtml}
            autoComplete={"true"}
            className="rounded bg-white mb-1 text-sm text-gray-700 placeholder-gray-400"
            style={{ transition: "all 0.15s ease 0s" }}
            aria-invalid={errors[idForm] ? "true" : "false"}
          />
          <label
            className="mb-2 block text-xs pl-2 font-bold text-gray-700"
            htmlFor={idHtml}
          >
            {checkboxLabel}
          </label>
        </div>
      )}
      {tag === "select" && (
        <select
          {...register(`${idForm}`, {
            required: required ? "Ce champ est requis" : undefined,
          })}
          name={idForm}
          id={idHtml}
          className="w-full rounded bg-white px-3 py-3 text-sm text-gray-700 placeholder-gray-400 shadow focus:outline-none focus:ring"
          style={{ transition: "all 0.15s ease 0s" }}
          aria-invalid={errors[idForm] ? "true" : "false"}
        >
          {selectOptions?.map((option) => {
            return (
              <option key={option.value} value={option.value}>
                {option.label}
              </option>
            );
          })}
        </select>
      )}

      {tag === "input" && idForm !== "connect" && (
        <input
          {...register(`${idForm}`, {
            required: required ? "Ce champ est requis" : undefined,
            // pattern: inputType === "tel" ? "[0-9]{10}" : undefined,
          })}
          name={idForm}
          type={inputType}
          id={idHtml}
          pattern={inputType === "tel" ? "[0-9]{10}" : undefined}
          autoComplete={"true"}
          className="w-full rounded bg-white px-3 py-3 text-sm text-gray-700 placeholder-gray-400 shadow focus:outline-none focus:ring"
          placeholder={placeholder}
          aria-invalid={errors[idForm] ? "true" : "false"}
        />
      )}
      {errors[idForm] && (
        <div className="mt-2">
          <span className="italic text-red-800">{errors[idForm]?.message}</span>
        </div>
      )}
    </div>
  );
};
