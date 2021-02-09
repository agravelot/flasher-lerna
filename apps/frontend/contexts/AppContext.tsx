import {
  createContext,
  ReactElement,
  ReactNode,
  useContext,
  useMemo,
  useState,
} from "react";

export enum SearchStatus {
  Opened = "opened",
  Closed = "closed",
}

interface ContextProps {
  status: SearchStatus;
  open: () => void;
  close: () => void;
}

interface Props {
  children: ReactNode;
}

export const SearchContext = createContext<ContextProps | undefined>(undefined);

export const SearchContextProvider = ({ children }: Props): ReactElement => {
  const [status, setStatus] = useState<SearchStatus>(SearchStatus.Closed);

  const open = () => setStatus(SearchStatus.Opened);
  const close = () => setStatus(SearchStatus.Closed);

  // Only trigger refresh on 'status' update.
  // https://stackoverflow.com/a/57840598
  const providerValue = useMemo(
    () => ({
      status,
      open,
      close,
    }),
    [status]
  );

  return (
    <SearchContext.Provider value={providerValue}>
      {children}
    </SearchContext.Provider>
  );
};

export const useSearch = (): ContextProps => {
  const context = useContext(SearchContext);
  if (!context) {
    throw new Error("Context not initialized.");
  }
  return context;
};
