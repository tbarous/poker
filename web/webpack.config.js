const path = require("path");

const config = {
    entry: "./src/index.tsx",
    module: {
        rules: [
            {
                test: /\.(ts|js)x?$/,
                exclude: /node_modules/,
                use: {
                    loader: "babel-loader",
                    options: {
                        presets: [
                            "@babel/preset-env",
                            "@babel/preset-react",
                            "@babel/preset-typescript",
                        ],
                    },
                },
            },
        ],
    },
    mode: "production",
    resolve: {
        extensions: [".tsx", ".ts", ".js"],
    },
    output: {
        path: path.resolve(__dirname, "public/dist"),
        filename: "bundle.js",
    },
    devServer: {
        static: path.join(__dirname, "public/dist"),
        compress: true,
        port: 4000
    },
};

module.exports = config;
