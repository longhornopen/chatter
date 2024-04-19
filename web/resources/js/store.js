
import { createStore } from 'vuex'
import { chatterApi } from './api'

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
    let post_id = payload.id
    let body = payload.body
    let tag = payload.tag
    let edited_at = payload.edited_at

    let edited_post = state.posts.findIndex(post => post.id === post_id);
    state.posts[edited_post].tag = tag;

    if (state.currently_viewed_post.id === post_id) {
      state.currently_viewed_post.body = body
      state.currently_viewed_post.tag = tag
      state.currently_viewed_post.edited_at = edited_at
    }
  },
  pinPost (state, payload) {
    let post_id = payload.id
    let pinned = payload.pinned

    state.posts.find(p => p.id === post_id).pinned = pinned
    if (state.currently_viewed_post.id === post_id) {
      state.currently_viewed_post.pinned = pinned
    }
  },
  lockPost (state, payload) {
    let post_id = payload.id
    let locked = payload.locked

    state.posts.find(p => p.id === post_id).locked = locked
    if (state.currently_viewed_post.id === post_id) {
      state.currently_viewed_post.locked = locked
    }
  },
  deletePost (state, payload) {
    state.currently_viewed_post = {}
    state.posts = state.posts.filter(p => p.id !== payload.id)
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
    let comment = this.getters.currently_viewed_comment_by_id(payload.id)
    if (comment) {
      comment.body = payload.body
    }
  },
  addCommentUpvote (state, payload) {
    state.user_upvoted_comment_ids.push(payload.id)
    let comment = this.getters.currently_viewed_comment_by_id(payload.id)
    if (comment) {
      comment.num_upvotes = comment.num_upvotes + 1
    }
  },
  removeCommentUpvote (state, payload) {
    state.user_upvoted_comment_ids = state.user_upvoted_comment_ids
      .filter(id => id !== payload.id)
    let comment = this.getters.currently_viewed_comment_by_id(payload.id)
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
    commit('setPostsLoading', { loading: true })
    let user_self = await chatterApi.getUserSelf()
    commit('setUser', { user: user_self })
    let course_response = await chatterApi.getCourseSummary()
    let posts_response = await chatterApi.getCoursePosts()
    commit('setCourseSummary', course_response)
    commit('setPosts', { posts: posts_response })
    commit('setPostsLoading', { loading: false })
  },
  /** @return Course */
  async getCourse ({commit}) {
    return await chatterApi.getCourse()
  },
  async updateCourse ({commit}, payload) {
    const course = await chatterApi.updateCourse(payload)
    commit('updateCourseSummary', course)
    return course
  },
  async updateCourseUser ({commit}, payload) {
    const user = await chatterApi.updateUser(this.state.user.id, payload)
    commit('updateCourseUser', user)
    return user
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
    const posts = await chatterApi.searchCoursePosts(params)
    commit('setFilteredPosts', { posts: posts })
    commit('setPostsLoading', { loading: false })
    return posts
  },
  async setCurrentlyViewedPost({commit}, payload) {
    let post = await chatterApi.getPost(payload.post_id)
    commit('setCurrentlyViewedPost', { post: post })
  },
  async createPost ({ commit }, payload) {
    let post = await chatterApi.createPost(payload)
    commit('addPost', post)
    return post
  },
  async editPost ({ commit }, payload) {
    let post = await chatterApi.editPost(payload.post_id, payload)
    commit('editPost', post)
    return post
  },
  async editTag ({ commit }, payload) {
    let post = await chatterApi.editPostTag(payload.post_id, payload)
    commit('editPost', post)
    return post
  },
  async pinPost ({ commit }, payload) {
    let post = await chatterApi.pinPost(payload.post_id, payload.pinned)
    commit('pinPost', post)
    return post
  },
  async lockPost ({ commit }, payload) {
    let post = await chatterApi.lockPost(payload.post_id, payload.locked)
    commit('lockPost', post)
    return post
  },
  async deletePost ({ commit }, payload) {
    await chatterApi.deletePost(payload.post_id)
    commit('deletePost', { id: payload.post_id })
  },
  async endorseComment ({ commit }, payload) {
    let comment = await chatterApi.endorseComment(payload.comment_id, payload.endorsed)
    commit('endorseComment', {
      comment_id: comment.id,
      post_id: comment.post_id,
      endorsed: comment.endorsed
    })
  },
  async muteComment ({ commit }, payload) {
    let comment = await chatterApi.muteComment(payload.comment_id, payload.muted)
    commit('muteComment', {
      comment_id: comment.id,
      post_id: comment.post_id,
      muted_by_user_id: comment.muted_by_user_id
    })
  },
  async addComment ({ commit }, payload) {
    let comment = await chatterApi.createComment(payload)
    commit('addComment', comment)
    return comment
  },
  async editComment ({ commit }, payload) {
    let comment = await chatterApi.editComment(payload.comment_id, payload)
    commit('editComment', comment)
    return comment
  },
  async addCommentUpvote ({ commit }, payload) {
    let comment = await chatterApi.upvoteComment(payload.comment_id, true)
    commit('addCommentUpvote', comment)
  },
  async removeCommentUpvote ({ commit }, payload) {
    let comment = await chatterApi.upvoteComment(payload.comment_id, false)
    commit('removeCommentUpvote', comment)
  },
  async deanonUserId ({ commit }, payload) {
    return await chatterApi.getUser(payload.user_id)
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
