import axios from "axios";
import { Modal } from "bootstrap";
export default {
    state: {
        exampleModal: null,
        tech_responses: {},
        ta_count: 0,
        request_count: 0,
        pending_requests: [],
        pending_requests_links: [],
        accomplished_requests: [],
        accomplished_requests_links: [],
    },
    getters: {
        tech_responses(state) {
            return state.tech_responses;
        },
        ta_count(state) {
            return state.ta_count;
        },
        request_count(state) {
            return state.request_count;
        },
        pending_requests(state) {
            return state.pending_requests;
        },
        pending_requests_links(state) {
            return state.pending_requests_links;
        },
        accomplished_requests(state) {
            return state.accomplished_requests;
        },
        accomplished_requests_links(state) {
            return state.accomplished_requests_links;
        },
    },
    mutations: {
        set_tech_responses: (state, data) => {
            state.tech_responses = data;
            // console.log("state.tech_responses");
        },
        set_ta_count: (state, data) => {
            state.ta_count = data;
        },
        set_request_count: (state, data) => {
            state.request_count = data;
        },
        set_accomplished_requests: (state, data) => {
            state.accomplished_requests = data;
            state.accomplished_requests_links = [];

            for (let i = 0; i < data.links.length; i++) {
                if (
                    i === 1 ||
                    i === Number(data.links.length - 2) ||
                    data.links[i].active ||
                    isNaN(data.links[i].label) ||
                    Number(data.links[i].label) ===
                        Number(data.current_page + 1) ||
                    Number(data.links[i].label) ===
                        Number(data.current_page - 1)
                ) {
                    state.accomplished_requests_links.push(data.links[i]);
                }
            }
        },
        set_pending_requests: (state, data) => {
            state.pending_requests = data;
            state.pending_requests_links = [];
            // console.log(data.links);
            for (let i = 0; i < data.links.length; i++) {
                if (
                    i === 1 ||
                    i === Number(data.links.length - 2) ||
                    data.links[i].active ||
                    isNaN(data.links[i].label) ||
                    Number(data.links[i].label) ===
                        Number(data.current_page + 1) ||
                    Number(data.links[i].label) ===
                        Number(data.current_page - 1)
                ) {
                    state.pending_requests_links.push(data.links[i]);
                }
            }
        },
    },
    actions: {
        getRequestResultsPending: (context, link) => {
            axios.get(link.url).then((response) => {
                context.commit("set_pending_requests", response.data);
                // console.log(link);
            });
        },
        getRequestResultsAccomplished: (context, link) => {
            axios.get(link.url).then((response) => {
                context.commit("set_accomplished_requests", response.data);
                // console.log(response.data);
            });
        },
        getTechResponse: (context, id) => {
            axios
                .get(`${window.url}api/getTechResponse/${id}`)
                .then((response) => {
                    context.commit("set_tech_responses", response.data);
                });
        },
        getSelfRequestCount: (context) => {
            axios
                .get(`${window.url}api/getSelfRequestCount`)
                .then((response) => {
                    context.commit("set_request_count", response.data);
                });
        },
        getAccomplishedRequests: (context) => {
            axios
                .get(`${window.url}api/getAccomplishedRequests`)
                .then((response) => {
                    context.commit("set_accomplished_requests", response.data);
                });
        },
        getPendingRequests: (context) => {
            axios
                .get(`${window.url}api/getPendingRequests`)
                .then((response) => {
                    context.commit("set_pending_requests", response.data);
                });
        },
        addRequest: (context, requestData) => {
            axios
                .post(
                    `${window.url}api/addRequest`,
                    requestData.requestData,
                    requestData.config
                )
                .then((response) => {
                    // $("#exampleModal").modal("hide");
                    window.Toast.fire({
                        icon: "success",
                        title: "Technical Assistance Requested",
                    });
                    context.dispatch("getPendingRequests");
                });
        },
        takeAction: (context, requestData) => {
            requestData
                .post(`${window.url}api/takeAction`, requestData)
                .then((response) => {
                    // $("#exampleModal").modal("hide");
                    window.Toast.fire({
                        icon: "success",
                        title: "Requesting was ongoing",
                    });
                    context.dispatch("getPendingRequests");
                });
        },
        completeRequest: (context, requestData) => {
            requestData
                .post(`${window.url}api/completeRequest`, requestData)
                .then((response) => {
                    // $("#exampleModal").modal("hide");
                    window.Toast.fire({
                        icon: "success",
                        title: "Technical Assistance is accomplished",
                    });
                    context.dispatch("getPendingRequests");
                    context.dispatch("getAccomplishedRequests");
                });
        },
        disregardTask: (context, requestData) => {
            axios
                .post(`${window.url}api/disregardTask/` + requestData.id)
                .then((response) => {
                    // $("#exampleModal").modal("hide");
                    window.Toast.fire({
                        icon: "success",
                        title: "Technical Assistance disregarded",
                    });
                    context.dispatch("getPendingRequests");
                });
        },
        countTAPending: (context) => {
            axios.get(`${window.url}api/countTAPending`).then((response) => {
                context.commit("set_ta_count", response.data);
            });
        },
    },
};
