require('./bootstrap');

import Vue from 'vue';

import AppFramework from './components/AppFramework.vue';
import AppHeader from './components/AppHeader.vue';
import PostList from './components/PostList.vue';
import PostDisplay from './components/PostDisplay.vue';
import CommentCreate from './components/CommentCreate.vue';
import CommentDisplay from './components/CommentDisplay.vue'

Vue.component('app-framework', AppFramework);
Vue.component('app-header', AppHeader);
Vue.component('post-list', PostList);
Vue.component('post-display', PostDisplay);
Vue.component('comment-create', CommentCreate);
Vue.component('comment-display', CommentDisplay)

import store from './store';

// Fill in some dummy data, since we don't want you to have to have a database or the real API running
store.state.user = {
    "name": "Tammy Teacher",
    "email": "teacher@example.com",
    "role": "teacher", // 'teacher' or 'student'
};
store.state.course_summary = {
    "id":1,
    "name":"test course",
    "posts":[
        {
            "id":1,
            "creator_user_name":"Theodore Teacher",
            "title":"post #1",
            "body":"test body",
            "pinned":false,
            "created_at":"2020-12-21T09:17:11.000000Z",
            "num_comments":17,
            "num_unread_comments":2
        },
        {
            "id":2,
            "creator_user_name":"Tammy Teacher",
            "title":"post #2",
            "body":"test body",
            "pinned":true,
            "created_at":"2020-12-21T09:17:11.000000Z",
            "num_comments":18,
            "num_unread_comments":2
        },
        {
            "id":3,
            "creator_user_name":"Tammy Teacher",
            "title":"post #3",
            "body":"test body",
            "pinned":true,
            "created_at":"2020-12-21T09:17:11.000000Z",
            "num_comments":18,
            "num_unread_comments":2
        }
    ], 
    'filtered_posts': [],
    'search_results_available': false,
};
store.state.currently_viewed_post =
    {
        'id': 1,
        'author_user_name': 'Tammy Teacher',
        'author_user_role': 'teacher',
        'title':'post #1',
        'body':'test body',
        'pinned':false,
        'locked':false,
        'created_at':"2020-12-21T09:17:11.000000Z",
        comments: [
            {
                'id': 1,
                'author_user_name': 'Tammy Teacher',
                'author_user_role': 'teacher',
                'poster_anonymous': false,
                'muted_by_user_id': null,
                'body': "This is <b>Test Comment #1</b>",
                "created_at": "2020-12-21T09:17:11.000000Z",
                'endorsed': true,
                'child_comments': [],
            },
            {
                'id': 2,
                'author_user_name': 'Tammy Teacher',
                'author_user_role': 'teacher',
                'poster_anonymous': false,
                'muted_by_user_id': null,
                'body': "This is <b>Test Comment #2</b>",
                "created_at": "2020-12-21T09:17:11.000000Z",
                'endorsed': false,
                'child_comments': [
                    {
                        'id': 3,
                        'author_user_name': 'Sally Student',
                        'author_user_role': 'student',
                        'poster_anonymous': false,
                        'muted_by_user_id': null,
                        'body': "This is <b>Child Comment #3</b>",
                        "created_at": "2020-12-21T09:18:11.000000Z",
                        'endorsed': false,
                        "child_comments": [
                            {
                                'id': 5,
                                'author_user_name': 'Sally Student',
                                'author_user_role': 'student',
                                'poster_anonymous': false,
                                'muted_by_user_id': null,
                                'body': "This is <b>Grandchild Comment #5</b>",
                                "created_at": "2020-12-21T09:18:11.000000Z",
                                'endorsed': false,
                                "child_comments": [],
                            }
                        ],
                    }, {
                        'id': 4,
                        'author_user_name': 'Sara Student',
                        'author_user_role': 'student',
                        'poster_anonymous': false,
                        'muted_by_user_id': null,
                        'body': "This is <b>Child Comment #4</b>",
                        "created_at": "2020-12-21T09:19:11.000000Z",
                        'endorsed': false,
                        "child_comments": [],
                    }
                ],
            }
        ]
};

store.actions = {
    // will actually have save comment function later
    // for now it can just pop up alert or print out "saved"
}

new Vue({
    el: '#app',
    store: store,
    methods: {
        get_sample_comment: function() {
            return this.$store.getters.currently_viewed_post.comments[0];
        }
    }
})
