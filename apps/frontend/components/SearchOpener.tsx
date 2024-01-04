import { useSearch } from "contexts/AppContext";
import { useRouter } from "next/router";
import { FC, useEffect, useReducer } from "react";

export const SearchOpener: FC = () => {
  const initialState = {
    search: "",
    status: false,
  };

  interface State {
    search: string;
    status: boolean;
  }

  interface Action {
    search: string | string[];
    type: string;
  }

  const { open, setGoogleSearch } = useSearch();
  const { query, isReady } = useRouter();
  const reducer = (state: State, action: Action) => {
    switch (action.type) {
      case "showResults":
        setGoogleSearch(action.search.toString());
        open();
        return { status: true, search: action.search.toString() };
      default:
        return state;
    }
  };
  const [state, dispatch] = useReducer(reducer, initialState);
  useEffect(() => {
    if (!isReady) {
      return;
    }
    const search = query.search;

    if (search != undefined && !state.status) {
      dispatch({ type: "showResults", search: search.toString() });
    }
  }, [open, query, isReady, setGoogleSearch, state.status]);
  return null;
};
