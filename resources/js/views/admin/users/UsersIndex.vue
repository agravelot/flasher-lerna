<template>
  <div>
    <section>
      <div class="buttons">
        <b-button
          :to="{ name: 'admin.users.create' }"
          tag="router-link"
          type="is-success"
          icon-left="plus"
        >
          Add
        </b-button>
        <b-button
          :disabled="!checkedRows.length"
          type="is-danger"
          icon-left="trash-alt"
          @click="confirmDeleteSelectedUsers()"
        >
          Delete checked
        </b-button>
      </div>

      <b-table
        :data="users"
        :loading="loading"
        :total="total"
        :per-page="perPage"
        :default-sort-direction="defaultSortOrder"
        :default-sort="[sortField, sortOrder]"
        :checked-rows.sync="checkedRows"
        striped
        hoverable
        mobile-cards
        paginated
        backend-pagination
        backend-sorting
        checkable
        @page-change="onPageChange"
        @sort="onSort"
      >
        <template slot-scope="user">
          <b-table-column
field="name"
label="Name" sortable>
            <router-link
              :to="{
                name: 'admin.users.edit',
                params: { id: user.row.id }
              }"
            >
              {{ user.row.name }}
            </router-link>
          </b-table-column>

          <b-table-column
field="email"
label="E-mail" sortable>
            <router-link
              :to="{
                name: 'admin.users.edit',
                params: { id: user.row.id }
              }"
            >
              {{ user.row.email }}
            </router-link>
          </b-table-column>

          <b-table-column
field="status"
label="Role" centered>
            <span
:title="'User role'"
class="tag is-dark">{{
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
                <b-icon
icon="sad-tear"
size="is-large" />
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
import Component from "vue-class-component";
import Buefy from "../../../admin/Buefy.vue";
import User from "../../../models/user";
import { showError, showSuccess } from "../../../admin/toast";

@Component({
    name: "UsersIndex"
})
export default class UsersIndex extends Buefy {
    private users: Array<User> = [];
    private checkedRows: Array<User> = [];
    private total = 0;
    private page = 1;
    perPage = 10;
    private loading = false;
    private sortField = "id";
    private sortOrder = "desc";
    showDetailIcon = true;
    defaultSortOrder = "desc";

    created(): void {
        this.fetchUsers();
    }

    fetchUsers(): void {
        this.loading = true;
        const sortOrder = this.sortOrder === "asc" ? "" : "-";

        this.axios
            .get("/api/admin/users", {
                params: {
                    page: this.page,
                    sort: sortOrder + this.sortField
                }
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
                showError(
                    this.$buefy,
                    "Unable to load users, maybe you are offline?",
                    this.fetchUsers
                );
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
            title: "Deleting Users",
            message:
                "Are you sure you want to <b>delete</b> these users? This action cannot be undone.",
            confirmText: "Delete Users",
            type: "is-danger",
            hasIcon: true,
            onConfirm: () => {
                this.deleteSelectedUsers();
            }
        });
    }

    /**
     * Delete user from slug
     */
    deleteSelectedUsers(): void {
        this.checkedRows.forEach(user => {
            this.axios
                .delete(`/api/admin/users/${user.id}`)
                .then(() => {
                    showSuccess(this.$buefy, "Users deleted");
                    this.fetchUsers();
                })
                .catch(err => {
                    showError(
                        this.$buefy,
                        `Unable to delete user <br> <small>${err.message}</small>`
                    );
                    throw err;
                });
        });
    }
}
</script>
