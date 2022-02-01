interface Product {
    id: number,
    name: string,
    barcode: string,
    price: number,
    brand: { name: string },
    date_added: string,
    image_url: string,
}

export default Product;
