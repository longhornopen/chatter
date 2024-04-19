

import { Toast } from 'bootstrap';
window.addToast = function(message, config) {
  var variant = config.variant || 'novariant';
  var toast_template = document.getElementById('toast-template-container-'+variant).children[0];
  // clone toast_template into the 'toast-container' div
  var toast_container = document.getElementById('toast-container');
  var toast = toast_template.cloneNode(true);
  toast.querySelector('.toast-body').innerHTML = message;
  toast_container.appendChild(toast);

  let toast_obj = new Toast(toast);
  toast_obj.show();
}
