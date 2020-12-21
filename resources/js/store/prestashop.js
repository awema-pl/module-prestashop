import {consoleDebug} from '../modules/helpers'

export const state = () => ({})

export const getters = {

    prestashop: state => name => {
        return state[name]
    },

    isLoading: (state, getters) => name => {
        const prestashop = getters.prestashop(name)
        return prestashop && prestashop.isLoading
    },
}

export const mutations = {

    create(state, {name, data}) {
        Vue.set(state, name, {
            isLoading: false,
            data,
        })
    },

    setLoading(state, {name, status}) {
        Vue.set(state[name], 'isLoading', status)
    },
}

export const actions = {

    restoreData({ state }, { name }) {
        const prestashop = state[name]
        return prestashop.exampleData || 'example-data';
    },

    testLoading({ state, commit, dispatch }, {name}) {

        return new Promise( resolve => {

            let _data
            const prestashop = state[name]

            commit('setLoading', {name, status:true})

            dispatch('restoreData', { name })
                .then( data => {
                    consoleDebug('data', data);
                    return ['data-2']
                })
                .then( data => {
                    _data = data
                    consoleDebug('data-2', data);
                })
                .finally( () => {
                   setTimeout(()=>{
                       commit('setLoading', { name, status: false })
                       resolve( _data )
                   }, 2000)
                })
        })
    }
}

export default {
    state,
    getters,
    mutations,
    actions,
    namespaced: true
}
