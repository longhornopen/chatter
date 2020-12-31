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
  setFilteredPosts(state, payload) {
    // console.log(state.course_summary.filtered_posts)
    state.course_summary.filtered_posts = payload.filtered_posts
    state.course_summary.search_results_available = payload.search_results_available
  },
  createPost(state, /* Post */ post) {
    // FIXME add the post to course_summary
    // FIXME set currently_viewed_post=post
  },
  editPost(state, post_id, body) {
    // FIXME update currently_viewed_post if it's the same post_id
    // FIXME update course_summary
  },
  pinPost(state, payload) {
    // FIXME pin/unpin post in course_summary
    // find the course with this id in course_summary
    var post_to_pin = null
    state.course_summary.posts.forEach(post => {
      if (post.id === payload.post_id) {
        post_to_pin = post
      }
    })

    // only pin if this post exists
    if (post_to_pin !== null) {
      post_to_pin.pinned = payload.pinned
    }
    // pin/unpin post in currently_viewed_post if it's the same post id
    if (state.currently_viewed_post.id === payload.post_id) {
      state.currently_viewed_post.pinned = payload.pinned
    }
  },
  lockPost(state, payload) {
    var post_to_lock = null
    state.course_summary.posts.forEach(post => {
      if (post.id === payload.post_id) {
        post_to_lock = post
      }
    })

    // only lock if this post exists
    if (post_to_lock !== null) {
      post_to_lock.locked = payload.locked
    }

    // lock / unlock post in currently_viewed_post if it's the same post id
    if (state.currently_viewed_post.id === payload.post_id) {
      state.currently_viewed_post.locked = payload.locked
    }
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
  createPost({commit}, post) {
    commit('createPost', post);
    // FIXME do an API call here and commit the mutation
  },
  editPost({commit}, post_id, body) {
    commit('editPost', post_id, body);
    // FIXME do an API call here and commit the mutation
  },
  pinPost({commit}, payload) {
    commit('pinPost', payload);
    // FIXME do an API call here and commit the mutation
  },
  lockPost({commit}, payload) {
    commit('lockPost', payload);
    // FIXME do an API call here and commit the mutation
  },
  deletePost({commit}, post_id) {
    commit('deletePost', post_id);
    // FIXME do an API call here and commit the mutation
  },
  endorseComment({commit}, comment_id) {
    commit('endorseComment', comment_id);
    // FIXME do an API call here and commit the mutation
  },
  muteComment({commit}, comment_id) {
    commit('muteComment', comment_id);
    // FIXME do an API call here and commit the mutation
  },
  addComment({commit}, post_id, comment) {
    commit('addComment', post_id, comment);
    // FIXME do an API call here and commit the mutation
  },
  setFilteredPosts({ commit }, payload) {
    commit('setFilteredPosts', payload)
  }
}

export default new Vuex.Store({
    state,
    getters,
    actions,
    mutations
});
