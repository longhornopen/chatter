window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

if (process.env.MIX_PUSHER_APP_KEY) {
  window.Pusher = require('pusher-js');

  let echo_options = {
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    forceTLS: false,
    disableStats: true,
  }
  if (process.env.MIX_PUSHER_WS_HOST) {
    echo_options.wsHost = process.env.MIX_PUSHER_WS_HOST;
    echo_options.wsPort = 6001;
  }
  window.Echo = new Echo(echo_options);
}


window.katex = require('katex')
import 'katex/dist/katex.min.css';


import Vue from 'vue';
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
    faChevronLeft
  } from '@fortawesome/free-solid-svg-icons'
library.add(faCog);
library.add(faPlus);
library.add(faReply);
library.add(faAward);
library.add(faTimesCircle);
library.add(faThumbtack);
library.add(faLock);
library.add(faArrowCircleUp);
library.add(faCommentAlt);
library.add(faEye);
library.add(faEyeSlash);
library.add(faCaretSquareRight);
library.add(faEllipsisH);
library.add(faChevronLeft);

Vue.component('font-awesome-icon', FontAwesomeIcon);
Vue.config.productionTip = false;

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

import sweetalert2 from 'sweetalert2';
window.axios.interceptors.response.use(response => {
  return response;
}, error => {
  if (error.response.status === 401) {
    sweetalert2.fire({
      title: 'Login Expired',
      text: 'Your login has expired.  Please relaunch Chatter from your course to continue.',
      icon: 'error',
    })
  } else {
    sweetalert2.fire({
      title: 'Error',
      text: 'Sorry, but an unexpected error happened.  Please try again.',
      icon: 'error',
    })
  }
  throw error;
});


