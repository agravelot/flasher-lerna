import { MatrixType } from "./ContactForm";

export const contactFormMatrix: MatrixType[] = [
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
                    placeholder: "Prénom ou pseudonyme",
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
                    placeholder: "Indiquez où se déroule la cérémonie / mairie",
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
                    placeholder: "Nombre approximatif d'invités",
                },
                {
                    idForm: "weddingPrepare",
                    idHtml: "contact_wedding_prepare",
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
                    idForm: "weddingCeremony",
                    idHtml: "contact_wedding_ceremony",
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
                    idForm: "weddingCocktail",
                    idHtml: "contact_wedding_cocktail",
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
                    idForm: "weddingDinner",
                    idHtml: "contact_wedding_Dinner",
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
                    idForm: "weddingOther",
                    idHtml: "contact_wedding_other",
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
                }, {
                    idForm: "connectOther",
                    idHtml: "contact_connect_other",
                    label: "Si Autre, préciser",
                    type: "text",
                    placeholder: "",
                    required: false,
                    isVisible: (input) => {
                        return input.connect === "other";
                    },
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
                    placeholder: "Par exemple, la ville où vous habitez",
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
                }, {
                    idForm: "connectOther",
                    idHtml: "contact_connect_other",
                    label: "Si Autre, préciser",
                    type: "text",
                    placeholder: "",
                    required: false,
                    isVisible: (input) => {
                        return input.connect === "other";
                    },
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
                    placeholder: "Par exemple, la ville où vous habitez",
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
                }, {
                    idForm: "connectOther",
                    idHtml: "contact_connect_other",
                    label: "Si Autre, préciser",
                    type: "text",
                    placeholder: "",
                    required: false,
                    isVisible: (input) => {
                        return input.connect === "other";
                    },
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
                    placeholder: "Par exemple, la ville où vous habitez",
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
                }, {
                    idForm: "connectOther",
                    idHtml: "contact_connect_other",
                    label: "Si Autre, préciser",
                    type: "text",
                    placeholder: "",
                    required: false,
                    isVisible: (input) => {
                        return input.connect === "other";
                    },
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
                    placeholder: "Par exemple, la ville où vous habitez",
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
                    placeholder: "Par exemple Sukuna...",
                    required: true,
                },
                {
                    idForm: "cosplayUniverse",
                    idHtml: "contact_cosplay_universe",
                    label: "Univers du personnage",
                    type: "text",
                    placeholder: "Par exemple Jujutsu Kaisen...",
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
                }, {
                    idForm: "connectOther",
                    idHtml: "contact_connect_other",
                    label: "Si Autre, préciser",
                    type: "text",
                    placeholder: "",
                    required: false,
                    isVisible: (input) => {
                        return input.connect === "other";
                    },
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
                    placeholder: "Par exemple, la ville où vous habitez",
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
                }, {
                    idForm: "connectOther",
                    idHtml: "contact_connect_other",
                    label: "Si Autre, préciser",
                    type: "text",
                    placeholder: "",
                    required: false,
                    isVisible: (input) => {
                        return input.connect === "other";
                    },
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
                    placeholder: "Par exemple, la ville où vous habitez",
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
                }, {
                    idForm: "connectOther",
                    idHtml: "contact_connect_other",
                    label: "Si Autre, préciser",
                    type: "text",
                    placeholder: "",
                    required: false,
                    isVisible: (input) => {
                        return input.connect === "other";
                    },
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