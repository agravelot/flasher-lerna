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
    }
</script>

<style lang="scss">
    @import "../../../resources/sass/app";
    @import "~buefy/src/scss/buefy";
</style>
