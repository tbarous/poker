import {createSlice, PayloadAction} from '@reduxjs/toolkit';
import type {RootState} from './store';

interface AppState {
    env: "dev" | "prod"
}

const initialState: AppState = {
    env: window.env,
}

export const appSlice = createSlice({
    name: 'app',
    initialState,
    reducers: {},
})

export const {} = appSlice.actions;

export default appSlice.reducer;