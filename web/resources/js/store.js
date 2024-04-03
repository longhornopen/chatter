import axios from 'axios'

import { createStore } from 'vuex';

/**
@typedef {object} Course
@property {number} id
@property {array} post_tags

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
  course: {},
  course_summary: {},
  app_settings: [],
  posts: [],
  currently_viewed_post: {}, // deprecated
  user: {},

  user_upvoted_comment_ids: [],

  filter_order: 'newest',
  search_string: '',
  filtered_posts: [],
  search_results_available: false,

  posts_loading: false,

  // for mobile display
  // FIXME might be able to do this all in CSS
  mobile: null,
  view_post_list: null,
  view_post_display: null,
  view_post_create: false,
}

const getters = {
  course_summary: state => { return state.course_summary },
  posts: state => { return state.posts },
  currently_viewed_post: state => { return state.currently_viewed_post },
  user: state => { return state.user },

  filter_order: state => { return state.filter_order },
  search_string: state => { return state.search_string },
  filtered_posts: state => { return state.filtered_posts },
  search_results_available: state => { return state.search_results_available },

  posts_loading: state => { return state.posts_loading },

  // for mobile display
  mobile: state => { return state.mobile },
  show_post_list: state => { return state.view_post_list },
  show_post_display: state => { return state.view_post_display },
  show_post_create: state => { return state.view_post_create },

  currently_viewed_comment_by_id: (state) => (comment_id) => { return state.currently_viewed_post.comments.find(c => c.id === comment_id) },
  user_is_teacher: (state) => { return state.user.role === 'teacher' },
  course_is_closed: (state) => {
    if (!state.course_summary.close_date) {
      return false
    }
    let close_at = new Date(state.course_summary.close_date)
    let now = new Date()
    return close_at < now
  },
}

const mutations = {
  setUser (state, payload) {
    state.user = payload.user
  },
  setCourseSummary (state, payload) {
    state.course_summary = payload.course
    state.user_upvoted_comment_ids = payload.user_upvoted_comment_ids
    state.app_settings = payload.app_settings
  },
  updateCourseSummary (state, payload) {
    state.course_summary = Object.assign(state.course_summary, payload)
  },
  updateCourseUser (state, payload) {
    state.user = Object.assign(state.user, payload)
  },
  setPosts (state, payload) {
    state.posts = payload.posts
  },

  setCurrentlyViewedPost (state, payload) {
    state.currently_viewed_post = payload.post
  },
  setPostsLoading (state, payload) {
    state.posts_loading = payload.loading
  },

  setSearchString (state, payload) {
    state.search_string = payload.search_string
  },
  setFilterOrder (state, payload) {
    state.filter_order = payload.filter_order
  },
  setFilteredPosts (state, payload) {
    state.filtered_posts = payload.posts
    state.search_results_available = true
  },

  addPost (state, payload) {
    if (!payload.num_comments) {
      payload.num_comments = 0
    }
    if (!payload.num_unread_comments) {
      payload.num_unread_comments = 0
    }
    state.posts.unshift(payload)
    state.currently_viewed_post = payload
  },
  editPost (state, payload) {
    console.log("edit post in store");
    console.log(payload);
    let post_id = payload.post_id
    let body = payload.body
    let tag = payload.tag
    let edited_at = payload.edited_at

    if (state.currently_viewed_post.id === post_id) {
      state.currently_viewed_post.body = body
      state.currently_viewed_post.tag = tag
      state.currently_viewed_post.edited_at = edited_at
    }
  },
  pinPost (state, payload) {
    let post_id = payload.post_id
    let pinned = payload.pinned

    state.posts.find(p => p.id === post_id).pinned = pinned
    if (state.currently_viewed_post.id === post_id) {
      state.currently_viewed_post.pinned = pinned
    }
  },
  lockPost (state, payload) {
    let post_id = payload.post_id
    let locked = payload.locked

    state.posts.find(p => p.id === post_id).locked = locked
    if (state.currently_viewed_post.id === post_id) {
      state.currently_viewed_post.locked = locked
    }
  },
  deletePost (state, payload) {
    state.currently_viewed_post = {}
    state.posts = state.posts.filter(p => p.id !== payload.post_id)
  },
  endorseComment (state, payload) {
    let endorsed = payload.endorsed
    if (state.currently_viewed_post.id !== payload.post_id) {
      return
    }

    let comment1 = this.getters.currently_viewed_comment_by_id(payload.comment_id)
    if (comment1) {
      comment1.endorsed = endorsed
    }
  },
  muteComment (state, payload) {
    let muted_by_user_id = payload.muted_by_user_id
    if (state.currently_viewed_post.id !== payload.post_id) {
      return
    }

    let comment1 = this.getters.currently_viewed_comment_by_id(payload.comment_id)
    if (comment1) {
      comment1.muted_by_user_id = muted_by_user_id || null
    }
  },
  addComment (state, payload) {
    state.posts.find(p => p.id === payload.post_id).num_comments++;
    if (state.currently_viewed_post.id === payload.post_id) {
      state.currently_viewed_post.comments.push(payload)
    }
  },
  editComment (state, payload) {
    let comment = this.getters.currently_viewed_comment_by_id(payload.comment_id)
    if (comment) {
      comment.body = payload.body
    }
  },
  addCommentUpvote (state, payload) {
    state.user_upvoted_comment_ids.push(payload.comment_id)
    let comment = this.getters.currently_viewed_comment_by_id(payload.comment_id)
    if (comment) {
      comment.num_upvotes = comment.num_upvotes + 1
    }
  },
  removeCommentUpvote (state, payload) {
    state.user_upvoted_comment_ids = state.user_upvoted_comment_ids
      .filter(id => id !== payload.comment_id)
    let comment = this.getters.currently_viewed_comment_by_id(payload.comment_id)
    if (comment) {
      comment.num_upvotes = comment.num_upvotes - 1
    }
  },
  switchScreen (state, payload) {
    state.view_post_list = payload.view_post_list
    state.view_post_display = payload.view_post_display
    state.view_post_create = payload.view_post_create
  },
  toggleMobile (state, payload) {
    state.mobile = payload.mobile
  }
}

const actions = {
  async init ({ commit }, payload) {
    axios.interceptors.request.use(function(config) {
      config.headers['X-Chatter-CourseID'] = payload.course_id;
      return config;
    })
    commit('setPostsLoading', { loading: true })
    let user_response = await axios.get('/api/user/self')
    commit('setUser', { user: user_response.data })
    let course_id = user_response.data.course_id
    let [course_response, posts_response] = await axios.all([
      axios.get('/api/course/' + course_id + '/summary'),
      axios.get('/api/course/' + course_id + '/posts')
    ])
    commit('setCourseSummary', course_response.data)
    commit('setPosts', { posts: posts_response.data })
    commit('setPostsLoading', { loading: false })
  },
  /** @return Course */
  async getCourse ({commit}) {
    let response = await axios.get('/api/course/' + this.state.user.course_id)
    return response.data
  },
  async updateCourse ({commit}, payload) {
    let response = await axios.post('/api/course/' + this.state.user.course_id, payload)
    commit('updateCourseSummary', response.data)
    return response.data
  },
  async updateCourseUser ({commit}, payload) {
    let response = await axios.post('/api/course/' + this.state.user.course_id + '/user/' + this.state.user.id, payload);
    commit('updateCourseUser', response.data)
    return response.data
  },


  setSearchString ({ commit }, payload) {
    commit('setSearchString', { search_string: payload.search_string })
  },
  setFilterOrder ({ commit }, payload) {
    commit('setFilterOrder', { filter_order: payload.filter_order })
  },
  async search ({ commit }) {
    commit('setPostsLoading', { loading: true })
    let params = { filter: this.state.filter_order, search: this.state.search_string }
    let response = await axios.get('/api/course/' + this.state.user.course_id + '/posts', { params: params })
    commit('setFilteredPosts', { posts: response.data })
    commit('setPostsLoading', { loading: false })
    return response
  },
  async setCurrentlyViewedPost({commit}, payload) {
    let response = await axios.get('/api/course/' + this.state.user.course_id + '/post/' + payload.post_id)
    commit('setCurrentlyViewedPost', { post: response.data })
  },
  async createPost ({ commit }, payload) {
    let response = await axios.post('/api/course/' + this.state.user.course_id + '/post/new', payload)
    commit('addPost', response.data)
    return response.data
  },
  async editPost ({ commit }, payload) {
    let response = await axios.post('/api/course/' + this.state.user.course_id + '/post/' + payload.post_id, payload)
    commit('editPost', { post_id: payload.post_id, body: payload.body, tag: payload.tag, edited_at: response.data.edited_at })
    return response.data
  },
  async editTag ({ commit }, payload) {
    let response = await axios.post('/api/course/' + this.state.user.course_id + '/post/' + payload.post_id + '/tag', payload)
    commit('editPost', { post_id: payload.post_id, tag: payload.tag, edited_at: response.data.edited_at })
    return response.data
  },
  async pinPost ({ commit }, payload) {
    let response = await axios.post('/api/course/' + this.state.user.course_id + '/post/' + payload.post_id + '/pin/' + payload.pinned)
    commit('pinPost', { post_id: payload.post_id, pinned: payload.pinned })
  },
  async lockPost ({ commit }, payload) {
    let response = await axios.post('/api/course/' + this.state.user.course_id + '/post/' + payload.post_id + '/lock/' + payload.locked)
    commit('lockPost', { post_id: payload.post_id, locked: payload.locked })
  },
  async deletePost ({ commit }, payload) {
    let response = await axios.delete('/api/course/' + this.state.user.course_id + '/post/' + payload.post_id)
    commit('deletePost', { post_id: payload.post_id })
  },
  async endorseComment ({ commit }, payload) {
    let response = await axios.post('/api/course/' + this.state.user.course_id + '/comment/' + payload.comment_id + '/endorse/' + payload.endorsed)
    commit('endorseComment', {
      comment_id: response.data.id,
      post_id: response.data.post_id,
      endorsed: response.data.endorsed
    })
  },
  async muteComment ({ commit }, payload) {
    let response = await axios.post('/api/course/' + this.state.user.course_id + '/comment/' + payload.comment_id + '/mute/' + payload.muted)
    commit('muteComment', {
      comment_id: response.data.id,
      post_id: response.data.post_id,
      muted_by_user_id: response.data.muted_by_user_id
    })
  },
  async addComment ({ commit }, payload) {
    let response = await axios.post('/api/course/' + this.state.user.course_id + '/comment/new', payload)
    commit('addComment', response.data)
    return response.data
  },
  async editComment ({ commit }, payload) {
    let response = await axios.post('/api/course/' + this.state.user.course_id + '/comment/' + payload.comment_id, payload)
    commit('editComment', { comment_id: payload.comment_id, body: payload.body })
    return response.data
  },
  async addCommentUpvote ({ commit }, payload) {
    let response = await axios.post('/api/course/' + this.state.user.course_id + '/comment/' + payload.comment_id + '/upvote/true', payload)
    commit('addCommentUpvote', payload)
  },
  async removeCommentUpvote ({ commit }, payload) {
    let response = await axios.post('/api/course/' + this.state.user.course_id + '/comment/' + payload.comment_id + '/upvote/false', payload)
    commit('removeCommentUpvote', payload)
  },
  async deanonUserId ({ commit }, payload) {
    let response = await axios.get('/api/course/' + this.state.user.course_id + '/user/' + payload.user_id)
    return response.data
  },
  switchScreen ({ commit }, payload) {
    commit('switchScreen', payload)
  },
  toggleMobile ({ commit }, payload) {
    commit('toggleMobile', payload)
  }
}

export const store = createStore({
  state,
  getters,
  actions,
  mutations
});
