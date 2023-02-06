import { useSearch } from "contexts/AppContext";
import { useRouter } from "next/router";
import { FunctionComponent, useEffect } from "react";

const SearchOpener: FunctionComponent = () => {
    const { open } = useSearch();
    const {query, isReady} = useRouter();
    
    useEffect(() => {
        if(!isReady)
        {
            return undefined;
        }
        const {search} = query;
        console.log(search);
        if(search != "")
        {
            open();
        }
    }, [open, query, isReady]);
    return <div></div>;
};

export default SearchOpener;