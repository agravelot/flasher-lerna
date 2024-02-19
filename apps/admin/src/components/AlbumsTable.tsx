import { Album } from "@flasher/models";
import { FC } from "react";
import { format } from "date-fns";
import { fr } from "date-fns/locale";
import { generateNextImageUrl, resolveAlbumStatus } from "@flasher/common";
import { Link } from "react-router-dom";

export interface AlbumTableProps {
  albums: Album[];
}

const AlbumTable: FC<AlbumTableProps> = ({
  albums,
}: AlbumTableProps) => {
  return (
    <>
      <Link className="btn" to="/albums/create">
        Ajouter
      </Link>
      <div className="overflow-x-auto">
        <table className="table w-full">
          <thead>
            <tr>
              <th>
                <label>
                  <input type="checkbox" className="checkbox" />
                  <span className="checkbox-mark"></span>
                </label>
              </th>
              <th>Titre</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            {albums.map((a) => {
              return (
                <tr key={a.id}>
                  <th>
                    <label>
                      <input type="checkbox" className="checkbox" />
                      <span className="checkbox-mark"></span>
                    </label>
                  </th>
                  <td>
                    <div className="flex items-center space-x-3">
                      <div className="avatar">
                        <div className="w-12 h-12 mask mask-squircle">
                          {a.media && (
                            <img
                              src={`https://jkanda.fr${generateNextImageUrl(
                                a.media?.url,
                                640
                              )}`}
                              alt={`Image ${a.title}`}
                            />
                          )}
                        </div>
                      </div>
                      <div>
                        <div className="font-bold">{a.title}</div>
                        <div className="text-sm opacity-50">
                          {a.published_at &&
                            format(new Date(a.published_at), "dd/MM/yyyy", {
                              locale: fr,
                            })}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div className="flex items-center space-x-3">
                      <div data-tip="neutral" className="tooltip">
                        {resolveAlbumStatus(a) === "draft" && (
                          <div className="badge">Brouillon</div>
                        )}
                        {resolveAlbumStatus(a) === "private" && (
                          <div className="badge bg-red-600">Privé</div>
                        )}
                        {resolveAlbumStatus(a) === "published" && (
                          <div className="badge badge-accent">Publié</div>
                        )}
                      </div>
                    </div>
                  </td>
                  <td>
                    {a.categories?.map((c) => (
                      <span
                        className="badge badge-outline badge-sm mx-1"
                        key={c.id}
                      >
                        {c.name}
                      </span>
                    ))}
                  </td>
                  <th>
                    <Link
                      to={`/albums/${a.slug}`}
                      className="btn btn-ghost btn-xs"
                    >
                      détails
                    </Link>
                  </th>
                </tr>
              );
            })}
          </tbody>
          <tfoot>
            <tr>
              <th></th>
              <th>Titre</th>
              <th>Status</th>
              <th></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </>
  );
};

export default AlbumTable;
