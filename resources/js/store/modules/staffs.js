import axios from 'axios';

export default {
    state: {

    },
    getters: {

    },
    mutations: {
       
    },
    actions: {
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