import { Inter } from "next/font/google";
import { getServerSession } from "next-auth/next";
import { authOptions } from "pages/api/auth/[...nextauth]";
import { AlbumTable } from "components/admin/albums/AlbumsTable";
import { Login } from "components/auth/login";
import { AlbumServiceClient } from "../../../../gen/albums/v2/albums_pb.client";

import { ChannelCredentials } from "@grpc/grpc-js";

import { GrpcTransport } from "@protobuf-ts/grpc-transport";
import { Album } from "@flasher/models";
const inter = Inter({ subsets: ["latin"] });

export default async function AdminAlbumsList() {
  const rpcTransport = new GrpcTransport({
    host: "127.0.0.1:3100",
    channelCredentials: ChannelCredentials.createInsecure(),
  });
  const client = new AlbumServiceClient(rpcTransport);

  const albums = await client.index({
    limit: 10,
  });

  const session = await getServerSession(authOptions);

  return (
    <main className={inter.className}>
      <Login />
      <pre>session: {JSON.stringify(session, null, 2)}</pre>
      <AlbumTable
        albums={albums.response.data.map((album): Album => {
          return {
            links: {
              view: "",
            },
            body: album.content,
            categories: [],
            cosplayers: [],
            created_at: "",
            id: album.id,
            media: undefined,
            medias: undefined,
            meta_description: "",
            private: album.private,
            published_at: new Date().toISOString(),
            slug: album.slug,
            title: album.title,
            updated_at: new Date().toISOString(),
            user_id: "",
          };
        })}
      />
    </main>
  );
}
