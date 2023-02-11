import { useSearch } from "contexts/AppContext";
import { useRouter } from "next/router";
import { FunctionComponent, useEffect } from "react";

export const SearchOpener: FunctionComponent = () => {
    const { open, setGoogleSearch } = useSearch();
    const {query, isReady} = useRouter();
    
    useEffect(() => {
        if(!isReady)
        {
            return;
        }
        const search = query.search;
        
        if(search != undefined)
        {
            setGoogleSearch(search.toString());
            open();
        }
    }, [open, query, isReady, setGoogleSearch]);
    return null;
};