import { useSearch } from "contexts/AppContext";
import { useRouter } from "next/router";
import { FunctionComponent, useEffect, useState } from "react";

export const SearchOpener: FunctionComponent = () => {
    const { open, setGoogleSearch } = useSearch();
    const {query, isReady} = useRouter();
    const [status, setStatus] = useState<boolean>(false);
    
    useEffect(() => {
        if(!isReady)
        {
            return;
        }
        const search = query.search;
        
        if(search != undefined && !status)
        {
            setGoogleSearch(search.toString());
            open();
            setStatus(true);
        }
    }, [open, query, isReady, setGoogleSearch, status, setStatus]);
    return null;
};