import { FunctionComponent } from "react";
import { api, HttpRequestError } from "@flasher/common";
import { useForm } from "react-hook-form";

interface ContactErrors {
  errors: { email?: string[]; name?: string[]; message?: string[] };
}

export interface ContactFormRequestInputs {
  name: string;
  phone?: string;
  email: string;
  prestationType: string;
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
  const [selectedPrestationType, setSelectedPrestationType] = useState("");
  const [showOtherConnectType, setShowOtherConnectType] = useState(false);

  const onSubmit = (options: ContactFormRequestInputs) => {

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
          Prénom
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
          htmlFor="contact_phone"
        >
          Numéro de téléphone
        </label>
        <input
          {...register("phone", { required: false })}
          name="phone"
          type="text"
          id="contact_phone"
          required={false}
          autoComplete={"true"}
          className="w-full rounded bg-white px-3 py-3 text-sm text-gray-700 placeholder-gray-400 shadow focus:outline-none focus:ring"
          placeholder="06XXXXXXXX"
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
        selectedPrestationType != "" ?
          <div className="relative mb-3 w-full">
            <label
              className="mb-2 block text-xs font-bold uppercase text-gray-700"
              htmlFor="location"
            >
              Lieu
            </label>
            <input
              {...register("location", { required: true })}
              name="location"
              type="text"
              id="location"
              required={false}
              autoComplete={"true"}
              className="w-full rounded bg-white px-3 py-3 text-sm text-gray-700 placeholder-gray-400 shadow focus:outline-none focus:ring"
              placeholder="Le lieu où vous souhaiteriez réaliser la prestation"
              style={{ transition: "all 0.15s ease 0s" }}
            />

            {errors.location && (
              <div className="text-xs italic text-red-800">
                <span className="text-xs italic text-red-800">
                  {errors.location.message}
                </span>
              </div>
            )}
          </div> : ""
      }
      {
        selectedPrestationType == "wedding" || selectedPrestationType == "pregnancy" || selectedPrestationType == "cosplay" || selectedPrestationType == "casual" ?
          <div className="relative mb-3 w-full">
            <label
              className="mb-2 block text-xs font-bold uppercase text-gray-700"
              htmlFor="contact_date"
            >
              Date
            </label>
            <input
              {...register("date", { required: true })}
              name="date"
              type="text"
              id="contact_date"
              required={true}
              autoComplete={"true"}
              className="w-full rounded bg-white px-3 py-3 text-sm text-gray-700 placeholder-gray-400 shadow focus:outline-none focus:ring"
              placeholder="La date ou période où vous souhaiteriez réaliser la prestation"
              style={{ transition: "all 0.15s ease 0s" }}
            />

            {errors.connectOther && (
              <div className="text-xs italic text-red-800">
                <span className="text-xs italic text-red-800">
                  {errors.connectOther.message}
                </span>
              </div>
            )}
          </div> : ""
      }

      {
        selectedPrestationType == "wedding" ?
          <div>
            <div className="relative mb-3 w-full">
              <label
                className="mb-2 block text-xs font-bold uppercase text-gray-700"
                htmlFor="contact_wedding_partner"
              >
                Prénom du futur époux/(se)
              </label>
              <input
                {...register("weddingPartner", { required: false })}
                name="weddingPartner"
                type="text"
                id="contact_wedding_partner"
                required={false}
                autoComplete={"true"}
                className="w-full rounded bg-white px-3 py-3 text-sm text-gray-700 placeholder-gray-400 shadow focus:outline-none focus:ring"
                placeholder="Le prénom de votre futur époux/(se)"
                style={{ transition: "all 0.15s ease 0s" }}
              />

              {errors.weddingPartner && (
                <div className="text-xs italic text-red-800">
                  <span className="text-xs italic text-red-800">
                    {errors.weddingPartner.message}
                  </span>
                </div>
              )}
            </div>

            <div className="relative mb-3 w-full">
              <label
                className="mb-2 block text-xs font-bold uppercase text-gray-700"
                htmlFor="contact_wedding_guests"
              >
                Nombre d&apos;invités
              </label>
              <input
                {...register("weddingGuests", { required: true })}
                name="weddingGuests"
                type="text"
                id="contact_wedding_guests"
                required={true}
                autoComplete={"true"}
                className="w-full rounded bg-white px-3 py-3 text-sm text-gray-700 placeholder-gray-400 shadow focus:outline-none focus:ring"
                placeholder="Indiquez le nombre d'invités présents au mariage (approximatif)"
                style={{ transition: "all 0.15s ease 0s" }}
              />

              {errors.weddingGuests && (
                <div className="text-xs italic text-red-800">
                  <span className="text-xs italic text-red-800">
                    {errors.weddingGuests.message}
                  </span>
                </div>
              )}
            </div>

            <div className="relative mb-3 w-full">
              <label
                className="mb-2 block text-xs font-bold uppercase text-gray-700"
              >
                Moments d&apos;intervention (checkbox)
              </label>
              <div className="relative flex">
                <input
                  {...register("weddingMoments1", { required: false })}
                  name="weddingMoments1"
                  type="checkbox"
                  value="prepare"
                  id="contact_wedding_moments1"
                  required={false}
                  autoComplete={"true"}
                  className="rounded bg-white mb-1 text-sm text-gray-700 placeholder-gray-400"
                  style={{ transition: "all 0.15s ease 0s" }}
                />
                <label
                  className="mb-2 block text-xs pl-2 font-bold text-gray-700"
                  htmlFor="contact_wedding_moments1"
                >
                  Préparatifs
                </label>
                {errors.weddingMoments1 && (
                  <div className="text-xs italic text-red-800">
                    <span className="text-xs italic text-red-800">
                      {errors.weddingMoments1.message}
                    </span>
                  </div>
                )}
              </div>
              <div className="relative flex">
                <input
                  {...register("weddingMoments2", { required: false })}
                  name="weddingMoments2"
                  type="checkbox"
                  value="ceremony"
                  id="contact_wedding_moments2"
                  required={false}
                  autoComplete={"true"}
                  className="rounded bg-white mb-1 text-sm text-gray-700 placeholder-gray-400"
                  style={{ transition: "all 0.15s ease 0s" }}
                />
                <label
                  className="mb-2 block text-xs pl-2 font-bold text-gray-700"
                  htmlFor="contact_wedding_moments2"
                >
                  Cérémonie(s)
                </label>
                {errors.weddingMoments2 && (
                  <div className="text-xs italic text-red-800">
                    <span className="text-xs italic text-red-800">
                      {errors.weddingMoments2.message}
                    </span>
                  </div>
                )}
              </div>
              <div className="relative flex">
                <input
                  {...register("weddingMoments3", { required: false })}
                  name="weddingMoments3"
                  type="checkbox"
                  value="cocktail"
                  id="contact_wedding_moments3"
                  required={false}
                  autoComplete={"true"}
                  className="rounded bg-white mb-1 text-sm text-gray-700 placeholder-gray-400"
                  style={{ transition: "all 0.15s ease 0s" }}
                />
                <label
                  className="mb-2 block text-xs pl-2 font-bold text-gray-700"
                  htmlFor="contact_wedding_moments3"
                >
                  Cocktail
                </label>
                {errors.weddingMoments3 && (
                  <div className="text-xs italic text-red-800">
                    <span className="text-xs italic text-red-800">
                      {errors.weddingMoments3.message}
                    </span>
                  </div>
                )}
              </div>
              <div className="relative flex">
                <input
                  {...register("weddingMoments4", { required: false })}
                  name="weddingMoments4"
                  type="checkbox"
                  value="dinner"
                  id="contact_wedding_moments4"
                  required={false}
                  autoComplete={"true"}
                  className="rounded bg-white mb-1 text-sm text-gray-700 placeholder-gray-400"
                  style={{ transition: "all 0.15s ease 0s" }}
                />
                <label
                  className="mb-2 block text-xs pl-2 font-bold text-gray-700"
                  htmlFor="contact_wedding_moments4"
                >
                  Dîner et soirée
                </label>
                {errors.weddingMoments4 && (
                  <div className="text-xs italic text-red-800">
                    <span className="text-xs italic text-red-800">
                      {errors.weddingMoments4.message}
                    </span>
                  </div>
                )}
              </div>
              <div className="relative flex">
                <input
                  {...register("weddingMoments5", { required: false })}
                  name="weddingMoments5"
                  type="checkbox"
                  value="brunch"
                  id="contact_wedding_moments5"
                  required={false}
                  autoComplete={"true"}
                  className="rounded bg-white mb-1 text-sm text-gray-700 placeholder-gray-400"
                  style={{ transition: "all 0.15s ease 0s" }}
                />
                <label
                  className="mb-2 block text-xs pl-2 font-bold text-gray-700"
                  htmlFor="contact_wedding_moments5"
                >
                  Autre
                </label>
                {errors.weddingMoments5 && (
                  <div className="text-xs italic text-red-800">
                    <span className="text-xs italic text-red-800">
                      {errors.weddingMoments5.message}
                    </span>
                  </div>
                )}
              </div>
            </div>
          </div>
          : ""
      }

      {
        selectedPrestationType == "family" ?
          <div className="relative mb-3 w-full">
            <label
              className="mb-2 block text-xs font-bold uppercase text-gray-700"
              htmlFor="contact_family_members"
            >
              Nombre de personnes à prendre en photo
            </label>
            <input
              {...register("familyMembers", { required: true })}
              name="familyMembers"
              type="text"
              id="contact_family_members"
              required={true}
              autoComplete={"true"}
              className="w-full rounded bg-white px-3 py-3 text-sm text-gray-700 placeholder-gray-400 shadow focus:outline-none focus:ring"
              placeholder="Indiquez combien de personnes seront à prendre en photo lors de la prestation"
              style={{ transition: "all 0.15s ease 0s" }}
            />

            {errors.familyMembers && (
              <div className="text-xs italic text-red-800">
                <span className="text-xs italic text-red-800">
                  {errors.familyMembers.message}
                </span>
              </div>
            )}
          </div>
          : ""
      }

      {
        selectedPrestationType == "animal" ?
          <div className="relative mb-3 w-full">
            <label
              className="mb-2 block text-xs font-bold uppercase text-gray-700"
              htmlFor="contact_animal_type"
            >
              Type d&apos;animal
            </label>
            <input
              {...register("animalType", { required: true })}
              name="animalType"
              type="text"
              id="contact_animal_type"
              required={true}
              autoComplete={"true"}
              className="w-full rounded bg-white px-3 py-3 text-sm text-gray-700 placeholder-gray-400 shadow focus:outline-none focus:ring"
              placeholder="Indiquer quel type d'animal sera à prendre en photo lors de la prestation "
              style={{ transition: "all 0.15s ease 0s" }}
            />

            {errors.animalType && (
              <div className="text-xs italic text-red-800">
                <span className="text-xs italic text-red-800">
                  {errors.animalType.message}
                </span>
              </div>
            )}
          </div>
          : ""
      }

      {
        selectedPrestationType == "cosplay" ?
          <div className="relative mb-3 w-full">
            <label
              className="mb-2 block text-xs font-bold uppercase text-gray-700"
              htmlFor="contact_cosplay_name"
            >
              Nom du personnage incarné
            </label>
            <input
              {...register("cosplayName", { required: true })}
              name="cosplayName"
              type="text"
              id="contact_cosplay_name"
              required={true}
              autoComplete={"true"}
              className="w-full rounded bg-white px-3 py-3 text-sm text-gray-700 placeholder-gray-400 shadow focus:outline-none focus:ring"
              placeholder="Indiquer le nom du personnage que vous incarnerez en cosplay lors de la prestation"
              style={{ transition: "all 0.15s ease 0s" }}
            />

            {errors.animalType && (
              <div className="text-xs italic text-red-800">
                <span className="text-xs italic text-red-800">
                  {errors.animalType.message}
                </span>
              </div>
            )}

            <label
              className="mb-2 block text-xs font-bold uppercase text-gray-700"
              htmlFor="contact_cosplay_universe"
            >
              Univers du personnage
            </label>
            <input
              {...register("cosplayUniverse", { required: true })}
              name="cosplayUniverse"
              type="text"
              id="contact_cosplay_universe"
              required={true}
              autoComplete={"true"}
              className="w-full rounded bg-white px-3 py-3 text-sm text-gray-700 placeholder-gray-400 shadow focus:outline-none focus:ring"
              placeholder="Indiquer de quel univers provient votre personnage"
              style={{ transition: "all 0.15s ease 0s" }}
            />

            {errors.animalType && (
              <div className="text-xs italic text-red-800">
                <span className="text-xs italic text-red-800">
                  {errors.animalType.message}
                </span>
              </div>
            )}
          </div>
          : ""
      }
      {
        selectedPrestationType != "" ?
          <div className="relative mb-3 w-full">
            <label
              className="mb-2 block text-xs font-bold uppercase text-gray-700"
              htmlFor="contact_connect"
            >
              Comment vous m&apos;avez connu ?
            </label>
            <select
              {...register("connect", { required: false })}
              name="connect"
              id="contact_connect"
              required={false}
              className="w-full rounded bg-white px-3 py-3 text-sm text-gray-700 placeholder-gray-400 shadow focus:outline-none focus:ring"
              style={{ transition: "all 0.15s ease 0s" }}
              onChange={(e) => {
                if (e.target.value == "other") {
                  setShowOtherConnectType(true);
                }
                else {
                  setShowOtherConnectType(false);
                }
              }
              }
            >
              <option value="">Sélectionner une option</option>
              <option value="wordOfMouth">Bouche à oreille</option>
              <option value="google">Google</option>
              <option value="facebook">Facebook</option>
              <option value="Instagram">Instagram</option>
              <option value="other">Autre</option>
            </select>
            {errors.connect && (
              <div className="text-xs italic text-red-800">
                <span className="text-xs italic text-red-800">
                  {errors.connect.message}
                </span>
              </div>
            )}
          </div> : ""
      }
      {
        showOtherConnectType && selectedPrestationType != "" ?
          <div className="relative mb-3 w-full">
            <label
              className="mb-2 block text-xs font-bold uppercase text-gray-700"
              htmlFor="contact_connect_other"
            >
              Préciser
            </label>
            <input
              {...register("connectOther", { required: true })}
              name="connectOther"
              type="text"
              id="contact_connect_other"
              required={true}
              autoComplete={"true"}
              className="w-full rounded bg-white px-3 py-3 text-sm text-gray-700 placeholder-gray-400 shadow focus:outline-none focus:ring"
              placeholder=""
              style={{ transition: "all 0.15s ease 0s" }}
            />

            {errors.connectOther && (
              <div className="text-xs italic text-red-800">
                <span className="text-xs italic text-red-800">
                  {errors.connectOther.message}
                </span>
              </div>
            )}
          </div> : ""
      }

      {
        selectedPrestationType != "" ?
          <div className="relative mb-3 w-full">
            <label
              className="mb-2 block text-xs font-bold uppercase text-gray-700"
              htmlFor="contact_message"
            >
              Parlez-moi de vos attentes
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
          </div> : ""
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
    </form>
  );
};

export default ContactForm;
