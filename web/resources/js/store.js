import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

const state = {
    course_summary: {},
    currently_viewed_post: {},
    user: {},
}

const getters = {
    course_summary: state => { return state.course_summary; },
    currently_viewed_post: state => { return state.currently_viewed_post; },
    user: state => { return state.user; },
}

const mutations = {

}

const actions = {

}

export default new Vuex.Store({
    state,
    getters,
    actions,
    mutations
});
