<template>
    <div>
        <section class="hero is-info welcome is-small has-margin-bottom-sm">
            <div class="hero-body">
                <div class="container">
                    <h1 class="title is-1">Hello, {{ user }}.</h1>
                    <h2 class="subtitle">
                        I hope you are having a great day!
                    </h2>
                </div>
            </div>
        </section>
        <section class="info-tiles">
            <div class="tile is-ancestor has-text-centered">
                <router-link :to="{ name: 'admin.users.index' }" class="tile is-parent">
                    <article class="tile is-child box">
                        <p class="title">{{ usersCount }}</p>
                        <p class="subtitle">Users</p>
                    </article>
                </router-link>

                <router-link :to="{ name: 'admin.albums.index' }" class="tile is-parent">
                    <article class="tile is-child box">
                        <p class="title">{{ albumsCount }}</p>
                        <p class="subtitle">Albums</p>
                    </article>
                </router-link>

                <router-link :to="{ name: 'admin.albums.index' }" class="tile is-parent">
                    <article class="tile is-child box">
                        <p class="title">{{ albumMediasCount }}</p>
                        <p class="subtitle">Medias</p>
                    </article>
                </router-link>

                <router-link :to="{ name: 'admin.cosplayers.index' }" class="tile is-parent">
                    <article class="tile is-child box">
                        <p class="title">{{ cosplayersCount }}</p>
                        <p class="subtitle">Cosplayers</p>
                    </article>
                </router-link>

                <a class="tile is-parent">
                    <article class="tile is-child box">
                        <p class="title">{{ contactsCount }}</p>
                        <p class="subtitle">Contacts</p>
                    </article>
                </a>
            </div>
        </section>
    </div>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import VueBuefy from '../../../../../resources/js/admin/Buefy.vue';

@Component
export default class Dashboard extends VueBuefy {
    private loading: boolean = true;
    private cosplayersCount: number = 0;
    private usersCount: number = 0;
    private albumsCount: number = 0;
    private contactsCount: number = 0;
    private albumMediasCount: number = 0;
    private user: string;

    created(): void {
        this.fetchDashboard();
    }

    fetchDashboard(): void {
        this.loading = true;

        this.axios
            .get('/api/admin/dashboard')
            .then(res => res.data)
            .then(res => {
                this.user = res.user;
                this.cosplayersCount = res.cosplayersCount;
                this.usersCount = res.usersCount;
                this.albumsCount = res.albumsCount;
                this.contactsCount = res.contactsCount;
                this.albumMediasCount = res.albumMediasCount;
                this.loading = false;
            })
            .catch(err => {
                this.loading = false;
                this.$snackbar.open({
                    message: 'Unable to load dashboard, maybe you are offline?',
                    type: 'is-danger',
                    position: 'is-top',
                    actionText: 'Retry',
                    indefinite: true,
                    onAction: () => {
                        this.fetchDashboard();
                    },
                });
                throw err;
            });
    }
}
</script>
