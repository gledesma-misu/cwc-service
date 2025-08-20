import axios from "axios";

try {
    window.Popper = require("@popperjs/core");
    window.$ = window.jQuery = require("jquery");
    require("bootstrap");
} catch (error) {
    console.log(error);
}

window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
function loggedIn() {
    if (window.token) {
        let successToken = window.token;
        axios.defaults.headers.common = {
            Authorization: `Bearer ${successToken}`,
        };
        return successToken;
    }
    return;
}
loggedIn();
