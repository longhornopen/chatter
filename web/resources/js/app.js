import Vuex from 'vuex'

require('./bootstrap');

import Vue from 'vue';

import AppFramework from './components/AppFramework.vue';
import AppHeader from './components/AppHeader.vue';
import PostList from './components/PostList.vue';
import PostDisplay from './components/PostDisplay.vue';
import PostCreate from './components/PostCreate.vue';
import CommentCreate from './components/CommentCreate.vue';
import CommentDisplay from './components/CommentDisplay.vue';
import FormattedDate from './components/FormattedDate.vue';


Vue.component('app-framework', AppFramework);
Vue.component('app-header', AppHeader);
Vue.component('post-list', PostList);
Vue.component('post-display', PostDisplay);
Vue.component('post-create', PostCreate);
Vue.component('comment-create', CommentCreate);
Vue.component('comment-display', CommentDisplay)
Vue.component('formatted-date', FormattedDate);

Vue.use(Vuex);
import store from './store';

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

store.dispatch('init').then(()=> {
  const app = new Vue({
    el: '#app',
    store: store,
    mounted () {
      onResize()
      window.addEventListener('resize', onResize)
      document.getElementById('loading-splash').remove();
    },
    beforeDestroy () {
      window.removeEventListener('resize', onResize)
    }
  })
})

