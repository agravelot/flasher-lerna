<template>
    <div>
        <section>
            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <router-link :to="{ name: 'admin.cosplayers.create' }">
                            <button class="button field is-success">
                                <b-icon pack="fas" icon="plus"></b-icon>
                                <span>New</span>
                            </button>
                        </router-link>

                        <button
                            class="button field is-danger"
                            @click="confirmDeleteSelectedCosplayers()"
                            :disabled="!checkedRows.length"
                        >
                            <b-icon pack="fas" icon="trash-alt"></b-icon>
                            <span>Delete checked</span>
                        </button>
                    </div>
                </div>

                <div class="level-right">
                    <b-field class="is-pulled-right">
                        <b-input
                            placeholder="Search..."
                            type="search"
                            icon="search "
                            :loading="loading"
                            v-model="search"
                            @input="fetchCosplayers()"
                        >
                        </b-input>
                    </b-field>
                </div>
            </div>

            <b-table
                :data="cosplayers"
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
                :checked-rows.sync="checkedRows"
            >
                <template slot-scope="cosplayer">
                    <b-table-column field="name" label="Name" sortable>
                        <router-link
                            :to="{
                                name: 'admin.cosplayers.edit',
                                params: { slug: cosplayer.row.slug },
                            }"
                        >
                            {{ cosplayer.row.name }}
                        </router-link>
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
import VueBuefy from '../../../../../../../resources/js/admin/Buefy.vue';
import Cosplayer from '../../cosplayer';

@Component({
    name: 'CosplayersIndex',
    filters: {
        /**
         * Filter to truncate string, accepts a length parameter
         */
        truncate(value: string, length: number): string {
            return value.length > length ? value.substr(0, length) + '...' : value;
        },
    },
})
export default class CosplayersIndex extends VueBuefy {
    private cosplayers: Array<Cosplayer> = [];
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
        this.fetchCosplayers();
    }

    fetchCosplayers(): void {
        this.loading = true;
        const sortOrder = this.sortOrder === 'asc' ? '' : '-';

        this.axios
            .get('/api/admin/cosplayers', {
                params: {
                    page: this.page,
                    sort: sortOrder + this.sortField,
                    'filter[name]': this.search,
                },
            })
            .then(res => res.data)
            .then(res => {
                this.perPage = res.meta.per_page;
                this.total = res.meta.total;
                this.cosplayers = res.data;
                this.loading = false;
            })
            .catch(err => {
                this.cosplayers = [];
                this.total = 0;
                this.loading = false;
                this.$snackbar.open({
                    message: 'Unable to load cosplayers, maybe you are offline?',
                    type: 'is-danger',
                    position: 'is-top',
                    actionText: 'Retry',
                    indefinite: true,
                    onAction: () => {
                        this.fetchCosplayers();
                    },
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
        this.fetchCosplayers();
    }

    /*
     * Handle sort event
     */
    onSort(field: string, order: string): void {
        this.sortField = field;
        this.sortOrder = order;
        this.fetchCosplayers();
    }

    confirmDeleteSelectedCosplayers(): void {
        this.$dialog.confirm({
            title: 'Deleting Cosplayers',
            message:
                'Are you sure you want to <b>delete</b> these cosplayers? This action cannot be undone.',
            confirmText: 'Delete Cosplayers',
            type: 'is-danger',
            hasIcon: true,
            onConfirm: () => {
                this.deleteSelectedCosplayers();
            },
        });
    }

    /**
     * Delete cosplayer from slug
     */
    deleteSelectedCosplayers(): void {
        this.checkedRows.forEach(cosplayer => {
            this.axios
                .delete(`/api/admin/cosplayers/${cosplayer.slug}`)
                .then(res => {
                    this.showSuccess('Cosplayers deleted');
                    this.fetchCosplayers();
                })
                .catch(err => {
                    this.showError(`Unable to delete cosplayer <br> <small>${err.message}</small>`);
                    throw err;
                });
        });
    }
}
</script>
