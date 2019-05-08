import Vue from 'vue';
import VueRouter from 'vue-router';

import Dashboard from './admin/views/Dashboard.vue';
import UsersIndex from './admin/views/UsersIndex.vue';
import AlbumsIndex from '../../modules/Album/Resources/assets/js/components/admin/AlbumsIndex.vue';
import AlbumsShow from '../../modules/Album/Resources/assets/js/components/front/AlbumsShowGallery.vue';
import AlbumsCreate from '../../modules/Album/Resources/assets/js/components/admin/AlbumsCreate.vue';
import AlbumsEdit from '../../modules/Album/Resources/assets/js/components/admin/AlbumsEdit.vue';
import CosplayersIndex from "../../modules/Cosplayer/Resources/assets/js/components/admin/CosplayersIndex.vue";

Vue.use(VueRouter);

const routes: Array<any> = [
    {
        path: '/admin/spa/',
        name: 'admin.dashboard',
        component: Dashboard,
    },
    // Albums
    {
        path: '/admin/spa/albums',
        name: 'admin.albums.index',
        component: AlbumsIndex,
    },
    {
        path: '/admin/spa/create',
        name: 'admin.albums.create',
        component: AlbumsCreate,
    },
    {
        path: '/admin/spa/:slug',
        name: 'admin.albums.show',
        component: AlbumsShow,
    },
    {
        path: '/admin/spa/:slug/edit',
        name: 'admin.albums.edit',
        component: AlbumsEdit,
    },
    //Cosplayers
    {
        path: '/admin/spa/cosplayers',
        name: 'admin.cosplayers.index',
        component: CosplayersIndex,
    },
    // Users
    {
        path: '/admin/spa/users',
        name: 'admin.users.index',
        component: UsersIndex,
    },
];

export default new VueRouter({
    mode: 'history',
    routes,
});
