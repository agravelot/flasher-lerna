import Script from "next/script";

interface Config {
  id: string;
  enable: boolean;
}

const config: Config = {
  id: process.env.NEXT_PUBLIC_CLARITY_ID ?? "",
  enable: process.env.NEXT_PUBLIC_CLARITY_ENABLE === "true",
};

export const Clarity = (): JSX.Element => {
  return config.enable ? (
    <Script
      id="clarity-script"
      dangerouslySetInnerHTML={{
        __html: `
    (function(c,l,a,r,i,t,y){
      c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
      t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
      y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "${config.id}");
  `,
      }}
      strategy="afterInteractive"
    />
  ) : (
    <></>
  );
};
