<template>
    <div>{{ settings }}</div>
</template>

<script lang="ts">
    import Vue from 'vue';
    import Component from 'vue-class-component';
    import VueBuefy from "../../../../../resources/js/buefy";

    class Setting {
        public name: string;
        public value: string;
    }

    @Component
    export default class Settings extends VueBuefy {

        private loading: boolean = false;
        private settings: Array<Setting> = [];

        created(): void {
            this.fetchSettings();
        }

        fetchSettings(): void {
            this.loading = true;

            Vue.axios.get('/api/admin/settings')
                .then(res => res.data)
                .then(res => {
                    this.settings = res.data;
                    this.loading = false;
                })
                .catch(err => {
                    this.settings = [];
                    this.loading = false;
                    this.$snackbar.open({
                        message: 'Unable to load settings, maybe you are offline?',
                        type: 'is-danger',
                        position: 'is-top',
                        actionText: 'Retry',
                        indefinite: true,
                        onAction: () => {
                            this.fetchSettings();
                        }
                    });
                    throw err;
                });
        }

    }
</script>
