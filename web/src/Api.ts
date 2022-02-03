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

    statistics(){
        return this.axios.get("statistics").then(res => res.data)
    }
}

const api = new API();

function getAPI() {
    return api;
}

export {
    getAPI
}
