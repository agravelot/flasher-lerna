import { FunctionComponent, useEffect, useState } from "react";
import Link from "next/link";
import { useRouter } from "next/dist/client/router";
import { Invitation } from "@flasher/models";
import {
  api,
  HttpNotFound,
  HttpRequestError,
  WrappedResponse,
} from "@flasher/common";
import { useAuthentication } from "hooks/useAuthentication";

export enum Status {
  Loading,
  Success,
  Error,
  NonValid,
  Expired,
  AlreadyAccepted,
}

const InvitationValidateComponent: FunctionComponent = () => {
  const [status, setStatus] = useState<Status>(Status.Loading);
  const router = useRouter();
  const { initialized, keycloak } = useAuthentication();

  useEffect(() => {
    const validate = async (c: string) => {
      try {
        await api<WrappedResponse<Invitation>>(`/invitations/${c}/accept`, {
          headers: {
            Authorization: `Bearer ${keycloak?.token}`,
          },
        });
        setStatus(Status.Success);
      } catch (e) {
        if (e instanceof HttpNotFound) {
          setStatus(Status.NonValid);
        }

        if (e instanceof HttpRequestError) {
          if (e.response.status === 400) {
            const message = (await e.response.json()).message;
            if (message === "ACCEPTED") {
              setStatus(Status.AlreadyAccepted);
              return;
            }
            if (message === "EXPIRED") {
              setStatus(Status.Expired);
              return;
            }
            setStatus(Status.Error);
          }
          if (e.response.ok === false) {
            setStatus(Status.Error);
          }
        }
      }
    };

    if (!initialized) {
      return;
    }

    if (!keycloak?.authenticated) {
      keycloak?.login();
      return;
    }

    const code = router.query.code;
    if (!code) {
      setStatus(Status.Error);
      throw new Error("Invitation code is not provided.");
    }

    validate(code as string);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [initialized, keycloak]);

  const message = (): string => {
    if (status === Status.Loading) {
      return "Vérificaton en cours de votre invitation...";
    }

    if (status === Status.Error) {
      return "Une erreur a eu lieu";
    }

    if (status === Status.NonValid) {
      return "Invitation non valide";
    }

    if (status === Status.AlreadyAccepted) {
      return "Cette invitation a déjà été utilisé";
    }

    if (status === Status.Expired) {
      return "Invitation expiré";
    }

    if (status === Status.Success) {
      return "Invitation validée";
    }

    throw new Error("Unale to get message");
  };

  return (
    <div className="mb-8 text-center">
      {status === Status.Success && (
        <div className="mx-auto mb-4 flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:h-10 sm:w-10">
          {/* <!-- Heroicon name: badge-check --> */}
          <svg
            className="h-6 w-6 text-green-600"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              strokeWidth="2"
              d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"
            />
          </svg>
        </div>
      )}

      {status >= 2 && (
        <div className="mx-auto mb-4 flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:h-10 sm:w-10">
          {/* <!-- Heroicon name: exclamation --> */}
          <svg
            className="h-6 w-6 text-red-600"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              strokeWidth="2"
              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
            />
          </svg>
        </div>
      )}

      <h1 className="mb-2 text-xl font-bold text-gray-900">{message()}</h1>

      {status === Status.Success && (
        <div>
          <span>Vous pouvez dorénavant accéder à toutes vos photos.</span>
          <div className="mt-8">
            <Link
              href={{ pathname: "/me/albums" }}
              className="mx-auto mt-12 rounded-lg bg-gradient-to-r from-blue-700 to-red-700 py-3 px-10 font-semibold text-white hover:from-pink-500 hover:to-orange-500"
            >
              Accéder à mes albums
            </Link>
          </div>
        </div>
      )}
      {status >= 2 && (
        <div className="text-base text-gray-700">
          <span>N&apos;hésitez pas à me contarter pour remédier à cela.</span>
          <div className="mt-8">
            <Link
              href={{ pathname: "/", hash: "#contact" }}
              className="mx-auto mt-12 rounded-lg bg-gradient-to-r from-blue-700 to-red-700 py-3 px-10 font-semibold text-white hover:from-pink-500 hover:to-orange-500"
            >
              Prendre contact
            </Link>
          </div>
        </div>
      )}
    </div>
  );
};

export default InvitationValidateComponent;
