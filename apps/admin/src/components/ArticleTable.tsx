import { FunctionComponent } from "react";
import { format } from "date-fns";
import { fr } from "date-fns/locale";
import { Link } from "react-router-dom";

interface Article {
  id: string;
  slug: string;
  name: string;
  publishedAt?: Date;
  metaDescription: string;
  content: string;
}

export interface ArticleListProps {
  articles: Article[];
  selected?: Article[];
  onSelectChange?: (articles: Article[]) => void;
}

const ArticleTable: FunctionComponent<ArticleListProps> = ({
  articles,
  onSelectChange,
  selected = [],
}: ArticleListProps) => {
  const onCheckboxChange = (article: Article) => {
    if (!onSelectChange) return;

    const position = selected?.findIndex((s) => s.id === article.id);

    if (position !== -1) {
      onSelectChange(selected.splice(position, 1));
    } else {
      onSelectChange([...selected, article]);
    }
  };

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
            <th>Titre</th>
            <th>Status</th>
            <th>Action</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          {articles.map((a) => {
            return (
              <tr key={a.id}>
                <th>
                  <label>
                    <input
                      type="checkbox"
                      className="checkbox"
                      checked={selected?.some((s) => s.id === a.id)}
                      onChange={() => onCheckboxChange(a)}
                    />
                    <span className="checkbox-mark"></span>
                  </label>
                </th>
                <td>
                  <div className="flex items-center space-x-3">
                    {/*<div className="avatar">*/}
                    {/*  <div className="mask mask-squircle h-12 w-12">*/}
                    {/*    <img*/}
                    {/*      src="/tailwind-css-component-profile-2@56w.png"*/}
                    {/*      alt="Avatar Tailwind CSS Component"*/}
                    {/*    />*/}
                    {/*  </div>*/}
                    {/*</div>*/}
                    <div>
                      <div className="font-bold">{a.name}</div>
                      <div className="text-sm opacity-50">
                        <>
                          {a.publishedAt &&
                            format(a.publishedAt, "dd/MM/yyyy", {
                              locale: fr,
                            })}
                        </>
                      </div>
                    </div>
                  </div>
                </td>
                <td>
                  <div className="flex items-center space-x-3">
                    <div data-tip="neutral" className="tooltip">
                      {a.publishedAt !== null ? (
                        <div className="badge badge-accent">Publié</div>
                      ) : (
                        <div className="badge">Brouillon</div>
                      )}
                    </div>
                  </div>
                </td>
                <th>
                  <Link to={`${a.slug}`} className="btn btn-ghost btn-xs">
                    details
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
            <th>Action</th>
            <th></th>
          </tr>
        </tfoot>
      </table>
    </div>
  );
};

export default ArticleTable;
