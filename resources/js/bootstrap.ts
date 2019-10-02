import Vue from 'vue';
import axios from 'axios';
import VueAxios from 'vue-axios';

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
Vue.use(VueAxios, axios);

Vue.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
