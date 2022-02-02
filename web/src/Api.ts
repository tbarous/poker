import {Axios, AxiosInstance} from "axios";

class API {
    private axios: Axios;
    private baseURL: string = "http://localhost:8080/api/";

    constructor() {
        // const element = document.head.querySelector("[name=_token][content]");

        // const token = element.content;

        this.axios = new Axios({
            headers: {
                // 'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            baseURL: this.baseURL,
            transformRequest: [(data, headers) => JSON.stringify(data)],
            transformResponse: [(data) => JSON.parse(data)],
        })
    }

    register(
        name: string,
        email: string,
        password: string,
        password_confirmation: string
    ) {
        this.axios.post("register", {
            name, email, password, password_confirmation
        })
            .then((res) => {
                console.log(res);
            })
    }


    createProduct(name: string, barcode: string, price: number, image = null) {
        const formData = new FormData();

        formData.append("name", name);
        formData.append("barcode", barcode);
        formData.append("price", price.toString());

        if (image) formData.append("image", image, image.name);

        return this.multipartAxiosInstance.post("products", formData);
    }

    editProduct(
        name: string,
        barcode: string,
        price: number,
        id: number,
        image = null,
        brandId: string
    ) {
        const formData = new FormData();

        formData.append("name", name);
        formData.append("barcode", barcode);
        formData.append("price", price.toString());
        formData.append("brand_id", brandId);

        if (image) formData.append("image", image, image.name);

        return this.multipartAxiosInstance.post(`products/${id}`, formData);
    }

    delete(id: number) {
        return this.axiosInstance.delete(`products/${id}`);
    }

    filter(params: any) {
        return this.axiosInstance.get("products", {params})
    }

    report(params: any) {
        return this.axiosInstance.get("report", {params})
    }
}

const api = new API();

function getAPI() {
    return api;
}

export {
    getAPI
}
