<template>
    <div>
        <section>
            <div class="buttons">
                <b-button
                    tag="router-link"
                    :to="{ name: 'admin.users.create' }"
                    type="is-success"
                    icon-left="plus"
                    >Add
                </b-button>
                <b-button
                    type="is-danger"
                    icon-left="trash-alt"
                    :disabled="!checkedRows.length"
                    @click="confirmDeleteSelectedUsers()"
                >
                    Delete checked
                </b-button>
            </div>

            <b-table
                :data="users"
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
                            :to="{ name: 'admin.users.edit', params: { id: user.row.id } }"
                        >
                            {{ user.row.name }}
                        </router-link>
                    </b-table-column>

                    <b-table-column field="email" label="E-mail" sortable>
                        <router-link
                            :to="{ name: 'admin.users.edit', params: { id: user.row.id } }"
                        >
                            {{ user.row.email }}
                        </router-link>
                    </b-table-column>

                    <b-table-column field="status" label="Role" centered>
                        <span class="tag is-dark" v-bind:title="'User role'">{{
                            user.row.role
                        }}</span>
                    </b-table-column>

                    <!--                    <b-table-column field="actions.impersonate" label="Impersonate" centered>-->
                    <!--                        <a :href="user.row.actions.impersonate">-->
                    <!--                            <span class="icon has-text-info">-->
                    <!--                                <i class="fas fa-sign-in-alt"></i>-->
                    <!--                            </span>-->
                    <!--                        </a>-->
                    <!--                    </b-table-column>-->
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
import User from '../../models/user';

@Component({
    name: 'UsersIndex',
})
export default class UsersIndex extends VueBuefy {
    private users: Array<User> = [];
    private checkedRows: Array<User> = [];
    private total: number = 0;
    private page: number = 1;
    perPage: number = 10;
    private loading: boolean = false;
    private sortField: string = 'id';
    private sortOrder: string = 'desc';
    showDetailIcon: boolean = true;
    defaultSortOrder: string = 'desc';

    created(): void {
        this.fetchUsers();
    }

    fetchUsers(): void {
        this.loading = true;
        const sortOrder = this.sortOrder === 'asc' ? '' : '-';

        this.axios
            .get('/api/admin/users', {
                params: {
                    page: this.page,
                    sort: sortOrder + this.sortField,
                },
            })
            .then(res => res.data)
            .then(res => {
                this.perPage = res.meta.per_page;
                this.total = res.meta.total;
                this.users = res.data;
                this.loading = false;
            })
            .catch(err => {
                this.users = [];
                this.total = 0;
                this.loading = false;
                this.$buefy.snackbar.open({
                    message: 'Unable to load users, maybe you are offline?',
                    type: 'is-danger',
                    position: 'is-top',
                    actionText: 'Retry',
                    indefinite: true,
                    onAction: () => {
                        this.fetchUsers();
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
        this.fetchUsers();
    }

    /*
     * Handle sort event
     */
    onSort(field: string, order: string): void {
        this.sortField = field;
        this.sortOrder = order;
        this.fetchUsers();
    }

    confirmDeleteSelectedUsers(): void {
        this.$buefy.dialog.confirm({
            title: 'Deleting Users',
            message:
                'Are you sure you want to <b>delete</b> these users? This action cannot be undone.',
            confirmText: 'Delete Users',
            type: 'is-danger',
            hasIcon: true,
            onConfirm: () => {
                this.deleteSelectedUsers();
            },
        });
    }

    /**
     * Delete user from slug
     */
    deleteSelectedUsers(): void {
        this.checkedRows.forEach(user => {
            this.axios
                .delete(`/api/admin/users/${user.id}`)
                .then(res => {
                    this.showSuccess('Users deleted');
                    this.fetchUsers();
                })
                .catch(err => {
                    this.showError(`Unable to delete user <br> <small>${err.message}</small>`);
                    throw err;
                });
        });
    }
}
</script>
