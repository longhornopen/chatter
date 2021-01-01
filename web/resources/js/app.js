require('./bootstrap');

import Vue from 'vue';

import AppFramework from './components/AppFramework.vue';
import AppHeader from './components/AppHeader.vue';
import PostList from './components/PostList.vue';
import PostDisplay from './components/PostDisplay.vue';
import PostCreate from './components/PostCreate.vue';
import CommentCreate from './components/CommentCreate.vue';
import CommentDisplay from './components/CommentDisplay.vue';


Vue.component('app-framework', AppFramework);
Vue.component('app-header', AppHeader);
Vue.component('post-list', PostList);
Vue.component('post-display', PostDisplay);
Vue.component('post-create', PostCreate);
Vue.component('comment-create', CommentCreate);
Vue.component('comment-display', CommentDisplay)


import store from './store';

store.dispatch('init').then(() => {
  const app = new Vue({
    el: '#app',
    store: store,
  })
});
