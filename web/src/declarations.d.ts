declare global {
    interface Window {
        products: any;
    }
}

window.products = window.products || {};
