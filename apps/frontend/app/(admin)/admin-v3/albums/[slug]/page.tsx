import { Inter } from "next/font/google";
import { Uploader } from "./Uploader";
import { AlbumServiceClient } from "../../../../../gen/albums/v2/albums_pb.client";
import { ChannelCredentials } from "@grpc/grpc-js";
import { GrpcTransport } from "@protobuf-ts/grpc-transport";

const rpcTransport = new GrpcTransport({
  host: "127.0.0.1:3100",
  channelCredentials: ChannelCredentials.createInsecure(),
});

const client = new AlbumServiceClient(rpcTransport);

const inter = Inter({ subsets: ["latin"] });

// const config = new Configuration({
//   basePath: "https://api.jkanda.fr",
// });

export default async function AdminAlbumsShow({ params }) {
  console.log({ params });
  // const session = await getServerSession(authOptions);
  // const albumsApi = new AlbumsApi(config);

  const album = await client.getBySlug({
    slug: params.slug,
  });

  console.log(album);

  // const albumServiceIndexResponse = await albumsApi.albumServiceGetBySlug({
  //   slug: params.slug,
  // });

  if (!album.response.album) {
    // TODO 404
    throw new Error("No such album");
  }

  return (
    <main className={inter.className}>
      {/*<pre>*/}
      {/*  {JSON.stringify(*/}
      {/*    album.response,*/}
      {/*    (key, value) =>*/}
      {/*      typeof value === "bigint" ? value.toString() : value,*/}
      {/*    2,*/}
      {/*  )}*/}
      {/*</pre>*/}
      <Uploader ressource={"albums"} id={album.response.album.id} />
    </main>
  );
}
