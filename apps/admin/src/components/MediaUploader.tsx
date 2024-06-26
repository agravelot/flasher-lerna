import { FC, memo, useEffect } from "react";
import Uppy from "@uppy/core";
import Tus from "@uppy/tus";
import { Dashboard } from "@uppy/react";
// import ImageEditor from "@uppy/image-editor";

import "@uppy/core/dist/style.css";
import "@uppy/dashboard/dist/style.css";
// import "@uppy/image-editor/dist/style.css";
import { useAuthentication } from "../hooks/useAuthentication";

export interface MediaUploaderProps {
  modelId: number;
  modelClass: "album";
  onUploadSuccess?: () => void;
}

// Use memo to avoid re-rendering the component on upload success
const MediaUploader: FC<MediaUploaderProps> = memo(
  function MediaUploader(props: MediaUploaderProps) {
    const { initialized, keycloak } = useAuthentication();

    useEffect(() => {
      return () => uppy.close();
    }, []);

    if (!initialized || !keycloak) {
      return null;
    }

    const uppy = new Uppy({
      debug: true,
      autoProceed: false,
      restrictions: {
        maxFileSize: 100000000,
        maxNumberOfFiles: 30,
        minNumberOfFiles: 1,
        allowedFileTypes: ["image/*", "video/*"],
        // requiredMetaFields: ["caption"],
      },
      meta: {
        modelClass: props.modelClass,
        modelId: props.modelId,
      },
      // onBeforeUpload: (files: { [key: string]: UppyFile }): any | boolean => {
      //   console.log(files);

      //   // return Object.values(files).map((file) => {
      //   //   return (file.tus.headers = {
      //   //     Authorization: "Bearer " + keycloak.token,
      //   //   });
      //   // });
      // },
    })
      // .use(Dashboard, {
      //   trigger: ".UppyModalOpenerBtn",
      //   inline: true,
      //   target: ".DashboardContainer",
      //   showProgressDetails: true,
      //   note: "Images and video only, 2–3 files, up to 1 MB",
      //   height: 470,
      //   metaFields: [
      //     { id: "name", name: "Name", placeholder: "file name" },
      //     {
      //       id: "caption",
      //       name: "Caption",
      //       placeholder: "describe what the image is about",
      //     },
      //   ],
      //   browserBackButtonClose: false,
      // })
      // .use(GoogleDrive, {
      //   target: Dashboard,
      //   companionUrl: "https://companion.uppy.io",
      // })
      // .use(Dropbox, {
      //   target: Dashboard,
      //   companionUrl: "https://companion.uppy.io",
      // })
      // .use(Box, { target: Dashboard, companionUrl: "https://companion.uppy.io" })
      // .use(Instagram, {
      //   target: Dashboard,
      //   companionUrl: "https://companion.uppy.io",
      // })
      // .use(Facebook, {
      //   target: Dashboard,
      //   companionUrl: "https://companion.uppy.io",
      // })
      // .use(OneDrive, {
      //   target: Dashboard,
      //   companionUrl: "https://companion.uppy.io",
      // })
      // .use(Webcam, { target: Dashboard })
      // .use(ScreenCapture, { target: Dashboard })
      // .use(ImageEditor, { target: Dashboard })
      .use(Tus, {
        // endpoint: "https://api.jkanda.fr/tus/",
        endpoint: "http://tusd.localhost/files/",
        headers: {
          Authorization: "Bearer " + keycloak.token,
        },
        removeFingerprintOnSuccess: true, // Allow reuploading the same file
      })
      .on("upload-success", () => {
        props.onUploadSuccess && setTimeout(props.onUploadSuccess, 1000);
      });

    // uppy.use(ImageEditor, {
    //   // target: Dashboard,
    //   quality: 1,
    //   actions: {
    //     revert: true,
    //     rotate: true,
    //     granularRotate: true,
    //     flip: true,
    //     zoomIn: true,
    //     zoomOut: true,
    //     cropSquare: true,
    //     cropWidescreen: true,
    //     cropWidescreenVertical: true,
    //   },
    //   // cropperOptions: {
    //   //   viewMode: 1,
    //   //   background: false,
    //   //   autoCropArea: 1,
    //   //   responsive: true,
    //   //   croppedCanvasOptions: {},
    //   // },
    // });

    // Concat uplload
    // Image Editor
    return (
      <div className="mt-8 flex flex-wrap justify-center">
        <Dashboard
          plugins={["ImageEditor"]}
          uppy={uppy}
          theme="auto"
          proudlyDisplayPoweredByUppy={false}
          locale={{
            strings: {
              // Text to show on the droppable area.
              // `%{browse}` is replaced with a link that opens the system file selection dialog.
              // dropHereOr: "Déposer ici ou %{browse}",
              // Used as the label for the link that opens the system file selection dialog.
              // browse: "explorer",
            },
          }}
        />
      </div>
    );
  }
);

export default MediaUploader;
