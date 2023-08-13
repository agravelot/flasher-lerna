import { FC } from "react";
import { FieldErrors, Path, UseFormRegister } from "react-hook-form";
import { ContactFormRequestInputs } from "./ContactForm";

interface Props {
    register: UseFormRegister<ContactFormRequestInputs>;
    errors: FieldErrors<ContactFormRequestInputs>;
    idForm: Path<ContactFormRequestInputs>;
    idHtml: string;
    label: string;
    type?: string;
    required?: boolean;
}

export const ContactInput: FC<Props> = ({ register, errors, idForm, idHtml, label, type = "text", required = true }) => {
    return (
        <div className="relative mb-3 w-full">
            <label
                className="mb-2 block text-xs font-bold uppercase text-gray-700"
                htmlFor={idHtml}
            >
                {label}
            </label>

            <input
                {...register(`${idForm}`, { required: required })}
                type={type}
                id={idHtml}
                required={required}
                autoComplete={"true"}
                className="w-full rounded bg-white px-3 py-3 text-sm text-gray-700 placeholder-gray-400 shadow focus:outline-none focus:ring"
                placeholder="Nom ou pseudonyme"
            />

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