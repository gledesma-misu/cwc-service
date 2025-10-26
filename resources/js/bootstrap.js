import axios from "axios";
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */



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
import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    // encrypted: true,
    forceTLS: true,
});


