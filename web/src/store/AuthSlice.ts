import {createSlice, PayloadAction} from '@reduxjs/toolkit';
import type {RootState} from './store';

interface AuthState {
    loggedIn: boolean,
    username: string
}

const initialState: AuthState = {
    loggedIn: false,
    username: ""
}

export const authSlice = createSlice({
    name: 'auth',
    initialState,
    reducers: {
        login: (state, action: PayloadAction<string>) => {
            state.loggedIn = true;
            state.username = action.payload;
        },
        logout: (state) => {
            state.loggedIn = false;
            state.username = "";
        },
    },
})

export const {login, logout} = authSlice.actions;

export default authSlice.reducer;