
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

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


