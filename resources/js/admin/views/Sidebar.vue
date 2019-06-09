<template>
    <div class="column is-one-fifth" id="admin-menu">
        <div>
            <aside class="menu">
                <ul class="menu-list has-margin-bottom-md">
                    <li class="menu-label">
                        Administration
                    </li>

                    <router-link :to="{ name: 'admin.dashboard' }" exact>
                        <span class="icon">
                            <i class="fas fa-home"></i>
                        </span>
                        Dashboard
                    </router-link>

                    <li>
                        <router-link :to="{ name: 'admin.albums.index' }">
                            <span class="icon">
                                <i class="fas fa-images"></i>
                            </span>
                            Albums
                        </router-link>
                    </li>

                    <li>
                        <a href="/admin/categories">
                            <span class="icon">
                                <i class="fas fa-tags"></i>
                            </span>
                            Categories
                        </a>
                    </li>

                    <li>
                        <router-link :to="{ name: 'admin.cosplayers.index' }">
                            <span class="icon">
                                <i class="fas fa-user-tag"></i>
                            </span>
                            Cosplayers
                        </router-link>
                    </li>

                    <li>
                        <a href="/admin/cosplayers">
                            <span class="icon">
                                <i class="fas fa-user-tag"></i>
                            </span>
                            Cosplayers (old)
                        </a>
                    </li>

                    <li>
                        <router-link :to="{ name: 'admin.users.index' }">
                            <span class="icon">
                                <i class="fas fa-user"></i>
                            </span>
                            Users
                        </router-link>
                    </li>

                    <li>
                        <a href="/admin/users">
                            <span class="icon">
                                <i class="fas fa-user"></i>
                            </span>
                            Users (old)
                        </a>
                    </li>

                    <li>
                        <a href="/admin/contacts">
                            <span class="icon">
                                <i class="fas fa-pen-fancy"></i>
                            </span>
                            Contacts
                        </a>
                    </li>

                    <li>
                        <a href="/admin/goldenbook">
                            <span class="icon">
                                <i class="fas fa-book"></i>
                            </span>
                            Golden book
                        </a>
                    </li>

                    <li>
                        <a href="/admin/social-medias">
                            <span class="icon">
                                <i class="fas fa-external-link-alt"></i>
                            </span>
                            Social medias
                        </a>
                    </li>

                    <li>
                        <router-link :to="{ name: 'admin.settings.index' }">
                            <span class="icon">
                                <i class="fas fa-cog"></i>
                            </span>
                            Settings
                        </router-link>
                    </li>

                    <li>
                        <router-link :to="{ name: 'admin.pages.index' }">
                            <span class="icon">
                                <i class="fas fa-page"></i>
                            </span>
                            Pages
                        </router-link>
                    </li>
                </ul>

                <ul class="menu-list">
                    <li class="menu-label">
                        Monitoring
                    </li>
                    <li>
                        <a href="#">
                            <span class="icon">
                                <i class="fas fa-tachometer-alt"></i>
                            </span>
                            Statistics
                        </a>
                    </li>
                    <li>
                        <a href="/horizon">
                            <span class="icon">
                                <i class="fas fa-tasks"></i>
                            </span>
                            Background tasks
                        </a>
                    </li>
                </ul>

                <ul class="menu-list">
                    <li>
                        <a class="has-text-info has-margin-top-lg" href="/">
                            <span class="icon">
                                <i class="fas fa-chevron-circle-left"></i>
                            </span>
                            Back to the website
                        </a>
                    </li>
                </ul>
            </aside>
        </div>

        <div class="admin-collapse-button">
            <a class="has-text-info has-margin-top-lg" id="collapse-admin-menu">
                <span class="icon">
                    <i class="fas fa-chevron-circle-left"></i>
                </span>
                Collapse
            </a>
        </div>
    </div>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import VueBuefy from '../Buefy.vue';

@Component
export default class Sidebar extends VueBuefy {}
</script>
