import { FunctionComponent } from "react";
import { useNavigate } from "react-router-dom";
import AlbumForm from "../components/AlbumForm";

const AlbumeCreate: FunctionComponent = () => {
  const navigate = useNavigate();

  return (
    <div>
      <AlbumForm
        type="create"
        onPostSubmit={() => {
          navigate("/albums");
        }}
      />
    </div>
  );
};

export default AlbumeCreate;
