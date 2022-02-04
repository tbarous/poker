const express = require('express');
const app = express()
const port = 3000;
const path = require('path');
const process = require('process');

const env = process.env.ENVIRONMENT === "production" ? "production" : "dev";

app.use(express.static(path.join(__dirname, 'dist')));

app.set('view engine', 'pug');

app.get('/', function (req, res) {
    res.render('page', {env, title: "Statistics"})
})

app.get('/login', function (req, res) {
    res.render('page', {env, title: "Login"})
})

app.get('/register', function (req, res) {
    res.render('page', {env, title: "Register"})
})

app.listen(port, () => {
    console.log(`Example app listening on port ${port}`)
})