import { Album } from "@flasher/models";
import { FunctionComponent } from "react";
import { format } from "date-fns";
import { fr } from "date-fns/locale";

export interface AlbumTableProps {
  albums: Album[];
}

const AlbumTable: FunctionComponent<AlbumTableProps> = ({
  albums,
}: AlbumTableProps) => {
  return (
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
            <th>Name</th>
            <th>Categories</th>
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
                        <img
                          src={a.media?.url}
                          alt="Avatar Tailwind CSS Component"
                        />
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
                  <button className="btn btn-ghost btn-xs">details</button>
                </th>
              </tr>
            );
          })}
        </tbody>
        <tfoot>
          <tr>
            <th></th>
            <th>Name</th>
            <th>Categories</th>
            <th></th>
          </tr>
        </tfoot>
      </table>

      <div className="btn-group">
        <button className="btn">Previous</button>
        <button className="btn">1</button>
        <button className="btn btn-active">2</button>
        <button className="btn">3</button>
        <button className="btn">4</button>
        <button className="btn">Next</button>
      </div>
    </div>
  );
};

export default AlbumTable;
