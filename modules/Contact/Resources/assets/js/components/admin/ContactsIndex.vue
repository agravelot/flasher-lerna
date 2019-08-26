<template>
    <div>
        <section>
            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <div class="buttons">
                            <b-button
                                type="is-danger"
                                icon-left="trash-alt"
                                :disabled="!checkedRows.length"
                                @click="confirmDeleteSelectedContacts"
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
                            @input="fetchContacts()"
                        >
                        </b-input>
                    </b-field>
                </div>
            </div>

            <b-table
                :data="contacts"
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
                :opened-detailed="defaultOpenedDetails"
                detailed
                detail-key="id"
                @details-open="(row, index) => $buefy.toast.open(`Expanded ${row.user.first_name}`)"
                :show-detail-icon="showDetailIcon"
                :checked-rows.sync="checkedRows"
            >
                <template slot-scope="contact">
                    <b-table-column field="name" label="Name" sortable>
                        {{ contact.row.name }}
                    </b-table-column>

                    <b-table-column field="email" label="Email" sortable>
                        <a :href="`mailto:${contact.row.email}`" target="_blank">{{
                            contact.row.email
                        }}</a>
                    </b-table-column>
                </template>

                <template slot="detail" slot-scope="props">
                    <article>
                        <p>
                            {{ props.row.message }}
                        </p>
                    </article>
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
import VueBuefy from '../../../../../../../resources/js/admin/Buefy.vue';
import Contact from '../../contact';

@Component({
    name: 'ContactsIndex',
    filters: {
        /**
         * Filter to truncate string, accepts a length parameter
         */
        truncate(value: string, length: number): string {
            return value.length > length ? value.substr(0, length) + '...' : value;
        },
    },
})
export default class ContactsIndex extends VueBuefy {
    private contacts: Array<Contact> = [];
    private checkedRows: Array<Contact> = [];
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
        this.fetchContacts();
    }

    fetchContacts(): void {
        this.loading = true;
        const sortOrder = this.sortOrder === 'asc' ? '' : '-';

        this.axios
            .get('/api/admin/contacts', {
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
                this.contacts = res.data;
                this.loading = false;
            })
            .catch(err => {
                this.contacts = [];
                this.total = 0;
                this.loading = false;
                this.$buefy.snackbar.open({
                    message: 'Unable to load contacts, maybe you are offline?',
                    type: 'is-danger',
                    position: 'is-top',
                    actionText: 'Retry',
                    indefinite: true,
                    onAction: () => {
                        this.fetchContacts();
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
        this.fetchContacts();
    }

    /*
     * Handle sort event
     */
    onSort(field: string, order: string): void {
        this.sortField = field;
        this.sortOrder = order;
        this.fetchContacts();
    }

    confirmDeleteSelectedContacts(): void {
        this.$buefy.dialog.confirm({
            title: 'Deleting Contacts',
            message:
                'Are you sure you want to <b>delete</b> these contacts? This action cannot be undone.',
            confirmText: 'Delete Contacts',
            type: 'is-danger',
            hasIcon: true,
            onConfirm: () => {
                this.deleteSelectedContacts();
            },
        });
    }

    /**
     * Delete contact from slug
     */
    deleteSelectedContacts(): void {
        this.checkedRows.forEach(contact => {
            this.axios
                .delete(`/api/admin/contacts/${contact.id}`)
                .then(res => {
                    this.showSuccess('Contacts deleted');
                    this.fetchContacts();
                })
                .catch(err => {
                    this.showError(`Unable to delete contact <br> <small>${err.message}</small>`);
                    throw err;
                });
        });
    }
}
</script>
