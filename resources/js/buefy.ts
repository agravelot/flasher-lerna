import Vue from 'vue'
import Buefy from 'buefy';
Vue.use(Buefy);
import '../sass/buefy.scss';

export default class VueBuefy extends Vue {
    $dialog: any;
    $loading: any;
    $modal: any;
    $snackbar: any;
    $toast: any;
}