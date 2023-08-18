import { FC, useState } from "react";
import { FieldErrors, Path, UseFormRegister } from "react-hook-form";
import { ContactFormRequestInputs, SelectOption } from "./ContactForm";

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
    selectOptions?: SelectOption[];
}

export const ContactInput: FC<Props> = ({ register, errors, idForm, idHtml, label, tag = "input", inputType = "text", required = true, placeholder = "", checkboxTitle = false, checkboxValue = "", checkboxLabel = "", selectOptions = [] }) => {
    return (
        <div className={(tag != "checkbox") ? "relative mb-3 w-full" : "relative w-full"}>
            {(tag != "checkbox" || checkboxTitle) &&
                <label
                    className="mb-2 block text-xs font-bold uppercase text-gray-700"
                    htmlFor={idHtml}
                >
                    {label}{(required) ? <div className="inline-block pl-0.5 text-red-700">*</div> : ""}
                </label>
            }
            {
                (tag === "textarea") &&
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
            }
            {
                (tag === "checkbox") &&
                <div className="relative flex">

                    <input
                        {...register(`${idForm}`, { required: required })}
                        name={idForm}
                        type={tag}
                        value={checkboxValue}
                        id={idHtml}
                        required={required}
                        autoComplete={"true"}
                        className="rounded bg-white mb-1 text-sm text-gray-700 placeholder-gray-400"
                        style={{ transition: "all 0.15s ease 0s" }}
                    />
                    <label
                        className="mb-2 block text-xs pl-2 font-bold text-gray-700"
                        htmlFor={idHtml}
                    >
                        {checkboxLabel}
                    </label>
                </div>
            }
            {
                (tag === "select") &&
                <select
                    {...register(`${idForm}`, { required: required })}
                    name={idForm}
                    id={idHtml}
                    required={required}
                    className="w-full rounded bg-white px-3 py-3 text-sm text-gray-700 placeholder-gray-400 shadow focus:outline-none focus:ring"
                    style={{ transition: "all 0.15s ease 0s" }}
                >
                    {
                        selectOptions.map((option) => {
                            return (<option key={option.value} value={option.value}>{option.label}</option>);
                        })
                    }
                </select>
            }

            {
                (tag === "input") &&
                <input
                    {...register(`${idForm}`, { required: required })}
                    name={idForm}
                    type={inputType}
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