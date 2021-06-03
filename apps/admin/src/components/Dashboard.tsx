import { FunctionComponent } from "react";

export interface DashboardProps {
  albumsCount: number;
  mediaCount?: number;
  contactCount?: number;
  invitationCount?: number;
  articleCount?: number;
  userCount?: number;
}

const Dashboard: FunctionComponent<DashboardProps> = (
  props: DashboardProps
) => {
  return (
    <div className="stats shadow w-full">
      <div className="stat place-items-center place-content-center">
        <div className="stat-title">Albums</div>
        <div className="stat-value">{props.albumsCount}</div>
        <div className="stat-desc"></div>
      </div>
      <div className="stat place-items-center place-content-center">
        <div className="stat-title">Users</div>
        <div className="stat-value text-success">{props.userCount}</div>
        <div className="stat-desc text-success">↗︎ 400 (22%)</div>
      </div>
      <div className="stat place-items-center place-content-center">
        <div className="stat-title">New Registers</div>
        <div className="stat-value text-error">1,200</div>
        <div className="stat-desc text-error">↘︎ 90 (14%)</div>
      </div>
    </div>
  );
};

export default Dashboard;
