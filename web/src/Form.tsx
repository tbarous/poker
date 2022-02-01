import React, {FunctionComponent, ReactElement, useEffect, useRef} from "react";
import {useState} from "react";
import {getAPI} from "./Api";
import {Input} from "./Search";
import {Button} from "./App";
import styled from "styled-components";
import Product from "./model/Product";

const FormStyled = styled.form<{ onSubmit: any, enctype: any }>`
    border: 1px solid lightgray;
    padding: 1rem;
    width: 300px;
    margin-top: 1rem;
`;

const Label = styled.div`
    font-family: 'Nunito', sans-serif;
`

interface Props {
    selectedProduct: Product,
    onSubmit: () => void
}

const Form: FunctionComponent<Props> = (props: Props): ReactElement => {
    const {selectedProduct} = props;

    const [name, setName] = useState("");
    const [barcode, setBarcode] = useState("");
    const [price, setPrice] = useState(0);
    const [brandId, setBrandId] = useState("");
    const [currentImage, setCurrentImage] = useState("");
    const [errors, setErrors] = useState(null);

    const ref = useRef();

    const isProductSelected = selectedProduct && selectedProduct.id;

    function onSubmit(e: SubmitEvent) {
        e.preventDefault();

        setErrors(null);

        const image = ref.current.files[0];

        if (isProductSelected) {
            getAPI().editProduct(
                name,
                barcode,
                price,
                selectedProduct.id,
                image,
                brandId
            ).then(submitAndReset);

            return;
        }

        getAPI().createProduct(
            name,
            barcode,
            price,
            image
        ).then(submitAndReset);
    }

    function submitAndReset(res) {
        if(res.status !== 200){
            setErrors(res.data.message)
            return;
        }

        props.onSubmit();

        reset();
    }

    function reset() {
        setName("");
        setBarcode("");
        setPrice(0);
        setBrandId("");

        setCurrentImage("");

        ref.current.value = "";
    }

    useEffect(() => {
        if (isProductSelected) {
            setName(selectedProduct.name)
            setBarcode(selectedProduct.barcode)
            setPrice(selectedProduct.price)
            setCurrentImage(selectedProduct.image_url);
            setBrandId(selectedProduct.brand && selectedProduct.id ? selectedProduct.brand.id : "")

            return
        }

        reset();
    }, [selectedProduct])

    return (
        <FormStyled
            onSubmit={onSubmit}
            enctype="multipart/form-data"
        >
            <Label>
                <label htmlFor="name">
                    <div>Name</div>

                    <Input
                        type="text"
                        value={name}
                        onChange={e => setName(e.target.value)}
                    />
                </label>
            </Label>

            <br/>

            <Label>
                <label htmlFor="barcode">
                    <div>Barcode</div>

                    <Input
                        type="text"
                        value={barcode}
                        onChange={e => setBarcode(e.target.value)}
                    />
                </label>
            </Label>

            <br/>

            <Label>
                <label htmlFor="price">
                    <div>Price</div>

                    <Input
                        type="text"
                        value={price}
                        onChange={e => setPrice(e.target.value)}
                    />
                </label>
            </Label>

            <br/>

            {currentImage && <img width={40} height={40} src={currentImage} alt=""/>}

            <br/>

            <Label>
                <label htmlFor="image">
                    <div>Image</div>

                    <Input
                        ref={ref}
                        type="file"
                        name="image"
                    />
                </label>
            </Label>

            <br/><br/>

            {errors && <div style={{color: 'red', fontSize: '11px'}}>{errors}</div>}

            <br/><br/>

            <Button type="submit">
                {props.selectedProduct && props.selectedProduct.id ? "Edit" : "Create"}
            </Button>
        </FormStyled>
    )
}

export default Form;
