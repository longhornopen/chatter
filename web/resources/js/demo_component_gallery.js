import axios from 'axios'

require('./bootstrap');

import Vue from 'vue';

import AppFramework from './components/AppFramework.vue';
import AppHeader from './components/AppHeader.vue';
import PostList from './components/PostList.vue';
import PostDisplay from './components/PostDisplay.vue';
import PostCreate from './components/PostCreate.vue';
import CommentCreate from './components/CommentCreate.vue';
import CommentDisplay from './components/CommentDisplay.vue'
import FormattedDate from './components/FormattedDate.vue';

Vue.component('app-framework', AppFramework);
Vue.component('app-header', AppHeader);
Vue.component('post-list', PostList);
Vue.component('post-display', PostDisplay);
Vue.component('post-create', PostCreate);
Vue.component('comment-create', CommentCreate);
Vue.component('comment-display', CommentDisplay);
Vue.component('formatted-date', FormattedDate);

import Vuex from 'vuex'
Vue.use(Vuex);
import store_defaults from './store';


// Fill in some dummy data, since we don't want you to have to have a database or the real API running
store_defaults.state.user = {
    "name": "Tammy Teacher",
    "email": "teacher@example.com",
    "role": "teacher", // 'teacher' or 'student'
};
store_defaults.state.course_summary = {
    "id":1,
    "name":"Some course: A Very Very Very Very Very Very Long Course Title",
};
store_defaults.state.posts = [
  {
    "id":1,
    "author_user_name":"Theodore Teacher",
    "author_anonymous":false,
    "title":"post #1",
    "body":"test body",
    "pinned":false,
    "locked":true,
    "created_at":"2020-12-21T09:17:11.000000Z",
    "num_comments":17,
    "num_unread_comments":2
  },
  {
    "id":2,
    "author_user_name":"Tammy Teacher",
    "author_anonymous":false,
    "title":"post #2",
    "body":"test body",
    "pinned":true,
    "locked":true,
    "created_at":"2020-12-21T09:17:11.000000Z",
    "num_comments":18,
    "num_unread_comments":2
  },
  {
    "id":3,
    "author_user_name":"Tammy Teacher",
    "author_anonymous":false,
    "title":"post #3",
    "body":"test body",
    "pinned":true,
    "locked":false,
    "created_at":"2020-12-21T09:17:11.000000Z",
    "num_comments":18,
    "num_unread_comments":2
  },
  {
    "id":3,
    "author_user_name":"Tammy Teacher",
    "author_anonymous":false,
    "title":"post #4, testing time",
    "body":"test body",
    "pinned":true,
    "locked":false,
    "created_at":"2021-01-05T16:08:02+0000",
    "num_comments":18,
    "num_unread_comments":2
  },
  {
    "id":1,
    "author_user_name":"Theodore Teacher",
    "author_anonymous":false,
    "title":"post #1",
    "body":"test body",
    "pinned":false,
    "locked":true,
    "created_at":"2020-12-21T09:17:11.000000Z",
    "num_comments":17,
    "num_unread_comments":2
  },
  {
    "id":2,
    "author_user_name":"Tammy Teacher",
    "author_anonymous":false,
    "title":"post #2",
    "body":"test body",
    "pinned":true,
    "locked":true,
    "created_at":"2020-12-21T09:17:11.000000Z",
    "num_comments":18,
    "num_unread_comments":2
  },
  {
    "id":3,
    "author_user_name":"Tammy Teacher",
    "author_anonymous":false,
    "title":"post #3",
    "body":"test body",
    "pinned":true,
    "locked":false,
    "created_at":"2020-12-21T09:17:11.000000Z",
    "num_comments":18,
    "num_unread_comments":2
  },
  {
    "id":3,
    "author_user_name":"Tammy Teacher",
    "author_anonymous":false,
    "title":"post #4, testing time",
    "body":"test body",
    "pinned":true,
    "locked":false,
    "created_at":"2021-01-05T16:08:02+0000",
    "num_comments":18,
    "num_unread_comments":2
  },
  {
    "id":1,
    "author_user_name":"Theodore Teacher",
    "author_anonymous":false,
    "title":"post #1",
    "body":"test body",
    "pinned":false,
    "locked":true,
    "created_at":"2020-12-21T09:17:11.000000Z",
    "num_comments":17,
    "num_unread_comments":2
  },
  {
    "id":2,
    "author_user_name":"Tammy Teacher",
    "author_anonymous":false,
    "title":"post #2",
    "body":"test body",
    "pinned":true,
    "locked":true,
    "created_at":"2020-12-21T09:17:11.000000Z",
    "num_comments":18,
    "num_unread_comments":2
  },
  {
    "id":3,
    "author_user_name":"Tammy Teacher",
    "author_anonymous":false,
    "title":"post #3",
    "body":"test body",
    "pinned":true,
    "locked":false,
    "created_at":"2020-12-21T09:17:11.000000Z",
    "num_comments":18,
    "num_unread_comments":2
  },
  {
    "id":3,
    "author_user_name":"Tammy Teacher",
    "author_anonymous":false,
    "title":"post #4, testing time",
    "body":"test body",
    "pinned":true,
    "locked":false,
    "created_at":"2021-01-05T16:08:02+0000",
    "num_comments":18,
    "num_unread_comments":2
  }
];

store_defaults.state.currently_viewed_post =
    {
        'id': 1,
        "author_user_name":"Tammy Teacher",
        "author_anonymous":false,
        'author_user_role': 'teacher',
        'title':'post #1',
        'body':'test body',
        'pinned':false,
        'locked':false,
        'created_at':"2020-12-21T09:17:11.000000Z",
        comments: [
            {
                'id': 1,
                "author_user_name":"Tammy Teacher",
                "author_anonymous":false,
                'author_user_role': 'teacher',
                'muted_by_user_id': 1,
                'body': "This is <b>Test Comment #1</b>",
                "created_at": "2020-12-21T09:17:11.000000Z",
                'endorsed': true,
                'parent_comment_id': null,
                'is_unread': false,
            },
            {
                'id': 2,
              "author_user_name":"Tammy Teacher",
              "author_anonymous":false,
                'author_user_role': 'teacher',
                'muted_by_user_id': null,
                'body': "This is <b>Test Comment #2</b>",
                "created_at": "2020-12-21T09:17:11.000000Z",
                'endorsed': false,
                'parent_comment_id': null,
                'is_unread': true,
            },
            {
              'id': 3,
              "author_user_name":"Sally Student",
              "author_anonymous":false,
              'author_user_role': 'student',
              'muted_by_user_id': null,
              'body': "This is <b>Child Comment #3</b>",
              "created_at": "2020-12-21T09:18:11.000000Z",
              'endorsed': false,
              'parent_comment_id': 2,
              'is_unread': true,
            },
            {
              'id': 4,
              "author_user_name":"Sally Student",
              "author_anonymous":false,
              'author_user_role': 'student',
              'muted_by_user_id': null,
              'body': "This is <b>Child Comment #4</b>",
              "created_at": "2020-12-21T09:19:11.000000Z",
              'endorsed': false,
              'parent_comment_id': 2,
              'is_unread': true,
            },
            {
              'id': 5,
              "author_user_name":"Sally Student",
              "author_anonymous":false,
              'author_user_role': 'student',
              'muted_by_user_id': null,
              'body': "This is <b>Grandchild Comment #5</b>",
              "created_at": "2020-12-21T09:18:11.000000Z",
              'endorsed': false,
              'parent_comment_id': 3,
              'is_unread': true,
            },
            {
              'id': 6,
              "author_user_name":"Sally Student",
              "author_anonymous":false,
              'author_user_role': 'student',
              'muted_by_user_id': null,
              'body': "This is <b>Child Comment #6</b>. This comment is long, with a lot a lot a lot of text. I just want to see it wraps around. More text more text more text . . . More and more and more and more text to see . . .",
              "created_at": "2020-12-21T09:18:11.000000Z",
              'endorsed': false,
              'parent_comment_id': 2,
              'is_unread': true,
            },
            {
              'id': 7,
              "author_user_name":"Sally Student",
              "author_anonymous":false,
              'author_user_role': 'student',
              'muted_by_user_id': null,
              'body': "This is <b>Child Comment #7</b>",
              "created_at": "2020-12-21T09:18:11.000000Z",
              'endorsed': false,
              'parent_comment_id': 2,
              'is_unread': true,
            }
        ]
};

store_defaults.actions.search = function({commit}) {
    let posts = this.getters.posts;
    // search string exists?  exclude first post, to simulate server-side search
    if (this.getters.search_string !== '') {
      posts = posts.slice(1);
    }
    // filtering by other than newest?  reverse post order, to simulate server-side search
    if (this.getters.filter_order !== 'newest') {
      posts = [...posts].reverse();
    }
    commit('setFilteredPosts', {posts: posts});
}
store_defaults.actions.setAppMainPanelMode = function({commit}, payload) {
  commit('setAppMainPanelMode', {mode: payload.mode});
  if (payload.mode === 'show_post') {
    commit('setCurrentlyViewedPost', {post: this.getters.currently_viewed_post});
  }
}
store_defaults.actions.createPost = function({commit}, payload) {
    // these are filled in from the API in the real app, so fake it here
    payload.author_user_name = 'Current User';
    payload.created_at = '2020-12-21T09:17:11.000000Z';
    commit('addPost', payload);
  };
store_defaults.actions.editPost = function({commit}, payload) {
    commit('editPost', {post_id: payload.post_id, body: payload.body});
  };
store_defaults.actions.pinPost = function({commit}, payload) {
    commit('pinPost', {post_id:payload.post_id, pinned:payload.pinned});
  };
store_defaults.actions.lockPost = function({commit}, payload) {
    commit('lockPost', {post_id: payload.post_id, locked: payload.locked});
  };
store_defaults.actions.deletePost = function({commit}, payload) {
    commit('deletePost', {post_id: payload.post_id});
  };
store_defaults.actions.endorseComment = function({commit}, payload) {
    commit('endorseComment', {comment_id:payload.comment_id, endorsed: payload.endorsed});
  };
store_defaults.actions.muteComment = function({commit}, payload) {
    let muid = payload.muted ? 999 : null;
    commit('muteComment', {comment_id: payload.comment_id, muted_by_user_id: muid});
  };
store_defaults.actions.addComment = function({commit}, payload) {
    // these are filled in from the API in the real app, so fake it here
    payload.author_user_name = 'Current User';
    payload.created_at = '2020-12-21T09:17:11.000000Z';
    commit('addComment', payload);
};
store_defaults.actions.deanonUserId = function({commit}, payload) {
  return {name:"Real User Name"};
};

let store = new Vuex.Store(store_defaults);

let onResize = function() {
  const w = window.innerWidth
  let is_mobile = w < 768
  store.dispatch('toggleMobile', {
    mobile: is_mobile,
  })
  store.dispatch('switchScreen', {
    view_post_list: true,
    view_post_display: !is_mobile,
  })
}

new Vue({
    el: '#app',
    store: store,
    methods: {
        get_sample_comment: function() {
            return this.$store.getters.currently_viewed_post.comments[0];
        }
    },
    mounted() {
      onResize()
      window.addEventListener('resize', onResize)
    },
    beforeDestroy() {
      window.removeEventListener('resize', onResize)
    }
})
