import Vue from 'vue'
import Buefy from 'buefy';
import '../sass/buefy.scss';
Vue.use(Buefy);

export default class VueBuefy extends Vue {
    $dialog: any;
    $loading: any;
    $modal: any;
    $snackbar: any;
    $toast: any;
    $refs: any;
}