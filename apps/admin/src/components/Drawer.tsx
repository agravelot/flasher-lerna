import { useKeycloak } from "@react-keycloak/web";
import { FunctionComponent } from "react";
import { Link } from "react-router-dom";

interface DrawerProps {
  children: JSX.Element;
}

const Drawer: FunctionComponent<DrawerProps> = ({ children }: DrawerProps) => {
  const { keycloak } = useKeycloak();

  return (
    <div className="rounded-lg shadow bg-base-200 drawer drawer-mobile h-full">
      <input id="my-drawer-2" type="checkbox" className="drawer-toggle" />
      <div className="flex flex-col items-center drawer-content">
        <label
          htmlFor="my-drawer-2"
          className="mb-4 btn btn-primary drawer-button lg:hidden"
        >
          open menu
        </label>
        {children}
      </div>
      <div className="drawer-side">
        <label htmlFor="my-drawer-2" className="drawer-overlay"></label>
        <ul className="menu p-4 overflow-y-auto w-80 bg-base-100 text-base-content">
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
            <a>ID : {keycloak.tokenParsed?.sub}</a>
          </li>
          <li className="text-red-300">
            <a onClick={() => keycloak.logout()}>Se deconnecter</a>
          </li>
        </ul>
      </div>
    </div>
  );
};

export default Drawer;
