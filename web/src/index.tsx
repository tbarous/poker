import React from "react";
import ReactDOM from "react-dom";
import {BrowserRouter} from "react-router-dom";
import App from "./App";
import {ThemeProvider} from "styled-components";
import Theme from "./theme/Theme";
import {Provider} from "react-redux";
import store from "./store/store";

ReactDOM.render(
    <ThemeProvider theme={Theme}>
        <Provider store={store}>
            <BrowserRouter>
                <App/>
            </BrowserRouter>
        </Provider>
    </ThemeProvider>,
    document.getElementById("app")
);
