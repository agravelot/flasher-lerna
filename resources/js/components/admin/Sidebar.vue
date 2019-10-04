<template>
    <div class="column is-one-fifth" id="admin-menu">
        <div>
            <aside class="menu">
                <ul class="menu-list has-margin-bottom-md">
                    <li class="menu-label">
                        Administration
                    </li>

                    <router-link :to="{ name: 'admin.dashboard' }" exact>
                        <b-icon icon="home"></b-icon>
                        Dashboard
                    </router-link>

                    <li>
                        <router-link :to="{ name: 'admin.albums.index' }">
                            <b-icon icon="images"></b-icon>
                            Albums
                        </router-link>
                    </li>

                    <li>
                        <router-link :to="{ name: 'admin.categories.index' }">
                            <b-icon icon="tags"></b-icon>
                            Categories
                        </router-link>
                    </li>

                    <li>
                        <router-link :to="{ name: 'admin.cosplayers.index' }">
                            <b-icon icon="user-tag"></b-icon>
                            Cosplayers
                        </router-link>
                    </li>

                    <li>
                        <router-link :to="{ name: 'admin.contacts.index' }">
                            <b-icon icon="pen-fancy"></b-icon>
                            Contacts
                        </router-link>
                    </li>

                    <li>
                        <router-link :to="{ name: 'admin.users.index' }">
                            <b-icon icon="user"></b-icon>
                            Users
                        </router-link>
                    </li>

                    <li>
                        <router-link :to="{ name: 'admin.testimonials.index' }">
                            <b-icon icon="book"></b-icon>
                            Testimonials
                        </router-link>
                    </li>

                    <li>
                        <a href="/admin/social-medias">
                            <b-icon icon="external-link-alt"></b-icon>
                            Social medias
                        </a>
                    </li>

                    <li>
                        <router-link :to="{ name: 'admin.settings.index' }">
                            <b-icon icon="cog"></b-icon>
                            Settings
                        </router-link>
                    </li>
                </ul>

                <ul class="menu-list">
                    <li class="menu-label">
                        Monitoring
                    </li>
                    <li>
                        <a href="#">
                            <b-icon icon="tachometer-alt"></b-icon>
                            Statistics
                        </a>
                    </li>
                    <li>
                        <a href="/horizon">
                            <b-icon icon="tasks"></b-icon>
                            Background tasks
                        </a>
                    </li>
                </ul>

                <ul class="menu-list">
                    <li>
                        <a class="has-text-info has-margin-top-lg" href="/">
                            <b-icon icon="chevron-circle-left"></b-icon>
                            Back to the website
                        </a>
                    </li>
                </ul>
            </aside>
        </div>

        <div class="admin-collapse-button">
            <a class="has-text-info has-margin-top-lg" id="collapse-admin-menu">
                <b-icon icon="chevron-circle-left"></b-icon>
                Collapse
            </a>
        </div>
    </div>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import VueBuefy from '../../admin/Buefy.vue';

@Component
export default class Sidebar extends VueBuefy {}
</script>
