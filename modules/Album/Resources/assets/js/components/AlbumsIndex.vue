<template>
    <div>
        <section>
            <button
                class="button field is-danger"
                @click="confirmDeleteSelectedAlbums"
                :disabled="!checkedRows.length"
            >
                <b-icon pack="fas" icon="trash-alt"></b-icon>
                <span>Delete checked</span>
            </button>

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
                :opened-detailed="defaultOpenedDetails"
                detailed
                detail-key="id"
                :show-detail-icon="showDetailIcon"
                icon-pack="fas"
                checkable
                :checked-rows.sync="checkedRows"
            >
                <template slot-scope="album">
                    <b-table-column field="id" label="ID" width="40" numeric sortable
                        >{{ album.row.id }}
                    </b-table-column>

                    <b-table-column field="title" label="Title" sortable>
                        <!--<template v-if="showDetailIcon"><a v-bind:href="`/admin/albums/${album.row.slug}/edit`">{{ album.row.title }}</a></template>-->
                        <router-link :to="{name: 'albums.edit', params: { slug: album.row.slug }}">{{ album.row.title }}</router-link>
                        <!--<template v-else>-->
                            <!--<a @click="toggle(album.row)">{{ album.row.title }}</a>-->
                        <!--</template>-->
                    </b-table-column>

                    <b-table-column field="status" label="Status" sortable centered>
                         <span v-if="album.row.private === 1" class="tag is-danger"
                               v-bind:title="'This album is private'">
                            {{ 'Private' }}
                        </span>
                        <span v-else-if="typeof album.row.published_at === 'string'" class="tag is-success"
                              v-bind:title="new Date(album.row.published_at).toLocaleDateString()">
                            {{ 'Published' }}
                        </span>
                        <span v-else class="tag is-dark"
                              v-bind:title="'This album is a draft'">
                            {{ 'Draft' }}
                        </span>
                    </b-table-column>
                </template>

                <template slot="detail" slot-scope="album">
                    <article class="media">
                        <figure class="media-left">
                            <p class="image is-64x64">
                                <img src="https://via.placeholder.com/128" />
                            </p>
                        </figure>
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    <strong>{{ album.row.title }}</strong>
                                    <small>{{ album.row.published_at }}</small>
                                    <br />
                                    {{ album.row.body | truncate(80) }}
                                </p>
                            </div>
                        </div>
                    </article>
                </template>

                <template slot="empty">
                    <section class="section">
                        <div class="content has-text-grey has-text-centered">
                            <p>
                                <b-icon pack="fas" icon="sad-tear" size="is-large"> </b-icon>
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

<script>

export default {
    name: "AlbumsIndex",
    data() {
        return {
            albums: [],
            defaultOpenedDetails: [],
            checkedRows: [],
            total: 0,
            page: 1,
            perPage: 10,
            loading: false,
            showDetailIcon: true,
            sortField: 'id',
            sortOrder: 'desc',
            defaultSortOrder: 'desc',
        };
    },
    created() {
        this.fetchAlbums();
    },
    // mounted() {
    //     this.fetchAlbums();
    // },
    filters: {
        /**
         * Filter to truncate string, accepts a length parameter
         */
        truncate(value, length) {
            return value.length > length ? value.substr(0, length) + '...' : value;
        },
    },
    methods: {
        fetchAlbums() {
            this.loading = true;
            const sortOrder = ((this.sortOrder === 'asc') ? '' : '-');

            Vue.axios.get('/api/admin/albums', {
                    params: {
                        page: this.page,
                        sort: sortOrder + this.sortField
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
        },
        showSuccess(message) {
            this.$toast.open({
                message: message,
                type: 'is-success',
            });
        },
        showError(message) {
            this.$toast.open({
                message: message,
                type: 'is-danger',
                duration: 5000,
            });
        },
        toggle(row) {
            this.$refs.table.toggleDetails(row);
        },
        /*
         * Handle page-change event
         */
        onPageChange(page) {
            this.page = page;
            this.fetchAlbums();
        },
        /*
         * Handle sort event
         */
        onSort(field, order) {
            this.sortField = field;
            this.sortOrder = order;
            this.fetchAlbums();
        },
        confirmDeleteSelectedAlbums() {
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
        },
        /**
         * Delete album from slug
         */
        deleteSelectedAlbums() {
            this.checkedRows.forEach(album =>{
                Vue.axios
                    .delete(`/api/admin/albums/${album.slug}`)
                    .then(res => {
                        this.showSuccess('Albums deleted');
                    })
                    .catch(err => {
                        this.showError(`Unable to delete album <br> <small>${err.message}</small>`);
                        throw err;
                    });
            });
            this.fetchAlbums();
        },
    },
};
</script>
