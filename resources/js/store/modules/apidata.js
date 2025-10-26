import axios from "axios";

export default {
    state: {
        unread_notifications: {},
        all_notifications: {},
        filtered_divisions: [],
        filtered_roles: [],
        filtered_permission_categories: [],
        filtered_permissions: [],
        all_permissions: [],
    },
    getters: {
        unread_notifications(state) {
            return state.unread_notifications;
        },
        all_notifications(state) {
            return state.all_notifications;
        },
        filtered_divisions(state) {
            return state.filtered_divisions;
        },
        filtered_roles(state) {
            return state.filtered_roles;
        },
        filtered_permission_categories(state) {
            return state.filtered_permission_categories;
        },
        filtered_permissions(state) {
            return state.filtered_permissions;
        },
    },
    mutations: {
        set_all_notifications: (state, data) => {
            state.all_notifications = data;
        },
        set_unread_notifications: (state, data) => {
            state.unread_notifications = data;
        },
        set_all_divisions: (state, data) => {
            state.filtered_divisions = [];
            data.forEach((division) =>
                state.filtered_divisions.push({
                    value: division.id,
                    label: division.name,
                })
            );
        },
        set_all_roles: (state, data) => {
            state.filtered_roles = [];
            data.forEach((role) =>
                state.filtered_roles.push({
                    value: role.id,
                    label: role.name,
                })
            );
        },
        set_all_permissions: (state, data) => {
            state.all_permissions = data;
            state.filtered_permission_categories = [];
            let itemsArray = [];
            data.forEach((item) => {
                let items = item.name.split("-");
                itemsArray.push(items[0]);
            });
            let uniqueItems = [...new Set(itemsArray)];
            state.filtered_permission_categories = uniqueItems;
        },
        set_filtered_permissions: (state, data) => {
            state.filtered_permissions = [];
            data.values.forEach((value) => {
                state.all_permissions.find((element) => {
                    if (element.name.includes(value)) {
                        state.filtered_permissions.push({
                            value: element.id,
                            label: element.name,
                        });
                    }
                });
            });
        },
    },
    actions: {
        getAllNotifications: (context) => {
            axios.get(`${window.url}api/getAllNotifications`).then((response) => {
                // console.log(response.data);
                context.commit("set_all_notifications", response.data);
            });
        },
        getUnreadNotifications: (context) => {
            axios
                .get(`${window.url}api/getUnreadNotifications`)
                .then((response) => {
                    // console.log(response.data);
                    context.commit("set_unread_notifications", response.data);
                });
        },
        clearAllNotifications: (context) => {
            axios
                .get(`${window.url}api/clearAllNotifications`)
                .then((response) => {
                    // console.log(response.data);
                    context.dispatch("getAllNotifications");
                    window.Toast.fire({
                        icon: "success",
                        title: "Notification cleared!",
                    });
                });
        },
        markNotificationAsRead: (context, unreadData) => {
            axios
                .get(
                    `${window.url}api/markNotificationAsRead?unread=${unreadData.id}`
                )
                .then((response) => {
                    // console.log(response.data);
                    context.dispatch("getUnreadNotifications");
                    window.Toast.fire({
                        icon: "success",
                        title: "Notification marked as read!",
                    });
                });
        },
        getAllDivisions: (context) => {
            axios.get(`${window.url}api/getAllDivisions`).then((response) => {
                context.commit("set_all_divisions", response.data);
            });
        },
        getAllRoles: (context) => {
            axios.get(`${window.url}api/getAllRoles`).then((response) => {
                context.commit("set_all_roles", response.data);
            });
        },
        getAllPermissions: (context) => {
            axios.get(`${window.url}api/getAllPermissions`).then((response) => {
                context.commit("set_all_permissions", response.data);
            });
        },
        getFilteredPermissions: (context, data) => {
            context.commit("set_filtered_permissions", data);
        },
    },
};
