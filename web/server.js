const express = require('express');
const app = express()
const port = 3000;
const path = require('path');

app.use(express.static(path.join(__dirname, 'dist')));

app.get('/', function (req, res) {
    res.sendFile(`${__dirname}/index.html`);
});

app.listen(port, () => {
    console.log(`Example app listening on port ${port}`)
})