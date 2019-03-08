<template>
    <div>
        <p>
            <router-link :to="{ name: 'home' }">Home</router-link>
            <router-link :to="{ name: 'admin.albums.index' }">Albums</router-link>
            <router-link :to="{ name: 'admin.albums.create' }">Albums create</router-link>
            <!--<router-link :to="{ name: 'albums.show' }">show</router-link>-->
            <router-link :to="{ name: 'admin.users.index' }">Users</router-link>
        </p>

        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
export default {};
</script>
