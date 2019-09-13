<template>
    <div>
        <section>
            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <div class="buttons">
                            <b-button
                                tag="router-link"
                                :to="{ name: 'admin.categories.create' }"
                                type="is-success"
                                icon-left="plus"
                                >Add
                            </b-button>
                            <b-button
                                type="is-danger"
                                icon-left="trash-alt"
                                :disabled="!checkedRows.length"
                                @click="confirmDeleteSelectedCategories"
                            >
                                Delete checked
                            </b-button>
                        </div>
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
                            @input="fetchCategories()"
                        >
                        </b-input>
                    </b-field>
                </div>
            </div>

            <b-table
                :data="categories"
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
                <template slot-scope="category">
                    <b-table-column field="name" label="Name" sortable>
                        <router-link
                            :to="{
                                name: 'admin.categories.edit',
                                params: { slug: category.row.slug },
                            }"
                        >
                            {{ category.row.name }}
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
import Component from 'vue-class-component';
import VueBuefy from '../../admin/Buefy.vue';
import Category from '../../category';

@Component({
    name: 'CategoriesIndex',
    filters: {
        /**
         * Filter to truncate string, accepts a length parameter
         */
        truncate(value: string, length: number): string {
            return value.length > length ? value.substr(0, length) + '...' : value;
        },
    },
})
export default class CategoriesIndex extends VueBuefy {
    private categories: Array<Category> = [];
    private checkedRows: Array<Category> = [];
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
        this.fetchCategories();
    }

    fetchCategories(): void {
        this.loading = true;
        const sortOrder = this.sortOrder === 'asc' ? '' : '-';

        this.axios
            .get('/api/admin/categories', {
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
                this.categories = res.data;
                this.loading = false;
            })
            .catch(err => {
                this.categories = [];
                this.total = 0;
                this.loading = false;
                this.$snackbar.open({
                    message: 'Unable to load categories, maybe you are offline?',
                    type: 'is-danger',
                    position: 'is-top',
                    actionText: 'Retry',
                    indefinite: true,
                    onAction: () => {
                        this.fetchCategories();
                    },
                });
                throw err;
            });
    }

    /*
     * Handle page-change event
     */
    onPageChange(page: number): void {
        this.page = page;
        this.fetchCategories();
    }

    /*
     * Handle sort event
     */
    onSort(field: string, order: string): void {
        this.sortField = field;
        this.sortOrder = order;
        this.fetchCategories();
    }

    confirmDeleteSelectedCategories(): void {
        this.$dialog.confirm({
            title: 'Deleting Categories',
            message:
                'Are you sure you want to <b>delete</b> these categories? This action cannot be undone.',
            confirmText: 'Delete Categories',
            type: 'is-danger',
            hasIcon: true,
            onConfirm: () => {
                this.deleteSelectedCategories();
            },
        });
    }

    /**
     * Delete category from slug
     */
    deleteSelectedCategories(): void {
        this.checkedRows.forEach(category => {
            this.axios
                .delete(`/api/admin/categories/${category.slug}`)
                .then(res => {
                    this.showSuccess('Categories deleted');
                    this.fetchCategories();
                })
                .catch(err => {
                    this.showError(`Unable to delete category <br> <small>${err.message}</small>`);
                    throw err;
                });
        });
    }
}
</script>
