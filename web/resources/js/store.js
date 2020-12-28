import Vue from 'vue'
import Vuex from 'vuex'

import axios from 'axios';

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
  setUser(state,user) {
    state.user = user;
  },
  setCourseSummary(state,course_summary) {
    state.course_summary = course_summary;
  },
}

const actions = {
  init ({ commit }) {
    axios.all([
      axios.get('/api/course/1/user/self'),
      axios.get('/api/course/1/summary'),
    ])
      .then(axios.spread((user, courseSummary) => {
        commit('setUser', user.data);
        commit('setCourseSummary', courseSummary.data);
      }))
  }
}

export default new Vuex.Store({
    state,
    getters,
    actions,
    mutations
});
