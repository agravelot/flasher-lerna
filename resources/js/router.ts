import Vue from 'vue';
import VueRouter from 'vue-router';

import Dashboard from '../../modules/Dashboard/Resources/assets/js/Dashboard.vue';
import Settings from '../../modules/Core/Resources/assets/js/components/Settings.vue';
import PagesIndex from '../../modules/Core/Resources/assets/js/components/pages/PagesIndex.vue';
import UsersIndex from '../../modules/User/Resources/assets/js/UsersIndex.vue';
import AlbumsIndex from '../../modules/Album/Resources/assets/js/components/admin/AlbumsIndex.vue';
import AlbumsShow from '../../modules/Album/Resources/assets/js/components/front/AlbumsShowGallery.vue';
import AlbumsCreate from '../../modules/Album/Resources/assets/js/components/admin/AlbumsCreate.vue';
import AlbumsEdit from '../../modules/Album/Resources/assets/js/components/admin/AlbumsEdit.vue';
import CosplayersIndex from '../../modules/Cosplayer/Resources/assets/js/components/admin/CosplayersIndex.vue';
import CosplayersEdit from '../../modules/Cosplayer/Resources/assets/js/components/admin/CosplayersEdit.vue';

Vue.use(VueRouter);

const routes: Array<any> = [
    {
        path: '/admin/dashboard',
        name: 'admin.dashboard',
        component: Dashboard,
    },
    // Albums
    {
        path: '/admin/albums',
        name: 'admin.albums.index',
        component: AlbumsIndex,
    },
    {
        path: '/admin/albums/create',
        name: 'admin.albums.create',
        component: AlbumsCreate,
    },
    {
        path: '/admin/albums/:slug',
        name: 'admin.albums.show',
        component: AlbumsShow,
    },
    {
        path: '/admin/albums/:slug/edit',
        name: 'admin.albums.edit',
        component: AlbumsEdit,
    },
    //Cosplayers
    {
        path: '/admin/cosplayers',
        name: 'admin.cosplayers.index',
        component: CosplayersIndex,
    },
    {
        path: '/admin/cosplayers/:slug/edit',
        name: 'admin.cosplayers.edit',
        component: CosplayersEdit,
    },
    // Users
    {
        path: '/admin/users',
        name: 'admin.users.index',
        component: UsersIndex,
    },
    // Settings
    {
        path: '/admin/settings',
        name: 'admin.settings.index',
        component: Settings,
    },
    {
        path: '/admin/pages',
        name: 'admin.pages.index',
        component: PagesIndex,
    },
];

export default new VueRouter({
    mode: 'history',
    routes,
    linkActiveClass: 'is-active',
});
