import {defineStore} from 'pinia'
import {chatterApi} from './api'

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

export const useMainStore = defineStore('main', {

  state: () => ({
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
  }),

  getters: {
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
  },

  actions: {
    async init () {
      this.posts_loading = true
      this.user = await chatterApi.getUserSelf()
      let course_response = await chatterApi.getCourseSummary()
      this.posts = await chatterApi.getCoursePosts()
      this.course_summary = course_response.course
      this.user_upvoted_comment_ids = course_response.user_upvoted_comment_ids
      this.app_settings = course_response.app_settings
      this.posts_loading = false
    },
    /** @return Course */
    async getCourse () {
      return await chatterApi.getCourse()
    },
    async updateCourse (payload) {
      const course = await chatterApi.updateCourse(payload)
      this.course_summary = Object.assign(this.course_summary, course)
      return course
    },
    async updateCourseUser (payload) {
      const user = await chatterApi.updateUser(this.user.id, payload)
      this.user = Object.assign(this.user, user)
      return user
    },


    setSearchString (payload) {
      this.search_string = payload.search_string
    },
    setFilterOrder (payload) {
      this.filter_order = payload.filter_order
    },
    async search () {
      this.posts_loading = true
      let params = { filter: this.filter_order, search: this.search_string }
      const posts = await chatterApi.searchCoursePosts(params)
      this.filtered_posts = posts
      this.search_results_available = true
      this.posts_loading = false
      return posts
    },
    async setCurrentlyViewedPost(payload) {
      let post = await chatterApi.getPost(payload.post_id)
      this.currently_viewed_post = post
    },
    async createPost (payload) {
      let post = await chatterApi.createPost(payload)
      if (!post.num_comments) {
        post.num_comments = 0
      }
      if (!post.num_unread_comments) {
        post.num_unread_comments = 0
      }
      this.posts.unshift(post)
      this.currently_viewed_post = post
      return post
    },
    async editPost (payload) {
      let post = await chatterApi.editPost(payload.post_id, payload)

      let post_id = post.id
      let body = post.body
      let tag = post.tag
      let edited_at = post.edited_at

      let edited_post = this.posts.findIndex(post => post.id === post_id);
      this.posts[edited_post].tag = tag;

      if (this.currently_viewed_post.id === post_id) {
        this.currently_viewed_post.body = body
        this.currently_viewed_post.tag = tag
        this.currently_viewed_post.edited_at = edited_at
      }

      return post
    },
    async editTag (payload) {
      let post = await chatterApi.editPostTag(payload.post_id, payload)

      let post_id = post.id
      let body = post.body
      let tag = post.tag
      let edited_at = post.edited_at

      let edited_post = this.posts.findIndex(post => post.id === post_id);
      this.posts[edited_post].tag = tag;

      if (this.currently_viewed_post.id === post_id) {
        this.currently_viewed_post.body = body
        this.currently_viewed_post.tag = tag
        this.currently_viewed_post.edited_at = edited_at
      }

      return post
    },
    async pinPost (payload) {
      let post = await chatterApi.pinPost(payload.post_id, payload.pinned)

      let post_id = post.id
      let pinned = post.pinned

      this.posts.find(p => p.id === post_id).pinned = pinned
      if (this.currently_viewed_post.id === post_id) {
        this.currently_viewed_post.pinned = pinned
      }

      return post
    },
    async lockPost (payload) {
      let post = await chatterApi.lockPost(payload.post_id, payload.locked)

      let post_id = post.id
      let locked = post.locked

      this.posts.find(p => p.id === post_id).locked = locked
      if (this.currently_viewed_post.id === post_id) {
        this.currently_viewed_post.locked = locked
      }

      return post
    },
    async deletePost (payload) {
      await chatterApi.deletePost(payload.post_id)
      this.currently_viewed_post = {}
      this.posts = this.posts.filter(p => p.id !== payload.id)
    },
    async endorseComment (payload) {
      let comment = await chatterApi.endorseComment(payload.comment_id, payload.endorsed)
      let endorsed = comment.endorsed
      if (this.currently_viewed_post.id !== comment.post_id) {
        return
      }

      let comment1 = this.currently_viewed_comment_by_id(comment.id)
      if (comment1) {
        comment1.endorsed = endorsed
      }
    },
    async muteComment (payload) {
      let comment = await chatterApi.muteComment(payload.comment_id, payload.muted)
      let muted_by_user_id = comment.muted_by_user_id
      if (this.currently_viewed_post.id !== comment.post_id) {
        return
      }

      let comment1 = this.currently_viewed_comment_by_id(comment.id)
      if (comment1) {
        comment1.muted_by_user_id = muted_by_user_id || null
      }
    },
    async addComment (payload) {
      let comment = await chatterApi.createComment(payload)
      this.posts.find(p => p.id === comment.post_id).num_comments++;
      if (this.currently_viewed_post.id === comment.post_id) {
        this.currently_viewed_post.comments.push(comment)
      }
      return comment
    },
    async editComment (payload) {
      let comment = await chatterApi.editComment(payload.comment_id, payload)
      let comment1 = this.currently_viewed_comment_by_id(comment.id)
      if (comment1) {
        comment1.body = comment.body
      }
      return comment
    },
    async addCommentUpvote (payload) {
      let comment = await chatterApi.upvoteComment(payload.comment_id, true)
      this.user_upvoted_comment_ids.push(comment.id)
      let comment1 = this.currently_viewed_comment_by_id(comment.id)
      if (comment1) {
        comment1.num_upvotes = comment1.num_upvotes + 1
      }
    },
    async removeCommentUpvote (payload) {
      let comment = await chatterApi.upvoteComment(payload.comment_id, false)
      this.user_upvoted_comment_ids = this.user_upvoted_comment_ids
        .filter(id => id !== comment.id)
      let comment1 = this.currently_viewed_comment_by_id(comment.id)
      if (comment1) {
        comment1.num_upvotes = comment1.num_upvotes - 1
      }
    },
    async deanonUserId (payload) {
      return await chatterApi.getUser(payload.user_id)
    },
    switchScreen (payload) {
      this.view_post_list = payload.view_post_list
      this.view_post_display = payload.view_post_display
      this.view_post_create = payload.view_post_create
    },
    toggleMobile (payload) {
      this.mobile = payload.mobile
    }
  }

});
