import { apiClient } from "@flasher/common/src";
import { DashboardData } from "@flasher/models";
import { FunctionComponent, useEffect, useState } from "react";
import Dashboard from "../components/Dashboard";
import { useAuthentication } from "../hooks/useAuthentication";

const Home: FunctionComponent = () => {
  const { keycloak, initialized } = useAuthentication();

  const [dashboardData, setDashboardData] = useState<DashboardData>();

  useEffect(() => {
    if (!initialized || !keycloak) {
      return;
    }

    const repo = apiClient(keycloak);
    repo.admin.dashboard().then((data) => {
      setDashboardData(data);
    });
  }, [initialized]);

  return <div>{dashboardData && <Dashboard {...dashboardData} />}</div>;
};

export default Home;
