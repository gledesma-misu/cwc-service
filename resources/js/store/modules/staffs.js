import axios from "axios";

export default {
    state: {
        staffs: [],
        userLinks: [],
    },
    getters: {
        staffs(state) {
            return state.staffs;
        },
        userLinks(state) {
            return state.userLinks;
        },
    },
    mutations: {
        set_staffs: (state, data) => {
            state.staffs = data;
            state.userLinks = [];

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
                    state.userLinks.push(data.links[i]);
                }
            }
        },
    },
    actions: {
        searchUser: (context, searchData) => {
            setTimeout(function(){
                axios.get(`${window.url}api/searchUser?${searchData.search_type}=${searchData.search_value}`).then((response) => {
                    context.commit("set_staffs", response.data);
                }).catch(err => {
                    console.log(err);
                });
            })
        },
        getStaffsResults: (context, link) => {
            axios.get(link.url).then((response) => {
                // console.log(response.data);
                context.commit("set_staffs", response.data);
                // console.log(response.data);
            });
        },
        getStaffs: (context) => {
            axios.get(`${window.url}api/getStaffs`).then((response) => {
                context.commit("set_staffs", response.data);
            });
        },
        addStaff: (context, staffData) => {
            staffData.post(`${window.url}api/addStaff`).then((response) => {
                // this.getDivisions();
                console.log(response.data);
                $("#exampleModal").modal("hide");
                
                window.Toast.fire({
                    icon: "success",
                    title: "User Created Successfully",
                });
                context.dispatch('getStaffs')
            });
        },
        updateStaff: (context, staffData) => {
            staffData
                .post(window.url + "api/updateStaff/" + staffData.id)
                .then((response) => {
                    context.dispatch("getStaffs");

                    $("#exampleModal").modal("hide");
                    window.Toast.fire({
                        icon: "success",
                        title: "User updated successfully!",
                    });
                });
        },
        deleteStaff: (context, staff) => {
            axios
                .post(window.url + "api/deleteStaff/" + staff.id)
                .then((response) => {
                    window.Toast.fire({
                        icon: "success",
                        title: "User Deleted Successfully",
                    });
                    context.dispatch("getStaffs");
                });
        },
    },
};
