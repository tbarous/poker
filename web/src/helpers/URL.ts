function getParams() {
    const urlSearchParams = new URLSearchParams(window.location.search);

    return Object.fromEntries(urlSearchParams.entries());
}

function getURL() {
    const url = new URL(window.location.href);
}

function updateURL(prop: string, value: string) {
    const url = new URL(window.location.href);
    const params = getParams();

    if (params[prop]) {
        url.searchParams.set(prop, value);
    } else {
        url.searchParams.append(prop, value);
    }

    window.history.pushState({}, "", url);
}

function removeFromURL(prop) {
    const url = new URL(window.location.href);

    url.searchParams.delete(prop);

    window.history.pushState({}, "", url);
}

export {
    getParams,
    updateURL,
    getURL,
    removeFromURL
}
