import { FC } from "react";
import { useNavigate } from "react-router-dom";
import AlbumForm from "../components/AlbumForm";

const AlbumeCreate: FC = () => {
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
