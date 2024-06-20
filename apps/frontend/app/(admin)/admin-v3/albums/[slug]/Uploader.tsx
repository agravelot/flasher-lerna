"use client";

import { ChangeEvent, FC, useEffect, useMemo, useState } from "react";
import createUploadLinks, { ActionResponse } from "./action";
import { useFormState, useFormStatus } from "react-dom";

type Item = {
  file: File;
  uploadUrl?: string;
};

type Props = {
  ressource: "albums";
  id: string;
};

export const Uploader: FC<Props> = ({ ressource, id }: Props) => {
  const [items, setFiles] = useState<Item[]>([]);
  const { pending } = useFormStatus();
  const [state, formAction] = useFormState<ActionResponse>(
    createUploadLinks,
    {},
  );

  const allUrlInitialized = useMemo(
    () => items.every((item) => !!item.uploadUrl),
    [items],
  );

  useEffect(() => {
    setFiles((items) =>
      items.map((item) => ({
        ...item,
        uploadUrl: state[`${ressource}/${id}/${items[0]?.file.name}`],
      })),
    );
  }, [ressource, id, state]);

  const handleFilesChange = (e: ChangeEvent<HTMLInputElement>) => {
    const files = Array.from(e.target.files ?? []).map((file) => ({ file }));
    setFiles(files);
  };

  const uploadAllPending = () => {
    console.log("uploadAllPending");
    return Promise.all(items.map((item) => upload(item)));
  };

  const upload = async (item: Item) => {
    // const uploadProgress = 0;
    // const totalBytes = item.file.size;
    // const bytesUploaded = 0;

    // Use a custom TransformStream to track upload progress
    // const progressTrackingStream = new TransformStream({
    //   transform(chunk, controller) {
    //     controller.enqueue(chunk);
    //     bytesUploaded += chunk.byteLength;
    //     uploadProgress = bytesUploaded / totalBytes;
    //     console.log(
    //       `upload progress: ${uploadProgress}%, bytesUploaded: ${bytesUploaded}/${totalBytes}`,
    //     );
    //   },
    //   flush(controller) {
    //     console.log("completed stream");
    //   },
    // });

    // TODO resolve urls
    // const response = await fetch(state["albums/1/" + item.file.name] ?? "", {
    //   method: "PUT",
    //   headers: {
    //     "Content-Type": "application/octet-stream",
    //   },
    //   body: item.file.stream().pipeThrough(progressTrackingStream),
    //   duplex: "half",
    // });

    if (!item.uploadUrl) {
      throw new Error("missing uploadUrl");
    }

    const response = await fetch(item.uploadUrl, {
      method: "PUT",
      body: item.file,
      // keepalive: true,
    });
    console.log(`response: ${response.statusText}`);
  };

  return (
    <div>
      {items.map((item, index) => (
        <div className="flex" key={index}>
          <div>{item.file.name}</div>
          <div>{item.file.type}</div>
          <div>{item.file.size}</div>
        </div>
      ))}
      <input type="file" multiple onChange={handleFilesChange} />

      {!allUrlInitialized && (
        <form action={formAction}>
          <input name="type" type="text" hidden value={ressource} />
          <input name="resourceId" type="text" hidden value={id} />
          {items.map((item, index) => (
            <input
              key={index}
              type="text"
              name="files[]"
              hidden
              value={item.file.name}
            />
          ))}
          <button type="submit" aria-disabled={pending}>
            Send
          </button>
        </form>
      )}

      <pre>urls: {items.map((item) => item.uploadUrl)}</pre>

      <button onClick={() => uploadAllPending()}>send 2</button>
    </div>
  );
};
