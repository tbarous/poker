import React, {FunctionComponent, ReactElement, useEffect, useState} from "react";
import styled, {keyframes} from "styled-components";
import Search from "./Search";
import Products from "./Products";
import Pagination from "./Pagination";
import axios from "axios";
import {getParams, removeFromURL, updateURL} from "./helpers/URL";
import Brands from "./Brands";
import Price from "./Price";
import Form from "./Form";
import {getAPI} from "./Api";

const Wrapper = styled.div`
    display: flex;
    width: 100%;
    box-sizing: border-box;
    padding: 1rem;
    flex-wrap: wrap;
    align-items: flex-start;
`;

const Left = styled.div`
    margin-right: 1rem;
`

const Filters = styled.div`
    flex: 1;
    border: 1px solid lightgray;
    display: flex;
    flex-direction: column;
    padding: 1rem;
    box-sizing: border-box;
    border-radius: 4px;
`;

export const Button = styled.button`
    border: none;
    padding: .5rem 1rem;
    background: purple;
    color: white;
    border-radius: 32px;
    font-family: 'Nunito', sans-serif;
    font-weight: bold;
    cursor: pointer;
    width: 100%;
`;


const alertAnimation = keyframes`
    from {
        transform: translateY(60px);
    }

    to {
        transform: translateY(0);
    }
`;

const Alert = styled.div`
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 60px;
    z-index: 999;
    background: ${p => p.type === "success" ? "green" : "red"};
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    animation: ${alertAnimation} .3s;
    font-family: 'Nunito', sans-serif;
`;

const Table = styled.div`
    flex: 6;
`;

export type FilterProps = { prop: string, value: string }[];

interface Data {
    products: {},
    minPrice: number,
    maxPrice: number
}

const App: FunctionComponent<{}> = (props: {}): ReactElement => {
    const [data, setData] = useState(window.data);

    const [loading, setLoading] = useState(false);

    function update() {
        setLoading(true);

        getAPI().filter(getParams())
            .then((res) => {
                if (res.data) {
                    setData({...res.data});
                }
            })
            .finally(() => {
                setLoading(false);
            })
    }

    function filter(array: FilterProps) {
        removeFromURL("page");

        for (const item of array) {
            if (item.prop) {
                if (item.value) {
                    updateURL(item.prop, item.value);
                } else {
                    removeFromURL(item.prop);
                }
            }
        }

        update();
    }

    function email() {
        getAPI().report(getParams()).then(() => showAlert({
            text: "Email with filtered results will be sent",
            type: "success"
        }))
    }

    function showAlert(alert) {
        setAlert(alert)
        setTimeout(() => {
            setAlert(null)
        }, 3000)
    }

    const [selectedProduct, setSelectedProduct] = useState(null);
    const [alert, setAlert] = useState(null);

    return (
        <Wrapper>
            <Left>
                <Filters>
                    <Search onChange={filter}/>

                    <Brands
                        brands={data.brands}
                        onChange={filter}
                    />

                    <Price
                        min={data.minPrice}
                        max={data.maxPrice}
                        onChange={filter}
                    />

                    <br/><br/>

                    <Button onClick={email}>Send Email</Button>
                </Filters>

                <Form
                    onSubmit={() => {
                        setAlert({text: "Success", type: "success"})
                        setTimeout(() => setAlert(null), 3000)
                        update()
                    }}
                    selectedProduct={selectedProduct}
                />
            </Left>

            <Table>
                {alert && <Alert type={alert.type}>{alert.text}</Alert>}

                <Products
                    products={Object.values(data.products.data)}
                    onSort={filter}
                    loading={loading}
                    onDelete={() => {
                        setAlert({text: "Successfully deleted product", type: "success"})
                        setTimeout(() => setAlert(null), 3000)
                        update()
                    }}
                    onSelectProduct={(product) => {
                        setSelectedProduct({...product})
                    }}
                />

                <Pagination
                    onClick={filter}
                    currentPage={data.products.current_page}
                    links={data.products.links}
                    numberOfPages={data.products.data.length}
                />
            </Table>
        </Wrapper>
    )
}

export default App;
