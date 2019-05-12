<template>
    <div>
        <section>
            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <router-link :to="{ name: 'admin.albums.create' }">
                            <button class="button field is-success">
                                <b-icon pack="fas" icon="plus"></b-icon>
                                <span>Add</span>
                            </button>
                        </router-link>
                        <button class="button field is-danger"
                                @click="confirmDeleteSelectedAlbums"
                                :disabled="!checkedRows.length">
                            <b-icon pack="fas" icon="trash-alt"></b-icon>
                            <span>Delete checked</span>
                        </button>
                    </div>
                </div>

                <div class="level-right">
                    <b-field class="is-pulled-right">
                        <b-input placeholder="Search..."
                                 type="search"
                                 icon="search "
                                 :loading="loading"
                                 v-model="search"
                                 @input="fetchAlbums()">
                        </b-input>
                    </b-field>
                </div>
            </div>

            <b-table
                    :data="albums"
                    :loading="loading"
                    striped
                    hoverable
                    mobile-cards
                    paginated
                    backend-pagination
                    :total="total"
                    :per-page="perPage"
                    @page-change="onPageChange"
                    backend-sorting
                    :default-sort-direction="defaultSortOrder"
                    :default-sort="[sortField, sortOrder]"
                    @sort="onSort"
                    icon-pack="fas"
                    checkable
                    :checked-rows.sync="checkedRows">
                <template slot-scope="album">
                    <b-table-column field="title" label="Title" sortable>
                        <router-link :to="{name: 'admin.albums.edit', params: { slug: album.row.slug }}">
                            {{ album.row.title }}
                        </router-link>
                    </b-table-column>

                    <b-table-column field="media_count" label="Nb. photos" centered numeric>
                        {{ album.row.media_count }}
                    </b-table-column>

                    <b-table-column field="status" label="Status" centered>
                        <span v-if="album.row.private === 1"
                              class="tag is-danger"
                              v-bind:title="'This album is private'">
                            {{ 'Private' }}
                        </span>
                        <span v-else-if="typeof album.row.published_at === 'string'"
                              class="tag is-success"
                              v-bind:title="new Date(album.row.published_at).toLocaleDateString()">
                            {{ 'Published' }}
                        </span>
                        <span v-else class="tag is-dark" v-bind:title="'This album is a draft'">{{ 'Draft' }}</span>
                    </b-table-column>
                </template>

                <template slot="empty">
                    <section class="section">
                        <div class="content has-text-grey has-text-centered">
                            <p>
                                <b-icon pack="fas" icon="sad-tear" size="is-large"></b-icon>
                            </p>
                            <p>Nothing here.</p>
                        </div>
                    </section>
                </template>

                <template slot="bottom-left">
                    <b>Total checked</b>
                    : {{ checkedRows.length }}
                </template>
            </b-table>
        </section>
    </div>
</template>

<script lang="ts">
    import Vue from 'vue';
    import Component from 'vue-class-component';
    import VueBuefy from "../../../../../../../resources/js/buefy";
    import Album from '../../album';

    @Component({
        name: "AlbumsIndex",
        filters: {
            /**
             * Filter to truncate string, accepts a length parameter
             */
            truncate(value: string, length: number): string {
                return value.length > length ? value.substr(0, length) + '...' : value;
            }
        }
    })
    export default class AlbumsIndex extends VueBuefy {

        private albums: Array<Album> = [];
        //TODO Clearer types
        defaultOpenedDetails: Array<any> = [];
        private checkedRows: Array<any> = [];
        private total: number = 0;
        private page: number = 1;
        perPage: number = 10;
        private loading: boolean = false;
        private sortField: string = 'id';
        private sortOrder: string = 'desc';
        showDetailIcon: boolean = true;
        defaultSortOrder: string = 'desc';
        private search: string = '';

        created(): void {
            this.fetchAlbums();
        }

        fetchAlbums(): void {
            this.loading = true;
            const sortOrder = ((this.sortOrder === 'asc') ? '' : '-');

            Vue.axios.get('/api/admin/albums', {
                params: {
                    page: this.page,
                    sort: sortOrder + this.sortField,
                    'filter[title]': this.search
                }
            })
                .then(res => res.data)
                .then(res => {
                    this.perPage = res.meta.per_page;
                    this.total = res.meta.total;
                    this.albums = res.data;
                    this.loading = false;
                })
                .catch(err => {
                    this.albums = [];
                    this.total = 0;
                    this.loading = false;
                    this.$snackbar.open({
                        message: 'Unable to load albums, maybe you are offline?',
                        type: 'is-danger',
                        position: 'is-top',
                        actionText: 'Retry',
                        indefinite: true,
                        onAction: () => {
                            this.fetchAlbums();
                        }
                    });
                    throw err;
                });
        }

        showSuccess(message: string): void {
            this.$toast.open({
                message: message,
                type: 'is-success',
            });
        }

        showError(message: string): void {
            this.$toast.open({
                message: message,
                type: 'is-danger',
                duration: 5000,
            });
        }

        toggle(row: object): void {
            this.$refs.table.toggleDetails(row);
        }

        /*
         * Handle page-change event
         */
        onPageChange(page: number): void {
            this.page = page;
            this.fetchAlbums();
        }

        /*
         * Handle sort event
         */
        onSort(field: string, order: string): void {
            this.sortField = field;
            this.sortOrder = order;
            this.fetchAlbums();
        }

        confirmDeleteSelectedAlbums(): void {
            this.$dialog.confirm({
                title: 'Deleting Albums',
                message:
                    'Are you sure you want to <b>delete</b> these albums? This action cannot be undone.',
                confirmText: 'Delete Albums',
                type: 'is-danger',
                hasIcon: true,
                onConfirm: () => {
                    this.deleteSelectedAlbums();
                },
            });
        }

        /**
         * Delete album from slug
         */
        deleteSelectedAlbums(): void {
            this.checkedRows.forEach(album => {
                Vue.axios
                    .delete(`/api/admin/albums/${album.slug}`)
                    .then(res => {
                        this.showSuccess('Albums deleted');
                        this.fetchAlbums();
                    })
                    .catch(err => {
                        this.showError(`Unable to delete album <br> <small>${err.message}</small>`);
                        throw err;
                    });
            });
        }
    }
</script>
