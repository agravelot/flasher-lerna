import { FC, ReactNode } from "react";
import { Link } from "react-router-dom";
import { useAuthentication } from "../hooks/useAuthentication";

interface DrawerProps {
  children: ReactNode;
}

const Drawer: FC<DrawerProps> = ({ children }: DrawerProps) => {
  const { keycloak, parsedToken } = useAuthentication();

  return (
    <div className="drawer lg:drawer-open">
      <input id="my-drawer-2" type="checkbox" className="drawer-toggle" />
      <div className="drawer-content flex flex-col items-center justify-center">
        <label
          htmlFor="my-drawer-2"
          className="btn btn-primary drawer-button lg:hidden"
        >
          Open drawer
        </label>
        {children}
      </div>
      <div className="drawer-side">
        <label
          htmlFor="my-drawer-2"
          aria-label="close sidebar"
          className="drawer-overlay"
        ></label>
        <ul className="menu p-4 w-80 min-h-full bg-base-200 text-base-content">
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
            <a onClick={() => keycloak?.logout()}>Se déconnecter</a>
          </li>
        </ul>
      </div>
    </div>
  );
};

export default Drawer;
