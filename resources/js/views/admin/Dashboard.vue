<template>
  <div>
    <b-loading
      :is-full-page="false"
      :active="loading"
    />
    <div v-if="!loading">
      <section class="hero is-info welcome is-small has-margin-bottom-sm">
        <div class="hero-body">
          <div class="container">
            <h1 class="title is-1">
              Hello, {{ username }}.
            </h1>
            <h2 class="subtitle">
              I hope you are having a great day!
            </h2>
          </div>
        </div>
      </section>
      <section class="info-tiles">
        <div class="tile is-ancestor has-text-centered">
          <router-link
            :to="{ name: 'admin.users.index' }"
            class="tile is-parent"
          >
            <article class="tile is-child box">
              <p class="title">
                {{ usersCount }}
              </p>
              <p class="subtitle">
                Users
              </p>
            </article>
          </router-link>

          <router-link
            :to="{ name: 'admin.albums.index' }"
            class="tile is-parent"
          >
            <article class="tile is-child box">
              <p class="title">
                {{ albumsCount }}
              </p>
              <p class="subtitle">
                Albums
              </p>
            </article>
          </router-link>

          <router-link
            :to="{ name: 'admin.albums.index' }"
            class="tile is-parent"
          >
            <article class="tile is-child box">
              <p class="title">
                {{ albumMediasCount }}
              </p>
              <p class="subtitle">
                Medias
              </p>
            </article>
          </router-link>

          <router-link
            :to="{ name: 'admin.cosplayers.index' }"
            class="tile is-parent"
          >
            <article class="tile is-child box">
              <p class="title">
                {{ cosplayersCount }}
              </p>
              <p class="subtitle">
                Cosplayers
              </p>
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
  </div>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import Buefy from '../../admin/Buefy.vue';
import {showError} from "../../admin/toast";

@Component
export default class Dashboard extends Buefy {
    private loading = true;
    private cosplayersCount = 0;
    private usersCount = 0;
    private albumsCount = 0;
    private contactsCount = 0;
    private albumMediasCount = 0;
    private username: string | null = null;

    created(): void {
        this.fetchDashboard();
    }

    fetchDashboard(): void {
        this.loading = true;

        this.axios
            .get('/api/admin/dashboard')
            .then(res => res.data)
            .then(res => {
                this.username = res.user;
                this.cosplayersCount = res.cosplayersCount;
                this.usersCount = res.usersCount;
                this.albumsCount = res.albumsCount;
                this.contactsCount = res.contactsCount;
                this.albumMediasCount = res.albumMediasCount;
                this.loading = false;
            })
            .catch(err => {
                this.loading = false;
                showError('Unable to load dashboard, maybe you are offline?', this.fetchDashboard);
                throw err;
            });
    }
}
</script>
