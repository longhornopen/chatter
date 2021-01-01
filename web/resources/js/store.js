import Vue from 'vue'
import Vuex from 'vuex'

import axios from 'axios';

Vue.use(Vuex);

/*
@typedef Post
@type {object}
@property {number} id
@property {number} author_user_id
@property {string} author_user_name
@property {string} author_user_role - 'teacher' or 'student'
@property {string} title
@property {string} body
@property {boolean} pinned (boolean)
@property {boolean} locked (boolean)
@property {string} created_at - date in ISO-8601 format
@property {string} updated_at - date in ISO-8601 format

@typedef Comment
@type {object}
@property {number} id
@property {number} author_user_id
@property {string} author_user_name
@property {string} author_user_role - 'teacher' or 'student'
@property {string} body
@property {number} parent_comment_id - null if this is a top-level comment
@property {boolean} poster_anonymous
@property {string} created_at - date in ISO-8601 format
@property {string} updated_at - date in ISO-8601 format
 */

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
  setUser(state, user) {
    state.user = user;
  },
  setCourseSummary(state, course_summary) {
    state.course_summary = course_summary;
  },
  createPost(state, /* Post */ post) {
    // FIXME add the post to course_summary
    // FIXME set currently_viewed_post=post
  },
  editPost(state, post_id, body) {
    // FIXME update currently_viewed_post if it's the same post_id
    // FIXME update course_summary
  },
  pinPost(state, post_id, pinned) {
    // FIXME pin/unpin post in course_summary
    // FIXME pin/unpin post in currently_viewed_post if it's the same post id
  },
  lockPost(state, post_id, locked) {
    // FIXME lock/unlock post in course_summary
    // FIXME lock_unlock post in currently_viewed_post if it's the same post id
  },
  deletePost(state, post_id) {
    // FIXME remove the post to course_summary
    // FIXME set currently_viewed_post=null
  },
  endorseComment(state, comment_id, endorsed) {
    // FIXME endorse/unendorse the comment in currently_viewed_post
  },
  muteComment(state, comment_id) {
    // FIXME mute/unmute the comment in currently_viewed_post
  },
  addComment(state, post_id, /* Comment */ comment) {
    // FIXME add the comment, updating currently_viewed_post
    // FIXME update course_summary, incrementing num_comments
  },
}

const actions = {
  init ({ commit }) {
    axios.all([
      axios.get('/api/user/self'),
      axios.get('/api/course/current/summary'),
    ])
      .then(axios.spread((user, courseSummary) => {
        commit('setUser', user.data);
        commit('setCourseSummary', courseSummary.data);
      }))
  },
  createPost({commit}, payload) {
    let post = payload.post;
    commit('createPost', post);
    // FIXME do an API call here and commit the mutation
  },
  editPost({commit}, payload) {
    let post_id = payload.post_id;
    let body = payload.body;
    commit('editPost', post_id, body);
    // FIXME do an API call here and commit the mutation
  },
  pinPost({commit}, payload) {
    let post_id = payload.post_id;
    let pinned = payload.pinned;
    axios.post('/api/course/current/post/'+post_id+'/pin/'+pinned).then(function(response) {
      commit('pinPost', post_id, pinned);
    });
  },
  lockPost({commit}, payload) {
    let post_id = payload.post_id;
    let locked = payload.locked;
    axios.post('/api/course/current/post/'+post_id+'/lock/'+locked).then(function(response) {
      commit('lockPost', post_id, locked);
    });
  },
  deletePost({commit}, payload) {
    let post_id = payload.post_id;
    commit('deletePost', post_id);
    // FIXME do an API call here and commit the mutation
  },
  endorseComment({commit}, payload) {
    let post_id = payload.post_id;
    commit('endorseComment', comment_id);
    // FIXME do an API call here and commit the mutation
  },
  muteComment({commit}, payload) {
    let comment_id = payload.comment_id;
    commit('muteComment', comment_id);
    // FIXME do an API call here and commit the mutation
  },
  addComment({commit}, payload) {
    let post_id = payload.post_id;
    let comment = payload.comment;
    commit('addComment', post_id, comment);
    // FIXME do an API call here and commit the mutation
  },
}

export default new Vuex.Store({
    state,
    getters,
    actions,
    mutations
});
