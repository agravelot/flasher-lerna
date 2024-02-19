import { FC } from "react";
import { Link } from "react-router-dom";
import { useAuthentication } from "../hooks/useAuthentication";

interface DrawerProps {
  children: JSX.Element;
}

const Drawer: FC<DrawerProps> = ({ children }: DrawerProps) => {
  const { keycloak, parsedToken } = useAuthentication();

  return (
    <div className="drawer-mobile drawer h-full rounded-lg bg-base-200 shadow">
      <input id="my-drawer-2" type="checkbox" className="drawer-toggle" />
      <div className="drawer-content flex flex-col items-center">
        <label
          htmlFor="my-drawer-2"
          className="btn btn-primary drawer-button mb-4 lg:hidden"
        >
          open menu
        </label>
        {children}
      </div>
      <div className="drawer-side">
        <label htmlFor="my-drawer-2" className="drawer-overlay"></label>
        <ul className="menu w-80 overflow-y-auto bg-base-100 p-4 text-base-content">
          <li>
            <Link to="/">Dashboard</Link>
          </li>
          <li>
            <Link to="/articles">Articles</Link>
          </li>
          <li>
            <Link to="/albums">Albums</Link>
          </li>
          <li>
            <a>ID : {parsedToken?.sub}</a>
          </li>
          <li className="text-red-300">
            <a onClick={() => keycloak?.logout()}>Se deconnecter</a>
          </li>
        </ul>
      </div>
    </div>
  );
};

export default Drawer;
