<template>
    <div>
        <p>
            <router-link :to="{ name: 'home' }">Home</router-link>
            <router-link :to="{ name: 'admin.albums.index' }">Albums</router-link>
            <router-link :to="{ name: 'admin.users.index' }">Users</router-link>
        </p>

        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import VueBuefy from '../../buefy';
@Component
export default class App extends VueBuefy {}
</script>
