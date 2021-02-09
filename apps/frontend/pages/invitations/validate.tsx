import { FunctionComponent, useEffect, useState } from "react";
import Link from "next/link";
import Layout from "../../components/Layout";
import Header from "../../components/Header";
import { getGlobalProps, GlobalProps } from "../../stores";
import { useRouter } from "next/dist/client/router";
import { api, WrappedResponse } from "../../utils/api";
import { GetStaticProps, GetStaticPropsResult } from "next";
import { NextSeo } from "next-seo";
import { Invitation } from "@flasher/models";

enum Status {
  Loading,
  Success,
  Error,
  NonValid,
  Expired,
  AlreadyAccepted,
}

type Props = GlobalProps;

const InvitationValidate: FunctionComponent<Props> = ({
  appName,
  socialMedias,
}: Props) => {
  const [status, setStatus] = useState<Status>(Status.Loading);
  const router = useRouter();

  useEffect(() => {
    const code = router.query.code;
    if (!code) {
      setStatus(Status.Error);
      throw new Error("Invitation code is not provided.");
    }
    api<WrappedResponse<Invitation>>(`/invitations/${code}/accept`)
      // .then((res) => res.json())
      .then(async (res) => {
        if (res.response?.status === 400) {
          const message = (await res.response?.json()).data.message;
          if (message === "ACCEPTED") {
            setStatus(Status.AlreadyAccepted);
            return;
          }
          if (message === "EXPIRED") {
            setStatus(Status.Expired);
            return;
          }
          setStatus(Status.Expired);
          return;
        }
        if (res.response?.status === 404) {
          setStatus(Status.NonValid);
          return;
        }

        if (res.response.ok === false) {
          setStatus(Status.Error);
        }

        setStatus(Status.Success);
      });
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const message = (): string => {
    if (status === Status.Loading) {
      return "Vérificaton en cours de votre invitation...";
    }

    if (status === Status.Error) {
      return "Une erreur a eu lieu";
    }

    if (status === Status.NonValid) {
      return "Code non valide";
    }

    if (status === Status.AlreadyAccepted) {
      return "Ce code a déjà été utilisé";
    }

    if (status === Status.Expired) {
      return "Code expiré";
    }

    if (status === Status.Success) {
      return "Invitation validée";
    }

    throw new Error("Unale to get message");
  };

  return (
    <Layout socialMedias={socialMedias} appName={appName}>
      <NextSeo
        title={`Validation invitation | ${appName}`}
        description="Valider mon invitation en tant que cosplayer"
        openGraph={{
          title: `Validation invitation | ${appName}`,
          description: "Valider mon invitation en tant que cosplayer",
        }}
      />
      <div>
        <Header>
          <div className="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div className="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div className="mb-8 text-center">
                {status === Status.Success && (
                  <div className="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:h-10 sm:w-10 mb-4">
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
                  <div className="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:h-10 sm:w-10 mb-4">
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

                <h1 className="text-gray-900 font-bold text-xl mb-2">
                  {message}
                </h1>

                {status === Status.Success && (
                  <div>
                    <span>
                      Vous pouvez dorénavant accéder à toutes vos photos.
                    </span>
                    <div className="mt-8">
                      <Link href={{ pathname: "/me/albums" }}>
                        <a className="mt-12 bg-gradient-to-r mx-auto from-blue-700 to-red-700 hover:from-pink-500 hover:to-orange-500 text-white font-semibold py-3 px-10 rounded-lg">
                          Accéder à mes albums
                        </a>
                      </Link>
                    </div>
                  </div>
                )}
                {status >= 2 && (
                  <div className="text-gray-700 text-base">
                    <span>
                      N'hésitez pas à me contarter pour remédier à cela.
                    </span>
                    <div className="mt-8">
                      <Link href={{ pathname: "/", hash: "#contact" }}>
                        <a className="mt-12 bg-gradient-to-r mx-auto from-blue-700 to-red-700 hover:from-pink-500 hover:to-orange-500 text-white font-semibold py-3 px-10 rounded-lg">
                          Prendre contact
                        </a>
                      </Link>
                    </div>
                  </div>
                )}
              </div>
            </div>
          </div>
        </Header>
      </div>
    </Layout>
  );
};

export default InvitationValidate;

export const getStaticProps: GetStaticProps = async (): Promise<
  GetStaticPropsResult<Props>
> => {
  const global = await getGlobalProps();

  return { props: { ...global } };
};
