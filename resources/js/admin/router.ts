import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

const routes: Array<any> = [
    {
        path: '/admin',
        name: 'admin.dashboard',
        component: () =>
            import(
                /* webpackChunkName: "dashboard" */ '../../../modules/Dashboard/Resources/assets/js/Dashboard.vue'
            ),
    },
    // Albums
    {
        path: '/admin/albums',
        name: 'admin.albums.index',
        component: () =>
            import(
                /* webpackChunkName: "albumsIndex" */ '../../../modules/Album/Resources/assets/js/components/admin/AlbumsIndex.vue'
            ),
    },
    {
        path: '/admin/albums/create',
        name: 'admin.albums.create',
        component: () =>
            import(
                /* webpackChunkName: "albumsCreate" */ '../../../modules/Album/Resources/assets/js/components/admin/AlbumsCreate.vue'
            ),
    },
    {
        path: '/admin/albums/:slug',
        name: 'admin.albums.show',
        component: () =>
            import(
                /* webpackChunkName: "albumsShow" */ '../../../modules/Album/Resources/assets/js/components/admin/AlbumsShowGallery.vue'
            ),
    },
    {
        path: '/admin/albums/:slug/edit',
        name: 'admin.albums.edit',
        component: () =>
            import(
                /* webpackChunkName: "albumsEdit" */ '../../../modules/Album/Resources/assets/js/components/admin/AlbumsEdit.vue'
            ),
    },
    //Cosplayers
    {
        path: '/admin/cosplayers',
        name: 'admin.cosplayers.index',
        component: () =>
            import(
                /* webpackChunkName: "cosplayersIndex" */ '../../../modules/Cosplayer/Resources/assets/js/components/admin/CosplayersIndex.vue'
            ),
    },
    {
        path: '/admin/cosplayers/create',
        name: 'admin.cosplayers.create',
        component: () =>
            import(
                /* webpackChunkName: "cosplayersCreate" */ '../../../modules/Cosplayer/Resources/assets/js/components/admin/CosplayersCreate.vue'
                ),
    },
    {
        path: '/admin/cosplayers/:slug/edit',
        name: 'admin.cosplayers.edit',
        component: () =>
            import(
                /* webpackChunkName: "cosplayersIndex" */ '../../../modules/Cosplayer/Resources/assets/js/components/admin/CosplayersEdit.vue'
            ),
    },
    // Users
    {
        path: '/admin/users',
        name: 'admin.users.index',
        component: () =>
            import(
                /* webpackChunkName: "cosplayersIndex" */ '../../../modules/User/Resources/assets/js/UsersIndex.vue'
            ),
    },
    // Settings
    {
        path: '/admin/settings',
        name: 'admin.settings.index',
        component: () =>
            import(
                /* webpackChunkName: "cosplayersIndex" */ '../../../modules/Core/Resources/assets/js/components/Settings.vue'
            ),
    },
    {
        path: '/admin/pages',
        name: 'admin.pages.index',
        component: () =>
            import(
                /* webpackChunkName: "cosplayersIndex" */ '../../../modules/Core/Resources/assets/js/components/pages/PagesIndex.vue'
            ),
    },
];

export default new VueRouter({
    mode: 'history',
    routes,
    linkActiveClass: 'is-active',
});
