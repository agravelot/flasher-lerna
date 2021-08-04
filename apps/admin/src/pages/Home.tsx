import { apiRepository } from "@flasher/common/src";
import { DashboardData } from "@flasher/models";
import { useKeycloak } from "@react-keycloak/web";
import { FunctionComponent, useEffect, useState } from "react";
import Dashboard from "../components/Dashboard";

const Home: FunctionComponent = () => {
  const { keycloak, initialized } = useKeycloak();

  const [dashboardData, setdashboardData] = useState<DashboardData>();

  useEffect(() => {
    if (!initialized) {
      return;
    }

    const repo = apiRepository(keycloak);
    repo.admin.dashboard().then((data) => {
      setdashboardData(data);
    });
  }, [initialized]);

  return <div>{dashboardData && <Dashboard {...dashboardData} />}</div>;
};

export default Home;
