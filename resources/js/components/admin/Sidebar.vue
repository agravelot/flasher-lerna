<template>
  <div
    id="admin-menu"
    class="column is-one-fifth"
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
          label="Social medias"
          icon="external-link-alt"
          tag="a"
          to="/admin/social-medias"
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
          tag="a"
          to="/horizon"
        />
      </b-menu-list>
    </b-menu>

    <div>
      <aside class="menu">
        <ul class="menu-list">
          <li>
            <a
              class="has-text-info has-margin-top-lg"
              href="/"
            >
              <b-icon icon="chevron-circle-left" />
              Back to the website
            </a>
          </li>
        </ul>
      </aside>
    </div>

    <div class="admin-collapse-button">
      <a
        id="collapse-admin-menu"
        class="has-text-info has-margin-top-lg"
      >
        <b-icon icon="chevron-circle-left" />
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
