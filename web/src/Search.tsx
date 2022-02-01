import React, {ChangeEvent, ReactElement, useEffect, useState} from "react";
import {FunctionComponent} from "react";
import useDebounce from "./hooks/useDebounce";
import {getParams, removeFromURL} from "./helpers/URL";
import {H3} from "./Styled";
import {FilterProps} from "./App";
import styled from "styled-components";
import {Clear} from "./Brands";

export const Input = styled.input`
    padding: .5rem 0;
    border: none;
    border-bottom: 1px solid;
    font-family: 'Nunito', sans-serif;
    width: 100%;
    outline: none !important;
`;

interface Props {
    onChange: (array: FilterProps) => void
}

const params = getParams();

const Search: FunctionComponent<Props> = (props: Props): ReactElement => {
    const {onChange} = props;

    const [searchValue, setSearchValue] = useState(params.search);

    const debouncedSearchTerm = useDebounce(searchValue, 500);

    useEffect(() => {
        if (debouncedSearchTerm !== undefined) {
            onChange([{prop: "search", value: debouncedSearchTerm}]);
        }
    }, [debouncedSearchTerm]);

    function clear() {
        onChange([{prop: "search", value: ""}]);
        setSearchValue("");
    }

    function change(e: ChangeEvent<HTMLInputElement>) {
        setSearchValue(e.target.value)
    }

    return (
        <div>
            <H3>Search</H3>

            <Input
                type="text"
                value={searchValue}
                onChange={change}
            />

            <Clear onClick={clear}>Clear</Clear>
        </div>
    )
}


export default Search;
