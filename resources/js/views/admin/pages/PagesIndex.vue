<template>
  <div>
    <section>
      <div class="level">
        <div class="level-left">
          <div class="level-item">
            <div class="buttons">
              <b-button
                :to="{ name: 'admin.pages.create' }"
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
                @click="confirmDeleteSelectedPages()"
              >
                Delete checked
              </b-button>
            </div>
          </div>
        </div>

        <div class="level-right">
          <b-field class="is-pulled-right">
            <b-input
              v-model="search"
              :loading="loading"
              placeholder="Search..."
              type="search"
              icon="search"
              @input="fetchPages()"
            />
          </b-field>
        </div>
      </div>

      <b-table
        :data="pages"
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
        <template slot-scope="page">
          <b-table-column
            field="name"
            label="Name"
            sortable
          >
            <router-link
              :to="{ name: 'admin.pages.edit', params: { slug: page.row.slug } }"
            >
              {{ page.row.name }}
            </router-link>
          </b-table-column>
        </template>

        <template slot="empty">
          <section class="section">
            <div class="content has-text-grey has-text-centered">
              <p>
                <b-icon
                  icon="sad-tear"
                  size="is-large"
                />
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
import Buefy from '../../../admin/Buefy.vue';
import Page from '../../../models/page';

@Component({
    name: 'Core.Resources.assets.js.components.pages.PagesIndex',
    filters: {
        /**
         * Filter to truncate string, accepts a length parameter
         */
        truncate(value: string, length: number): string {
            return value.length > length ? value.substr(0, length) + '...' : value;
        },
    },
})
export default class PagesIndex extends Buefy {
    private pages: Array<Page> = [];
    //TODO Clearer types
    defaultOpenedDetails: Array<any> = [];
    private checkedRows: Array<any> = [];
    private total = 0;
    private page = 1;
    perPage = 10;
    private loading = false;
    private sortField = 'id';
    private sortOrder = 'desc';
    showDetailIcon = true;
    defaultSortOrder = 'desc';
    private search = '';

    created(): void {
        this.fetchPages();
    }

    fetchPages(): void {
        this.loading = true;
        const sortOrder = this.sortOrder === 'asc' ? '' : '-';

        this.axios
            .get('/api/admin/pages', {
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
                this.pages = res.data;
                this.loading = false;
            })
            .catch(err => {
                this.pages = [];
                this.total = 0;
                this.loading = false;
                this.$buefy.snackbar.open({
                    message: 'Unable to load pages, maybe you are offline?',
                    type: 'is-danger',
                    position: 'is-top',
                    actionText: 'Retry',
                    indefinite: true,
                    onAction: () => {
                        this.fetchPages();
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
        this.fetchPages();
    }

    /*
     * Handle sort event
     */
    onSort(field: string, order: string): void {
        this.sortField = field;
        this.sortOrder = order;
        this.fetchPages();
    }

    confirmDeleteSelectedPages(): void {
        this.$buefy.dialog.confirm({
            title: 'Deleting Pages',
            message:
                'Are you sure you want to <b>delete</b> these pages? This action cannot be undone.',
            confirmText: 'Delete Pages',
            type: 'is-danger',
            hasIcon: true,
            onConfirm: () => {
                this.deleteSelectedPages();
            },
        });
    }

    /**
     * Delete page from slug
     */
    deleteSelectedPages(): void {
        this.checkedRows.forEach(page => {
            this.axios
                .delete(`/api/admin/pages/${page.id}`)
                .then(() => {
                    this.showSuccess('Pages deleted');
                    this.fetchPages();
                })
                .catch(err => {
                    this.showError(`Unable to delete page <br> <small>${err.message}</small>`);
                    throw err;
                });
        });
    }
}
</script>
