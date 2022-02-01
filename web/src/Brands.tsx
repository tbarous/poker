import React, {useEffect, useState} from "react";
import styled from "styled-components";
import {H3} from "./Styled";
import {getParams, removeFromURL} from "./helpers/URL";

interface Props {
    brands: any[],
    onChange: any
}

const BrandsStyled = styled.select`
    padding: .5rem;
    width: 100%;
    font-family: 'Nunito', sans-serif;
    background: white;
    outline: none!important;
    cursor: pointer;
`;

const Wrapper = styled.div`
    margin-top: 1rem;
`;

export const Clear = styled.span`
    margin-left: auto;
    display: inline-block;
    font-family: 'Nunito', sans-serif;
    font-size: 11px;
    cursor: pointer;
    text-align: right;
    float: right;
    color: red;
    margin-top: 6px;
`;

export const Option = styled.option`
  font-family: 'Nunito', sans-serif;
`;

const Brands = (props: Props) => {
    const {onChange} = props;

    const [selected, setSelected] = useState(getParams().brand || "");

    function onSelect(e: any) {
        setSelected(e.target.value);

        onChange([{prop: "brand", value: e.target.value}]);
    }

    function clear() {
        setSelected("");

        removeFromURL("brand");

        onChange([]);
    }

    return (
        <Wrapper>
            <H3>Filter by Brand </H3>

            <BrandsStyled
                value={selected}
                onChange={onSelect}
            >
                {props.brands.map(brand => {
                    return (
                        <Option
                            value={brand.name}
                            key={brand.name}
                        >
                            {brand.name}
                        </Option>
                    )
                })}
            </BrandsStyled>

            <Clear onClick={clear}>Clear</Clear>
        </Wrapper>
    )
}

export default Brands;
