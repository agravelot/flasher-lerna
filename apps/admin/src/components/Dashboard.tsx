import { FC } from "react";

export interface DashboardProps {
  cosplayersCount: number;
  usersCount: number;
  albumsCount: number;
  contactsCount: number;
  albumMediasCount: number;
}

const Dashboard: FC<DashboardProps> = (
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
        <div className="stat-value text-success">{props.usersCount}</div>
        <div className="stat-desc text-success">↗︎ 400 (22%)</div>
      </div>
      <div className="stat place-items-center place-content-center">
        <div className="stat-title">Contact</div>
        <div className="stat-value text-success">{props.contactsCount}</div>
        <div className="stat-desc text-success">↗︎ 400 (22%)</div>
      </div>
      <div className="stat place-items-center place-content-center">
        <div className="stat-title">Modèles</div>
        <div className="stat-value text-success">{props.cosplayersCount}</div>
        <div className="stat-desc text-success">↗︎ 400 (22%)</div>
      </div>
      <div className="stat place-items-center place-content-center">
        <div className="stat-title">Media</div>
        <div className="stat-value text-error">{props.albumMediasCount}</div>
        <div className="stat-desc text-error">↘︎ 90 (14%)</div>
      </div>
    </div>
  );
};

export default Dashboard;
