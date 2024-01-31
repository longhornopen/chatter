import Vuex from 'vuex'

import './bootstrap';

import { createApp } from 'vue';

import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

import {
  faCog,
  faTimesCircle,
  faPlus,
  faReply,
  faAward,
  faThumbtack,
  faLock,
  faArrowCircleUp,
  faCommentAlt,
  faEye,
  faEyeSlash,
  faCaretSquareRight,
  faEllipsisH,
  faChevronLeft,
  faSpinner,
  faQuestion,
  faEdit,
  faTimes,
  faSearch,
  faSearchPlus,
  faChalkboardTeacher,
} from '@fortawesome/free-solid-svg-icons'
library.add(
  faCog,
  faTimesCircle,
  faPlus,
  faReply,
  faAward,
  faThumbtack,
  faLock,
  faArrowCircleUp,
  faCommentAlt,
  faEye,
  faEyeSlash,
  faCaretSquareRight,
  faEllipsisH,
  faChevronLeft,
  faSpinner,
  faQuestion,
  faEdit,
  faTimes,
  faSearch,
  faSearchPlus,
  faChalkboardTeacher,
);

import { store } from './store';

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

import PostDisplay from './components/PostDisplay.vue';
import PostCreate from './components/PostCreate.vue';
import SplashPage from './components/SplashPage.vue';

import { createRouter, createWebHashHistory } from 'vue-router';
const router = createRouter({
  history: createWebHashHistory(),
  routes: [
    { path: '/', component: SplashPage},
    { path: '/post/new', component: PostCreate},
    {
      path: '/post/:post_id',
      component: PostDisplay,
      props: (route) => {
          const pid = Number.parseInt(route.params.post_id)
          return { post_id: pid }
      }},
  ],
})

import AppFramework from './components/AppFramework.vue';
const app = createApp({
  mounted () {
    onResize()
    window.addEventListener('resize', onResize)
    document.getElementById('loading-splash').remove();
  },
  beforeDestroy () {
    window.removeEventListener('resize', onResize)
  }
});
app.component('app-framework', AppFramework);
app.component('font-awesome-icon', FontAwesomeIcon);
app.use(store);
app.use(router);
store.dispatch('init', {course_id:window.course_id}).then(() => {
  app.mount('#app');
});
