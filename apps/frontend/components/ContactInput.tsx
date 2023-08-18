import { FC } from "react";
import { FieldErrors, Path, UseFormRegister } from "react-hook-form";
import { ContactFormRequestInputs } from "./ContactForm";

interface Props {
    register: UseFormRegister<ContactFormRequestInputs>;
    errors: FieldErrors<ContactFormRequestInputs>;
    idForm: Path<ContactFormRequestInputs>;
    idHtml: string;
    label: string;
    tag?: string;
    inputType?: string;
    required?: boolean;
    placeholder?: string;
    checkboxValue?: string;
    checkboxTitle?: boolean;
    checkboxLabel?: string;
}

export const ContactInput: FC<Props> = ({ register, errors, idForm, idHtml, label, tag = "input", inputType = "text", required = true, placeholder = "", checkboxTitle = false, checkboxValue = "", checkboxLabel = "" }) => {
    return (
        <div className="relative mb-3 w-full">
            <label
                className="mb-2 block text-xs font-bold uppercase text-gray-700"
                htmlFor={idHtml}
            >
                {label}{(required) ? <div className="inline-block pl-0.5 text-red-700">*</div> : ""}
            </label>
            {
                (balise === "textarea") ?
                    <textarea
                        {...register(`${idForm}`, { required: required })}
                        name={idForm}
                        type={inputType}
                        id={idHtml}
                        required={required}
                        rows={8}
                        className="w-full rounded bg-white px-3 py-3 text-sm text-gray-700 placeholder-gray-400 shadow focus:outline-none focus:ring"
                        placeholder={placeholder}
                    />
                    :
                    <input
                        {...register(`${idForm}`, { required: required })}
                        name={idForm}
            }
                        id={idHtml}
                        required={required}
                        autoComplete={"true"}
                        className="w-full rounded bg-white px-3 py-3 text-sm text-gray-700 placeholder-gray-400 shadow focus:outline-none focus:ring"
                        placeholder={placeholder}
                    />
            }
            {errors[idForm] && (
                <div className="text-xs italic text-red-800">
                    <span className="text-xs italic text-red-800">
                        {errors[idForm]?.message}
                    </span>
                </div>
            )}
        </div>
    );
};