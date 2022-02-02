import React, {FunctionComponent, useState} from "react";
import {getAPI} from "../Api";

const Register: FunctionComponent<{}> = () => {

    const [form, setForm] = useState({
        name: "",
        email: "",
        password: "",
        passwordConfirmation: ""
    })

    function onSubmit(e: SubmitEvent) {
        e.preventDefault();

        getAPI().register(form.name, form.email, form.password, form.passwordConfirmation);
    }

    function onChange(e) {
        setForm({...form,})
    }

    return (

        <div>
            <form action="" onSubmit={onSubmit}>
                <label htmlFor="">name</label>
                <input value={form.name} onChange={e => setForm({...form, name: e.target.value})} type="text"/>

                <label htmlFor="">email</label>
                <input value={form.email} onChange={e => setForm({...form, email: e.target.value})} type="text"/>

                <label htmlFor="">password</label>
                <input value={form.password} onChange={e => setForm({...form, password: e.target.value})} type="text"/>

                <label htmlFor="">repeat password</label>
                <input value={form.passwordConfirmation}
                       onChange={e => setForm({...form, passwordConfirmation: e.target.value})} type="text"/>

                <button type="submit">submit</button>
            </form>
        </div>
    )
}

export default Register;