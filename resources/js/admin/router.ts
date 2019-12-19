import Vue from 'vue';
import VueRouter from 'vue-router';
import Dashboard from '../views/admin/Dashboard.vue';
import AlbumsIndex from '../views/admin/albums/AlbumsIndex.vue';
import AlbumsCreate from '../views/admin/albums/AlbumsCreate.vue';
import AlbumsEdit from '../views/admin/albums/AlbumsEdit.vue';
import CategoriesIndex from '../views/admin/categories/CategoriesIndex.vue';
import CategoriesCreate from '../views/admin/categories/CategoriesCreate.vue';
import CategoriesEdit from '../views/admin/categories/CategoriesEdit.vue';
import ConstactsIndex from '../views/admin/contacts/ContactsIndex.vue';
import CosplayersIndex from '../views/admin/cosplayers/CosplayersIndex.vue';
import CosplayersCreate from '../views/admin/cosplayers/CosplayersCreate.vue';
import CosplayersEdit from '../views/admin/cosplayers/CosplayersEdit.vue';
import UsersIndex from '../views/admin/users/UsersIndex.vue';
import UsersCreate from '../views/admin/users/UsersCreate.vue';
import UsersEdit from '../views/admin/users/UsersEdit.vue';
import TestimonialsIndex from '../views/admin/testimonials/TestimonialsIndex.vue';
import NotFound from '../views/admin/NotFound.vue';
import Settings from '../views/admin/Settings.vue';
import InvitationsIndex from '../views/admin/invitations/InvitationsIndex.vue';
import InvitationsCreate from '../views/admin/invitations/InvitationsCreate.vue';
import SocialMediasIndex from '../views/admin/social-medias/SocialMediasIndex.vue';

Vue.use(VueRouter);

const routes: Array<any> = [
  {
    path: '/admin',
    name: 'admin.dashboard',
    component: Dashboard
    // component: () =>
    //     import(
    //         /* webpackChunkName: "dashboard" */ '../../../modules/Dashboard/Resources/assets/js/Dashboard.vue'
    //     ),
  },
  // Albums
  {
    path: '/admin/albums',
    name: 'admin.albums.index',
    component: AlbumsIndex
    // component: () =>
    //     import(
    //         /* webpackChunkName: "albumsIndex" */ '../../../modules/Album/Resources/assets/js/components/admin/AlbumsIndex.vue'
    //     ),
  },
  {
    path: '/admin/albums/create',
    name: 'admin.albums.create',
    component: AlbumsCreate
    // component: () =>
    //     import(
    //         /* webpackChunkName: "albumsCreate" */ '../../../modules/Album/Resources/assets/js/components/admin/AlbumsCreate.vue'
    //     ),
  },
  // {
  //     path: '/admin/albums/:slug',
  //     name: 'admin.albums.show',
  //     component: AlbumsShowGallery,
  //     // component: () =>
  //     //     import(
  //     //         /* webpackChunkName: "albumsShow" */ '../../../modules/Album/Resources/assets/js/components/admin/AlbumsShowGallery.vue'
  //     //     ),
  // },
  {
    path: '/admin/albums/:slug/edit',
    name: 'admin.albums.edit',
    component: AlbumsEdit
    // component: () =>
    //     import(
    //         /* webpackChunkName: "albumsEdit" */ '../../../modules/Album/Resources/assets/js/components/admin/AlbumsEdit.vue'
    //     ),
  },
  // Categories
  {
    path: '/admin/categories',
    name: 'admin.categories.index',
    component: CategoriesIndex
    // component: () =>
    //     import(
    //         /* webpackChunkName: "categoriesIndex" */ '../../../modules/Category/Resources/assets/js/components/admin/CategoriesIndex.vue'
    //     ),
  },
  {
    path: '/admin/categories/create',
    name: 'admin.categories.create',
    component: CategoriesCreate
    // component: () =>
    //     import(
    //         /* webpackChunkName: "categoriesCreate" */ '../../../modules/Category/Resources/assets/js/components/admin/CategoriesCreate.vue'
    //     ),
  },
  {
    path: '/admin/categories/:slug/edit',
    name: 'admin.categories.edit',
    component: CategoriesEdit
    // component: () =>
    //     import(
    //         /* webpackChunkName: "categoriesEdit" */ '../../../modules/Category/Resources/assets/js/components/admin/CategoriesEdit.vue'
    //     ),
  },
  // Contacts
  {
    path: '/admin/contacts',
    name: 'admin.contacts.index',
    component: ConstactsIndex
    // component: () =>
    //     import(
    //         /* webpackChunkName: "cosplayersIndex" */ '../../../modules/Cosplayer/Resources/assets/js/components/admin/CosplayersIndex.vue'
    //     ),
  },
  // Cosplayers
  {
    path: '/admin/cosplayers',
    name: 'admin.cosplayers.index',
    component: CosplayersIndex
    // component: () =>
    //     import(
    //         /* webpackChunkName: "cosplayersIndex" */ '../../../modules/Cosplayer/Resources/assets/js/components/admin/CosplayersIndex.vue'
    //     ),
  },
  {
    path: '/admin/cosplayers/create',
    name: 'admin.cosplayers.create',
    component: CosplayersCreate
    // component: () =>
    //     import(
    //         /* webpackChunkName: "cosplayersCreate" */ '../../../modules/Cosplayer/Resources/assets/js/components/admin/CosplayersCreate.vue'
    //         ),
  },
  {
    path: '/admin/cosplayers/:slug/edit',
    name: 'admin.cosplayers.edit',
    component: CosplayersEdit
    // component: () =>
    //     import(
    //         /* webpackChunkName: "cosplayersIndex" */ '../../../modules/Cosplayer/Resources/assets/js/components/admin/CosplayersEdit.vue'
    //     ),
  },
  // Users
  {
    path: '/admin/users',
    name: 'admin.users.index',
    component: UsersIndex
    // component: () =>
    //     import(
    //         /* webpackChunkName: "cosplayersIndex" */ '../../../modules/User/Resources/assets/js/UsersIndex.vue'
    //     ),
  },
  {
    path: '/admin/users/create',
    name: 'admin.users.create',
    component: UsersCreate
    // component: () =>
    //     import(
    //         /* webpackChunkName: "cosplayersIndex" */ '../../../modules/User/Resources/assets/js/UsersIndex.vue'
    //     ),
  },
  {
    path: '/admin/users/:id/edit',
    name: 'admin.users.edit',
    component: UsersEdit
    // component: () =>
    //     import(
    //         /* webpackChunkName: "cosplayersEdit" */ '../../../modules/User/Resources/assets/js/UserEdit.vue'
    //     ),
  },
  // Social medias
  {
    path: '/admin/social-medias',
    name: 'admin.social-medias.index',
    component: SocialMediasIndex
    // component: () =>
    //     import(
    //         /* webpackChunkName: "cosplayersIndex" */ '../../../modules/User/Resources/assets/js/UsersIndex.vue'
    //     ),
  },
  // Testimonials
  {
    path: '/admin/testimonials',
    name: 'admin.testimonials.index',
    component: TestimonialsIndex
    // component: () =>
    //     import(
    //         /* webpackChunkName: "cosplayersIndex" */ '../../../modules/User/Resources/assets/js/UsersIndex.vue'
    //     ),
  },
  // {
  //     path: '/admin/users/create',
  //     name: 'admin.users.create',
  //     component: UsersCreate,
  // component: () =>
  //     import(
  //         /* webpackChunkName: "cosplayersIndex" */ '../../../modules/User/Resources/assets/js/UsersIndex.vue'
  //     ),
  // },
  // {
  //     path: '/admin/users/:id/edit',
  //     name: 'admin.users.edit',
  //     component: UsersEdit,
  // component: () =>
  //     import(
  //         /* webpackChunkName: "cosplayersEdit" */ '../../../modules/User/Resources/assets/js/UserEdit.vue'
  //     ),
  // },
  // Settings
  {
    path: '/admin/settings',
    name: 'admin.settings.index',
    component: Settings
    // component: () =>
    //     import(
    //         /* webpackChunkName: "cosplayersIndex" */ '../../../modules/Core/Resources/assets/js/components/Settings.vue'
    //     ),
  },
  {
    path: '/admin/invitations',
    name: 'admin.invitations.index',
    component: InvitationsIndex
    // component: () =>
    //     import(
    //         /* webpackChunkName: "cosplayersIndex" */ '../../../modules/Core/Resources/assets/js/components/Settings.vue'
    //     ),
  },
  {
    path: '/admin/invitations/create',
    name: 'admin.invitations.create',
    component: InvitationsCreate
    // component: () =>
    //     import(
    //         /* webpackChunkName: "cosplayersIndex" */ '../../../modules/Core/Resources/assets/js/components/Settings.vue'
    //     ),
  },
  { path: '*', name: '404', component: NotFound }
];

export default new VueRouter({
  mode: 'history',
  routes,
  linkActiveClass: 'is-active'
});
