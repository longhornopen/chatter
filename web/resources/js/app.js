import './bootstrap';

import { createApp } from 'vue';
import { createPinia} from 'pinia';

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
  faConciergeBell,
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
  faConciergeBell,
);

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
const pinia = createPinia();
app.use(pinia);
import { useMainStore } from './store'
const store = useMainStore()

let onResize = function() {
  const w = window.innerWidth
  let is_mobile = w < 1077
  store.toggleMobile({
    mobile: is_mobile,
  })
  store.switchScreen({
    view_post_list: true,
    view_post_display: !is_mobile,
  })
}
app.component('app-framework', AppFramework);
app.component('font-awesome-icon', FontAwesomeIcon);
app.use(router);
store.init({course_id:window.course_id}).then(() => {
  app.mount('#app');
});
