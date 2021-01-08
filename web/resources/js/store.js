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
    posts: [],
    currently_viewed_post: {},
    user: {},

    filter_order: 'newest',
    search_string: '',
    filtered_posts: [],
    search_results_available: false,

    app_main_panel_mode: 'welcome',
    posts_loading: false,
}

const getters = {
    course_summary: state => { return state.course_summary; },
    posts: state => { return state.posts; },
    currently_viewed_post: state => { return state.currently_viewed_post; },
    user: state => { return state.user; },

    filter_order: state => { return state.filter_order; },
    search_string: state => { return state.search_string; },
    filtered_posts: state => { return state.filtered_posts; },
    search_results_available: state => { return state.search_results_available; },

    app_main_panel_mode: state => { return state.app_main_panel_mode; },
    posts_loading: state => { return state.posts_loading; },
}

const mutations = {
  setUser(state, payload) {
    state.user = payload.user;
  },
  setCourseSummary(state, payload) {
    state.course_summary = payload.course;
  },
  setPosts(state, payload) {
    state.posts = payload.posts;
  },

  setAppMainPanelMode(state, payload) {
    state.app_main_panel_mode = payload.mode;
  },
  setCurrentlyViewedPost(state, payload) {
    state.currently_viewed_post = payload.post;
  },
  setPostsLoading(state, payload) {
    state.posts_loading = payload.loading;
  },

  setSearchString(state, payload) {
    state.search_string = payload.search_string;
  },
  setFilterOrder(state, payload) {
    state.filter_order = payload.filter_order;
  },
  setFilteredPosts(state, payload) {
    state.filtered_posts = payload.posts;
    state.search_results_available = true;
  },

  addPost(state, payload) {
    if (!payload.num_comments) {
      payload.num_comments = 0;
    }
    if (!payload.num_unread_comments) {
      payload.num_unread_comments = 0;
    }
    state.posts.unshift(payload);
    state.currently_viewed_post = payload;
    state.app_main_panel_mode = 'show_post';
  },
  editPost(state, payload) {
    let post_id = payload.post_id;
    let body = payload.body;
    // FIXME update currently_viewed_post if it's the same post_id
    // FIXME update course_summary
  },
  pinPost(state, payload) {
    let post_id = payload.post_id;
    let pinned = payload.pinned;

    state.posts.find(p => p.id===post_id).pinned = pinned;
    if (state.currently_viewed_post.id === post_id) {
      state.currently_viewed_post.pinned = pinned
    }
  },
  lockPost(state, payload) {
    let post_id = payload.post_id;
    let locked = payload.locked;

    state.posts.find(p => p.id===post_id).locked = locked;
    if (state.currently_viewed_post.id === post_id) {
      state.currently_viewed_post.locked = locked
    }
  },
  deletePost(state, payload) {
    state.currently_viewed_post = {};
    state.app_main_panel_mode = 'welcome';
    state.posts = state.posts.filter(p => p.id !== payload.post_id);
  },
  endorseComment(state, payload) {
    let comment_id = payload.comment_id;
    let endorsed = payload.endorsed;
    if (state.currently_viewed_post.id !== payload.post_id) {
      return;
    }
    let c = state.currently_viewed_post.comments.find(c => c.id === comment_id);
    if (c) {
      c.endorsed = endorsed;
    }
  },
  muteComment(state, payload) {
    let comment_id = payload.comment_id;
    let muted_by_user_id = payload.muted_by_user_id;
    if (state.currently_viewed_post.id !== payload.post_id) {
      return;
    }
    let c = state.currently_viewed_post.comments.find(c => c.id === comment_id);
    if (c) {
      c.muted_by_user_id = muted_by_user_id || null;
    }
  },
  addComment(state, payload) {
    if (state.currently_viewed_post.id === payload.post_id) {
      state.currently_viewed_post.comments.push(payload);
    }
  },
  incrementUnreadCommentCount(state, payload) {
    let p = state.posts.find(p => payload.post_id);
    if (p) {
      p.num_comments += 1;
      if (p.id !== state.currently_viewed_post.id) {
        p.num_unread_comments += 1;
      }
    }
  },
}

const actions = {
  init ({ commit }, user_id) {
    commit('setPostsLoading', {loading:true});
    axios.get('/api/user/self').then(function(user_response) {
      commit('setUser', {user: user_response.data});
      let course_id = user_response.data.course_id;
      axios.all([
        axios.get('/api/course/'+course_id),
        axios.get('/api/course/'+course_id+'/posts')
      ])
        .then(axios.spread((course_response, posts_response) => {
          commit('setCourseSummary', {course: course_response.data});
          commit('setPosts', {posts: posts_response.data});
          commit('setPostsLoading', {loading:false});
        }))
      window.Echo.channel('course.'+course_id)
        .listen('PostLockedChanged', (e) => {
          commit('lockPost', { post_id: e.post_id, locked: e.locked });
        }).listen('PostPinnedChanged', (e) => {
          commit('pinPost', { post_id: e.post_id, pinned: e.pinned });
        }).listen('PostDeleted', (e) => {
          commit('deletePost', { post_id: e.post_id });
        }).listen('CommentAdded', (e) => {
          commit('incrementUnreadCommentCount', { post_id: e.post_id, comment_id: e.comment_id })
        }).listen('CommentEndorsedChanged', (e) => {
          commit('endorseComment', { comment_id: e.comment_id, post_id: e.post_id, endorsed: e.endorsed });
        }).listen('CommentMutedChanged', (e) => {
          commit('muteComment', {comment_id: e.comment_id, post_id: e.post_id, muted_by_user_id: e.muted_by_user_id});
        });
    })
  },

  setSearchString({commit}, payload) {
    commit('setSearchString', {search_string: payload.search_string});
  },
  setFilterOrder({commit}, payload) {
    commit('setFilterOrder', {filter_order: payload.filter_order});
  },
  async search({commit}) {
    commit('setPostsLoading', {loading:true});
    let params = {filter:this.state.filter_order, search:this.state.search_string};
    let response = await axios.get('/api/course/'+this.state.user.course_id+'/posts', {params:params});
    commit('setFilteredPosts', {posts: response.data});
    commit('setPostsLoading', {loading:false});
    return response;
  },
  setAppMainPanelMode({commit}, payload) {
    commit('setCurrentlyViewedPost', {post: {}});
    commit('setAppMainPanelMode', {mode: payload.mode});
    if (payload.mode === 'show_post') {
      axios.get('/api/course/'+this.state.user.course_id+'/post/'+payload.post_id)
        .then(function(response) {
        commit('setCurrentlyViewedPost', {post: response.data});
      });
    }
  },

  createPost({commit}, payload) {
    axios.post('/api/course/'+this.state.user.course_id+'/post/new', payload)
      .then(function(response) {
        commit('addPost', response.data);
      });
  },
  editPost({commit}, payload) {
    commit('editPost', {post_id: payload.post_id, body: payload.body});
    // FIXME do an API call here and commit the mutation
  },
  pinPost({commit}, payload) {
    axios.post('/api/course/'+this.state.user.course_id+'/post/'+payload.post_id+'/pin/'+payload.pinned)
      .then(function(response) {
      commit('pinPost', {post_id:payload.post_id, pinned:payload.pinned});
    });
  },
  lockPost({commit}, payload) {
    axios.post('/api/course/'+this.state.user.course_id+'/post/'+payload.post_id+'/lock/'+payload.locked)
      .then(function(response) {
      commit('lockPost', {post_id: payload.post_id, locked: payload.locked});
    });
  },
  deletePost({commit}, payload) {
    axios.delete('/api/course/'+this.state.user.course_id+'/post/'+payload.post_id).then(function() {
      commit('deletePost', { post_id: payload.post_id });
    });
  },
  endorseComment({commit}, payload) {
    axios.post('/api/course/'+this.state.user.course_id+'/comment/'+payload.comment_id+'/endorse/'+payload.endorsed)
      .then(function(response) {
      commit('endorseComment', { comment_id: response.data.id, post_id: response.data.post_id, endorsed: response.data.endorsed });
    });
  },
  muteComment({commit}, payload) {
    axios.post('/api/course/'+this.state.user.course_id+'/comment/'+payload.comment_id+'/mute/'+payload.muted)
      .then(function(response) {
      commit('muteComment', {comment_id: response.data.id, post_id: response.data.post_id, muted_by_user_id: response.data.muted_by_user_id});
    });
  },
  addComment({commit}, payload) {
    axios.post('/api/course/'+this.state.user.course_id+'/comment/new', payload)
      .then(function(response) {
        commit('addComment', response.data);
      });
  },
  async deanonUserId({commit}, payload) {
    let response = await axios.get('/api/course/' + this.state.user.course_id + '/user/' + payload.user_id)
    return response.data;
  }
}

// TEMP
export default {state:state, getters:getters, actions:actions, mutations:mutations};
/**
export default new Vuex.Store({
    state,
    getters,
    actions,
    mutations
});
*/
