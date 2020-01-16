<template>
  <div
    id="admin-menu"
    style="width: 200px;"
  >
    <b-menu>
      <b-menu-list label="Administration">
        <b-menu-item
          :to="{ name: 'admin.dashboard' }"
          label="Dashboard"
          icon="home"
          tag="router-link"
          exact
        />
        <b-menu-item
          :to="{ name: 'admin.albums.index' }"
          label="Albums"
          icon="images"
          tag="router-link"
        />
        <b-menu-item
          :to="{ name: 'admin.categories.index' }"
          label="Categories"
          icon="tags"
          tag="router-link"
        />
        <b-menu-item
          :to="{ name: 'admin.cosplayers.index' }"
          label="Cosplayers"
          icon="user-tag"
          tag="router-link"
        />
        <b-menu-item
          :to="{ name: 'admin.contacts.index' }"
          label="Contacts"
          icon="pen-fancy"
          tag="router-link"
        />
        <b-menu-item
          :to="{ name: 'admin.users.index' }"
          label="Users"
          icon="user"
          tag="router-link"
        />
        <b-menu-item
          :to="{ name: 'admin.testimonials.index' }"
          label="Testimonials"
          icon="book"
          tag="router-link"
        />
        <b-menu-item
          :to="{ name: 'admin.social-medias.index' }"
          label="Social medias"
          icon="external-link-alt"
          tag="router-link"
        />
        <b-menu-item
          :to="{ name: 'admin.invitations.index' }"
          label="Invitations"
          icon="envelope-open-text"
          tag="router-link"
        />
        <b-menu-item
          :to="{ name: 'admin.settings.index' }"
          label="Settings"
          icon="cog"
          tag="router-link"
        />
      </b-menu-list>

      <b-menu-list label="Monitoring">
        <b-menu-item
          label="Background tasks"
          icon="tasks"
          href="a"
          to="/admin/horizon"
        />
      </b-menu-list>
    </b-menu>
  </div>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import Buefy from '../../admin/Buefy.vue';

@Component
export default class Sidebar extends Buefy {}
</script>
