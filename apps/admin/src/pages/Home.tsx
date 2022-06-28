import { apiRepository } from "@flasher/common/src";
import { DashboardData } from "@flasher/models";
import { FunctionComponent, useEffect, useState } from "react";
import Dashboard from "../components/Dashboard";
import { useAuthentication } from "../hooks/useAuthentication";

const Home: FunctionComponent = () => {
  const { keycloak, initialized } = useAuthentication();

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
