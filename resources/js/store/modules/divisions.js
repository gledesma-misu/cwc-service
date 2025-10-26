import axios from "axios";

export default {
    state: {
        divisions: {},
        divisionLinks: [],
    },
    getters: {
        divisions(state) {
            return state.divisions;
        },
        divisionLinks(state) {
            return state.divisionLinks;
        },
        // getDivisionName: (state) => (id) => {
        //     if (!state.divisions.data) return 'N/A';
        //     const division = state.divisions.data.find(d => d.id === id);
        //     return division ? division.name : 'N/A';
        // },
    },
    mutations: {
        set_divisions: (state, data) => {
            state.divisions = data;
            state.divisionLinks = [];

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
                    state.divisionLinks.push(data.links[i]);
                }
            }
        },
    },
    actions: {
        searchDivision: (context, searchData) => {
            setTimeout(function () {
                axios
                    .get(
                        `${window.url}api/searchDivision?${searchData.search_type}=${searchData.search_value}`
                    )
                    .then((response) => {
                        context.commit("set_divisions", response.data);
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            });
        },
        getDivisionsResults: (context, link) => {
            axios.get(link.url).then((response) => {
                // console.log(response.data);
                context.commit("set_divisions", response.data);
            });
        },
        getDivisions: (context) => {
            axios.get(`${window.url}api/getDivisions`).then((response) => {
                context.commit("set_divisions", response.data);
            });
        },
        addDivision: (context, divisionData) => {
            divisionData
                .post(`${window.url}api/storeDivision`)
                .then((response) => {
                    // this.getDivisions();
                    context.dispatch("getDivisions");
                    $("#exampleModal").modal("hide");
                    window.Toast.fire({
                        icon: "success",
                        title: "Division Created Successfully",
                    });
                });
        },
        updateDivision: (context, divisionData) => {
            divisionData
                .post(window.url + "api/updateDivision/" + divisionData.id)
                .then((response) => {
                    context.dispatch("getDivisions");
                    $("#exampleModal").modal("hide");
                    window.Toast.fire({
                        icon: "success",
                        title: "Division Updated Successfully",
                    });
                });
        },
        deleteDivision: (context, division) => {
            axios
                .post(window.url + "api/deleteDivision/" + division.id)
                .then((response) => {
                    window.Toast.fire({
                        icon: "success",
                        title: "Division Deleted Successfully",
                       
                    });
                    context.dispatch("getDivisions");
                });
        },
    },
};
