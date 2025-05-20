import axios from 'axios';

export default {
    state: {
        divisions: {},
    },
    getters: {
        divisions(state) {
            return state.divisions
        }
    },
    mutations: {
        set_divisions: (state, data) => {
            state.divisions = data
        }
    },
    actions: {
        getDivisions: (context) => {
            axios.get(`${window.url}api/getDivisions`)
                .then((response) => {
                    context.commit('set_divisions', response.data);
                });
        },
        addDivision: (context, divisionData) => {
            divisionData.post(`${window.url}api/storeDivision`)
                .then((response) => {
                    // this.getDivisions();
                    context.dispatch('getDivisions')
                    $('#exampleModal').modal('hide');
                })
        },
        updateDivision: (context, divisionData) => {
            divisionData.post(window.url + 'api/updateDivision/' +
                divisionData.id)
                .then((response) => {
                    context.dispatch('getDivisions')
                    $('#exampleModal').modal('hide');
                })
        },
        deleteDivision: (context, division) => {
            if (confirm('Are you sure you want to delete?')) {
                axios.post(window.url + 'api/deleteDivision/' +
                    division.id)
                    .then((response) => {
                        context.dispatch('getDivisions')
                    })
            }
        }
    }
}