import React, {ReactNode, useState} from "react";
import {FunctionComponent, ReactElement} from "react";
import styled from "styled-components";
import Product from "./model/Product";
import {Button, FilterProps} from "./App";
import ArrowDown from "./icons/ArrowDown";
import ArrowUp from "./icons/ArrowUp";
import {getParams, removeFromURL} from "./helpers/URL";
import {getAPI} from "./Api";

const Wrapper = styled.div`
    display: block;
    border-radius: 4px;
    position: relative;
`;

const Headers = styled.div`
    background: #2d3748;
    display: flex;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
`;

const Header = styled.div`
    font-family: 'Nunito', sans-serif;
    flex: 1;
    color: white;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    cursor: pointer;
`;

const Rows = styled.div``;

const Row = styled.div`
    width: 100%;
    display: flex;
    height: 80px;
`;

const RowItem = styled.div`
    font-family: 'Nunito', sans-serif;
    flex: 1;
    border: 1px solid lightgray;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    text-align: center;
`;

const Loader = styled.div`
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    color: white;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Nunito', sans-serif;
    font-size: 18px;
`;

const NoResults = styled.div`
    font-family: 'Nunito', sans-serif;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 5rem;
`;

interface Props {
    products: Product[],
    onSort: (array: FilterProps) => void,
    loading?: boolean,
    onDelete: () => void,
    onSelectProduct: () => void
}

const headers = [
    {name: "Name", value: "name"},
    {name: "Barcode", value: "barcode"},
    {name: "Brand", value: "brand"},
    {name: "Price", value: "price"},
    {name: "Image", value: "image"},
    {name: "Date Added", value: "date"},
];

type HeaderType = { name: string, desc?: boolean, value: string }

const Products: FunctionComponent<Props> = (props: Props): ReactElement => {
    const {products, onSort, loading, onDelete, onSelectProduct} = props;

    const [sortDirection, setSortDirection] = useState<string | null>(getParams().sortDirection ? "desc" : "asc");
    const [sortValue, setSortValue] = useState<string | null>(getParams().sort);
    const [selected, setSelected] = useState();

    function sortAsc(header: HeaderType) {
        const toSort = [{prop: "sort", value: header.value}];

        removeFromURL("sortDirection");

        onSort(toSort);

        setSortDirection("asc");

        setSortValue(header.value);
    }

    function sortDesc(header: HeaderType) {
        const toSort = [{prop: "sort", value: header.value}];

        toSort.push({prop: "sortDirection", value: "desc"});

        onSort(toSort);

        setSortDirection("desc");

        setSortValue(header.value);
    }

    function removeSort() {
        setSortValue(null)

        removeFromURL("sortDirection");
        removeFromURL("sort");

        onSort([]);
    }

    function onClick(header: HeaderType) {
        if (header.value !== sortValue) {
            sortAsc(header);

            return;
        }

        if (sortDirection === undefined) {
            sortAsc(header);

            return;
        }

        if (sortDirection === "asc") {
            sortDesc(header);

            return;
        }

        if (sortDirection === "desc") {
            removeSort();

            return;
        }
    }

    function sortClick(product) {
        if (selected === product.id) {
            setSelected(null)
            onSelectProduct(null)
        } else {
            setSelected(product.id)
            onSelectProduct(product)
        }
    }

    function onClickDelete(product) {
        getAPI().delete(product.id).then(res => onDelete(res.data))
    }

    return (
        <Wrapper>
            {/*{loading && <Loader>Loading</Loader>}*/}

            <Headers>
                <Header>Select</Header>

                {headers.map((header: HeaderType) => (
                    <Header
                        key={header.value}
                        onClick={() => onClick(header)}
                    >
                        {header.name} &nbsp;

                        {sortValue === header.value && sortDirection ? (sortDirection === "desc" ? <ArrowUp/> :
                            <ArrowDown/>) : null}
                    </Header>
                ))}

                <Header>Actions</Header>
            </Headers>

            <Rows>
                {!products.length && <NoResults>No results</NoResults>}

                {products.map((product: Product) => (
                    <Row
                        key={product.id}
                    >
                        <RowItem>
                            <input
                                onChange={() => {}}
                                style={{cursor: "pointer"}}
                                type="radio"
                                checked={product.id === selected}
                                onClick={() => sortClick(product)}
                            />
                        </RowItem>

                        <RowItem>
                            {product.name}
                        </RowItem>

                        <RowItem>
                            {product.barcode}
                        </RowItem>

                        <RowItem>
                            {product.brand && product.brand.name}
                        </RowItem>

                        <RowItem>
                            {product.price}
                        </RowItem>

                        <RowItem>
                            {product.image_url &&
                                <img
                                    width="60"
                                    height="60"
                                    src={product.image_url}
                                    alt=""
                                />
                            }
                        </RowItem>

                        <RowItem>
                            {product.date_added}
                        </RowItem>

                        <RowItem>
                            <Button
                                style={{width: "50%"}}
                                onClick={() => onClickDelete(product)}
                            >
                                Delete
                            </Button>
                        </RowItem>
                    </Row>
                ))}
            </Rows>
        </Wrapper>
    )
}

export default Products;
