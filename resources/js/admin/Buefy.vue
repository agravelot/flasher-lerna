<script lang="ts">
import Vue from 'vue';
import Buefy from 'buefy';

Vue.use(Buefy, {
    defaultIconPack: 'fas',
});

export default class VueBuefy extends Vue {
    $dialog: any;
    $loading: any;
    $modal: any;
    $snackbar: any;
    $toast: any;
    $refs: any;

    showSuccess(message: string): void {
        this.$buefy.toast.open({
            message: message,
            type: 'is-success',
        });
    }

    showError(message: string): void {
        this.$buefy.toast.open({
            message: message,
            type: 'is-danger',
            duration: 5000,
        });
    }
}
</script>

<style lang="scss">
@import '../../../resources/sass/app';
@import '~buefy/src/scss/buefy';
</style>
