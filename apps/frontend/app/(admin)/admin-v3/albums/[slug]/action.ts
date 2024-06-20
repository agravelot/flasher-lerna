"use server";

import { ChannelCredentials } from "@grpc/grpc-js";
import { GrpcTransport } from "@protobuf-ts/grpc-transport";
import { MediaServiceClient } from "../../../../../gen/medias/v2/medias_pb.client";
import { MediasResourceType } from "../../../../../gen/medias/v2/medias_pb";
import { getServerSession } from "next-auth/next";
import { authOptions } from "../../../../../pages/api/auth/[...nextauth]";

const rpcTransport = new GrpcTransport({
  host: "127.0.0.1:3100",
  channelCredentials: ChannelCredentials.createInsecure(),
});
const client = new MediaServiceClient(rpcTransport);

interface CreateUploadLinksFormData {
  type: "albums";
  resourceId: string;
  files: string;
}

export type ActionResponse = Record<string, string>;

export default async function createUploadLinks(
  prevState: unknown,
  formData: FormData,
): Promise<ActionResponse> {
  "use server";

  // TODO CHeck auth

  try {
    const session = await getServerSession(authOptions);

    const rawFormData: CreateUploadLinksFormData = {
      type: formData.get("type"),
      resourceId: formData.get("resourceId"),
      files: formData.get("files[]"),
    };

    console.log({ rawFormData, prevState });

    const test = await client.create(
      {
        type: MediasResourceType.ALBUMS,
        resourceID: Number(rawFormData.resourceId),
        fileNames: rawFormData.files.split(","),
      },
      {
        meta: {
          // TODO throw not auth?
          Authorization: `Bearer ${session?.access_token}`,
        },
      },
    );

    console.log(test.response);

    return test.response.fileUploadUrls;
  } catch (error) {
    console.error(error);
    throw new Error("Failed to create upload links");
  }
}
