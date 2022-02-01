import React, {ChangeEvent, useState} from "react";
import styled from "styled-components";
import {H3} from "./Styled";
import {Input} from "./Search";
import {getParams} from "./helpers/URL";
import {Clear} from "./Brands";

const Wrapper = styled.div`
    margin-top: 1rem;
`;

const Span = styled.span`
    font-family: 'Nunito', sans-serif;
`;

interface Props {
    onChange: any,
    min: number,
    max: number
}

const Price = (props: Props) => {
    const {onChange, min, max} = props;

    const [minPrice, setMinPrice] = useState(getParams().minPrice)
    const [maxPrice, setMaxPrice] = useState(getParams().maxPrice)

    function changeMin(e: ChangeEvent<HTMLInputElement>) {
        setMinPrice(e.target.value)
        onChange([{prop: "minPrice", value: e.target.value}])
    }

    function changeMax(e: ChangeEvent<HTMLInputElement>) {
        setMaxPrice(e.target.value)
        onChange([{prop: "maxPrice", value: e.target.value}])
    }

    function clearMin() {
        setMinPrice("");
        onChange([{prop: "minPrice", value: ""}])
    }

    function clearMax() {
        setMaxPrice("");
        onChange([{prop: "maxPrice", value: ""}])
    }

    return (
        <Wrapper>
            <H3>Price ($)</H3>

            <div>
                <Span>Min:</Span>

                <Input
                    value={minPrice}
                    placeholder={min ? min.toString() : ""}
                    type="text"
                    onChange={changeMin}
                />

                <Clear onClick={clearMin}>Clear</Clear>
            </div>

            <br/>

            <div>
                <Span>Max:</Span>

                <Input
                    value={maxPrice}
                    placeholder={max ? max.toString() : ""}
                    type="text"
                    onChange={changeMax}
                />

                <Clear onClick={clearMax}>Clear</Clear>
            </div>
        </Wrapper>
    )
}

export default Price;
