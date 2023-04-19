window._ = require('lodash');

require('bootstrap');

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Vue from 'vue';
import { BootstrapVue } from 'bootstrap-vue'
Vue.use(BootstrapVue);
import 'bootstrap-vue/dist/bootstrap-vue.css'

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

Vue.component('font-awesome-icon', FontAwesomeIcon);
Vue.config.productionTip = false;

window.axios.interceptors.response.use(response => {
  return response;
}, error => {
  if (error.response.status === 401) {
    alert('Your login has expired.  Please relaunch Chatter from your course to continue.');
  } else {
    alert('Sorry, but an unexpected error happened.  Please try again.');
  }
  throw error;
});


