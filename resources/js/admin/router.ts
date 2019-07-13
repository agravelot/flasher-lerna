import Vue from 'vue';
import VueRouter from 'vue-router';
import Dashboard from '../../../modules/Dashboard/Resources/assets/js/Dashboard.vue';
import AlbumsIndex from '../../../modules/Album/Resources/assets/js/components/admin/AlbumsIndex.vue';
import AlbumsCreate from '../../../modules/Album/Resources/assets/js/components/admin/AlbumsCreate.vue';
import AlbumsShowGallery from '../../../modules/Album/Resources/assets/js/components/admin/AlbumsShowGallery.vue';
import AlbumsEdit from '../../../modules/Album/Resources/assets/js/components/admin/AlbumsEdit.vue';
import CosplayersIndex from '../../../modules/Cosplayer/Resources/assets/js/components/admin/CosplayersIndex.vue';
import CosplayersCreate from '../../../modules/Cosplayer/Resources/assets/js/components/admin/CosplayersCreate.vue';
import CosplayersEdit from '../../../modules/Cosplayer/Resources/assets/js/components/admin/CosplayersEdit.vue';
import UsersIndex from '../../../modules/User/Resources/assets/js/UsersIndex.vue';
import UsersEdit from '../../../modules/User/Resources/assets/js/UsersEdit.vue';
import NotFound from '../../../modules/Core/Resources/assets/js/components/NotFound.vue';
import Settings from '../../../modules/Core/Resources/assets/js/components/Settings.vue';
import PagesIndex from '../../../modules/Core/Resources/assets/js/components/pages/PagesIndex.vue';

Vue.use(VueRouter);

const routes: Array<any> = [
    {
        path: '/admin',
        name: 'admin.dashboard',
        component: Dashboard,
        // component: () =>
        //     import(
        //         /* webpackChunkName: "dashboard" */ '../../../modules/Dashboard/Resources/assets/js/Dashboard.vue'
        //     ),
    },
    // Albums
    {
        path: '/admin/albums',
        name: 'admin.albums.index',
        component: AlbumsIndex,
        // component: () =>
        //     import(
        //         /* webpackChunkName: "albumsIndex" */ '../../../modules/Album/Resources/assets/js/components/admin/AlbumsIndex.vue'
        //     ),
    },
    {
        path: '/admin/albums/create',
        name: 'admin.albums.create',
        component: AlbumsCreate,
        // component: () =>
        //     import(
        //         /* webpackChunkName: "albumsCreate" */ '../../../modules/Album/Resources/assets/js/components/admin/AlbumsCreate.vue'
        //     ),
    },
    {
        path: '/admin/albums/:slug',
        name: 'admin.albums.show',
        component: AlbumsShowGallery,
        // component: () =>
        //     import(
        //         /* webpackChunkName: "albumsShow" */ '../../../modules/Album/Resources/assets/js/components/admin/AlbumsShowGallery.vue'
        //     ),
    },
    {
        path: '/admin/albums/:slug/edit',
        name: 'admin.albums.edit',
        component: AlbumsEdit,
        // component: () =>
        //     import(
        //         /* webpackChunkName: "albumsEdit" */ '../../../modules/Album/Resources/assets/js/components/admin/AlbumsEdit.vue'
        //     ),
    },
    //Cosplayers
    {
        path: '/admin/cosplayers',
        name: 'admin.cosplayers.index',
        component: CosplayersIndex,
        // component: () =>
        //     import(
        //         /* webpackChunkName: "cosplayersIndex" */ '../../../modules/Cosplayer/Resources/assets/js/components/admin/CosplayersIndex.vue'
        //     ),
    },
    {
        path: '/admin/cosplayers/create',
        name: 'admin.cosplayers.create',
        component: CosplayersCreate,
        // component: () =>
        //     import(
        //         /* webpackChunkName: "cosplayersCreate" */ '../../../modules/Cosplayer/Resources/assets/js/components/admin/CosplayersCreate.vue'
        //         ),
    },
    {
        path: '/admin/cosplayers/:slug/edit',
        name: 'admin.cosplayers.edit',
        component: CosplayersEdit,
        // component: () =>
        //     import(
        //         /* webpackChunkName: "cosplayersIndex" */ '../../../modules/Cosplayer/Resources/assets/js/components/admin/CosplayersEdit.vue'
        //     ),
    },
    // Users
    {
        path: '/admin/users',
        name: 'admin.users.index',
        component: UsersIndex,
        // component: () =>
        //     import(
        //         /* webpackChunkName: "cosplayersIndex" */ '../../../modules/User/Resources/assets/js/UsersIndex.vue'
        //     ),
    },
    {
        path: '/admin/users/:id/edit',
        name: 'admin.users.edit',
        component: UsersEdit,
        // component: () =>
        //     import(
        //         /* webpackChunkName: "cosplayersEdit" */ '../../../modules/User/Resources/assets/js/UserEdit.vue'
        //     ),
    },
    // Settings
    {
        path: '/admin/settings',
        name: 'admin.settings.index',
        component: Settings,
        // component: () =>
        //     import(
        //         /* webpackChunkName: "cosplayersIndex" */ '../../../modules/Core/Resources/assets/js/components/Settings.vue'
        //     ),
    },
    {
        path: '/admin/pages',
        name: 'admin.pages.index',
        component: PagesIndex,
        // component: () =>
        //     import(
        //         /* webpackChunkName: "cosplayersIndex" */ '../../../modules/Core/Resources/assets/js/components/pages/PagesIndex.vue'
        //     ),
    },
    { path: '*', name: '404', component: NotFound },
    // { path: '/admin/404', name: '404', component: NotFound },
    // { path: '*', redirect: '/admin/404' },
];

export default new VueRouter({
    mode: 'history',
    routes,
    linkActiveClass: 'is-active',
});
