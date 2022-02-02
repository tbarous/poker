import React, {FunctionComponent, ReactElement} from "react";
import styled from "styled-components";
import { Routes, Route, Link } from "react-router-dom";
import Login from "./components/Login";
import Register from "./components/Register";

const Wrapper = styled.div``;

const App: FunctionComponent<{}> = (props: {}): ReactElement => {

    return (
        <Wrapper>
            <Routes>
                <Route path="/login" element={<Login/>}/>
                <Route path="/register" element={<Register/>}/>
            </Routes>
        </Wrapper>
    )
}

export default App;
