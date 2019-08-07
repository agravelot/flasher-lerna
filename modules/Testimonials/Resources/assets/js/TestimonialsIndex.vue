<template>
    <div>
        <section>
            <div class="buttons">
<!--                <b-button-->
<!--                    tag="router-link"-->
<!--                    :to="{ name: 'admin.testimonials.create' }"-->
<!--                    type="is-success"-->
<!--                    icon-left="plus"-->
<!--                    >Add-->
<!--                </b-button>-->
                <b-button
                    type="is-danger"
                    icon-left="trash-alt"
                    :disabled="!checkedRows.length"
                    @click="confirmDeleteSelectedTestimonials()"
                >
                    Delete checked
                </b-button>
            </div>

            <b-table
                :data="testimonials"
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
                <template slot-scope="user">
                    <b-table-column field="name" label="Name" sortable>
                        <router-link
                            :to="{ name: 'admin.testimonials.edit', params: { id: user.row.id } }"
                        >
                            {{ user.row.name }}
                        </router-link>
                    </b-table-column>

                    <b-table-column field="email" label="E-mail" sortable>
                        <router-link
                            :to="{ name: 'admin.testimonials.edit', params: { id: user.row.id } }"
                        >
                            {{ user.row.email }}
                        </router-link>
                    </b-table-column>

                    <b-table-column field="published_at" label="Published" sortable>
                        <router-link
                            :to="{ name: 'admin.testimonials.edit', params: { id: user.row.id } }"
                        >
                            {{ user.row.published_at }}
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
import VueBuefy from '../../../../../resources/js/admin/Buefy.vue';
import Testimonial from './testimonial';


@Component({
    name: 'TestimonialsIndex',
})
export default class TestimonialsIndex extends VueBuefy {
    private testimonials: Array<Testimonial> = [];
    private checkedRows: Array<Testimonial> = [];
    private total: number = 0;
    private page: number = 1;
    perPage: number = 10;
    private loading: boolean = false;
    private sortField: string = 'id';
    private sortOrder: string = 'desc';
    showDetailIcon: boolean = true;
    defaultSortOrder: string = 'desc';

    created(): void {
        this.fetchTestimonials();
    }

    fetchTestimonials(): void {
        this.loading = true;
        const sortOrder = this.sortOrder === 'asc' ? '' : '-';

        this.axios
            .get('/api/admin/testimonials', {
                params: {
                    page: this.page,
                    sort: sortOrder + this.sortField,
                },
            })
            .then(res => res.data)
            .then(res => {
                this.perPage = res.meta.per_page;
                this.total = res.meta.total;
                this.testimonials = res.data;
                this.loading = false;
            })
            .catch(err => {
                this.testimonials = [];
                this.total = 0;
                this.loading = false;
                this.$snackbar.open({
                    message: 'Unable to load testimonials, maybe you are offline?',
                    type: 'is-danger',
                    position: 'is-top',
                    actionText: 'Retry',
                    indefinite: true,
                    onAction: () => {
                        this.fetchTestimonials();
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
        this.fetchTestimonials();
    }

    /*
     * Handle sort event
     */
    onSort(field: string, order: string): void {
        this.sortField = field;
        this.sortOrder = order;
        this.fetchTestimonials();
    }

    confirmDeleteSelectedTestimonials(): void {
        this.$dialog.confirm({
            title: 'Deleting Testimonials',
            message:
                'Are you sure you want to <b>delete</b> these testimonials? This action cannot be undone.',
            confirmText: 'Delete Testimonials',
            type: 'is-danger',
            hasIcon: true,
            onConfirm: () => {
                this.deleteSelectedTestimonials();
            },
        });
    }

    /**
     * Delete user from slug
     */
    deleteSelectedTestimonials(): void {
        this.checkedRows.forEach(user => {
            this.axios
                .delete(`/api/admin/testimonials/${user.id}`)
                .then(res => {
                    this.showSuccess('Testimonials deleted');
                    this.fetchTestimonials();
                })
                .catch(err => {
                    this.showError(`Unable to delete user <br> <small>${err.message}</small>`);
                    throw err;
                });
        });
    }
}
</script>
