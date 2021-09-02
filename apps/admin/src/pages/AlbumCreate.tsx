import { FunctionComponent } from "react";
import { useHistory } from "react-router-dom";
import AlbumForm from "../components/AlbumForm";

const AlbumeCreate: FunctionComponent = () => {
  const history = useHistory();

  return (
    <div>
      <AlbumForm
        type="create"
        onPostSubmit={() => {
          history.push("/albums");
        }}
      />
    </div>
  );
};

export default AlbumeCreate;
