<script lang="ts">
import Vue from 'vue';
import './fontawsome';

export default class Buefy extends Vue {
    public $dialog: any;
    public $loading: any;
    public $modal: any;
    public $snackbar: any;
    public $toast: any;
    public $refs: any;

    public showSuccess(message: string): void {
        this.$buefy.toast.open({
            message: message,
            type: 'is-success',
        });
    }

    public showError(message: string): void {
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
