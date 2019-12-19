import Vue from 'vue';
import Sidebar from '../components/admin/Sidebar.vue';
import router from './router';
import './fontawsome.ts';
import '../bootstrap';
import '../bulma';
import 'bulma-modal-fx/src/_js/modal-fx';
import '../sentry-vue';
import Buefy from 'buefy';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(Buefy, {
  defaultIconComponent: 'vue-fontawesome',
  defaultIconPack: 'fas'
  // defaultFieldLabelPosition: 'on-border',
});

// eslint-disable-next-line no-new
new Vue({
  el: '#app',
  components: { Sidebar },
  router
});
