import axios from 'axios';

export default {
    state: {
        staffs: [],
    },
    getters: {
        staffs(state){
            return state.staffs
        }
    },
    mutations: {
        set_staffs: (state, data) => {
            state.staffs = data
        },
    },
    actions: {
        getStaffs: (context) => {
            axios.get(`${window.url}api/getStaffs`)
                .then((response) => {
                    context.commit('set_staffs', response.data);
                    
                });
        },
        addStaff: (context, staffData) => {
            staffData.post(`${window.url}api/addStaff`)
                .then((response) => {
                    // this.getDivisions();
                    // context.dispatch('getStaffs')
                    console.log(response.data)
                    $('#exampleModal').modal('hide');
                })
        },
    }
}