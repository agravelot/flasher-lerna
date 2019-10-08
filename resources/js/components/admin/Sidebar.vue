<template>
    <div class="column is-one-fifth" id="admin-menu">

        <b-menu>
            <b-menu-list label="Administration">
                <b-menu-item label="Dashboard" icon="home" tag="router-link" :to="{ name: 'admin.dashboard' }" exact></b-menu-item>
                <b-menu-item label="Albums" icon="images" tag="router-link" :to="{ name: 'admin.albums.index' }"></b-menu-item>
                <b-menu-item label="Categories" icon="tags" tag="router-link" :to="{ name: 'admin.categories.index' }"></b-menu-item>
                <b-menu-item label="Cosplayers" icon="user-tag" tag="router-link" :to="{ name: 'admin.cosplayers.index' }"></b-menu-item>
                <b-menu-item label="Contacts" icon="pen-fancy" tag="router-link" :to="{ name: 'admin.contacts.index' }"></b-menu-item>
                <b-menu-item label="Users" icon="user" tag="router-link" :to="{ name: 'admin.users.index' }"></b-menu-item>
                <b-menu-item label="Testimonials" icon="book" tag="router-link" :to="{ name: 'admin.testimonials.index' }"></b-menu-item>
                <b-menu-item label="Social medias" icon="external-link-alt" tag="a" to="/admin/social-medias"></b-menu-item>
                <b-menu-item label="Settings" icon="cog" tag="router-link" :to="{ name: 'admin.settings.index' }"></b-menu-item>
            </b-menu-list>

            <b-menu-list label="Monitoring">
                <b-menu-item label="Background tasks" icon="tasks" tag="a" to="/horizon"></b-menu-item>
            </b-menu-list>
        </b-menu>

        <div>
            <aside class="menu">

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
import Buefy from '../../admin/Buefy.vue';

@Component
export default class Sidebar extends Buefy {}
</script>
