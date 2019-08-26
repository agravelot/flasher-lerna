<template>
    <div>
        <section>
            <div class="buttons">
                <!--                <b-button-->
                <!--                    type="is-success"-->
                <!--                    icon-left="check"-->
                <!--                    >Publish-->
                <!--                </b-button>-->
                <b-button
                    type="is-success"
                    icon-left="check"
                    @click="toggleSelectedArePublishedAndUpdate()"
                    >Publish / Un-publish
                </b-button>
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
                <template slot-scope="testimonial">
                    <b-table-column field="name" label="Name" sortable>
                        <router-link
                            :to="{
                                name: 'admin.testimonials.edit',
                                params: { id: testimonial.row.id },
                            }"
                        >
                            {{ testimonial.row.name }}
                        </router-link>
                    </b-table-column>

                    <b-table-column field="email" label="E-mail" sortable>
                        <router-link
                            :to="{
                                name: 'admin.testimonials.edit',
                                params: { id: testimonial.row.id },
                            }"
                        >
                            {{ testimonial.row.email }}
                        </router-link>
                    </b-table-column>

                    <b-table-column field="published_at" label="Published" sortable>
                        <a
                            :title="testimonial.row.published_at"
                            @click="toggleIsPublishedAndUpdate(testimonial.row)"
                        >
                            <span v-if="testimonial.row.published_at">
                                <b-icon icon="check" size="is-small" type="is-success"> </b-icon>
                            </span>
                            <span v-else>
                                <b-icon icon="lock" size="is-small" type="is-warning"> </b-icon>
                            </span>
                        </a>
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
     * Delete testimonial from slug
     */
    deleteSelectedTestimonials(): void {
        this.checkedRows.forEach(testimonial => {
            this.axios
                .delete(`/api/admin/testimonials/${testimonial.id}`)
                .then(res => {
                    this.showSuccess('Testimonials deleted');
                    this.fetchTestimonials();
                })
                .catch(err => {
                    this.showError(
                        `Unable to delete testimonial <br> <small>${err.message}</small>`
                    );
                    throw err;
                });
        });
    }

    toggleSelectedArePublishedAndUpdate(): void {
        this.checkedRows.forEach(testimonial => {
            this.toggleIsPublishedAndUpdate(testimonial);
        });
    }

    toggleIsPublishedAndUpdate(testimonial: Testimonial): void {
        testimonial.published_at = testimonial.published_at ? null : new Date();
        this.updateTestimonial(testimonial);
    }

    updateTestimonial(testimonial: Testimonial): void {
        console.log('updating testimonial');

        this.axios
            .patch(`/api/admin/testimonials/${testimonial.id}`, testimonial)
            .then(res => res.data)
            .then(res => {
                // this.testimonials = res.data;
                this.loading = false;
            })
            .catch(err => {
                this.loading = false;
                this.$snackbar.open({
                    message: 'Unable to update testimonial, maybe you are offline?',
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
}
</script>
