<template>
  <div>
    <section>
      <div class="level">
        <div class="level-left">
          <div class="level-item">
            <div class="buttons">
              <b-button
                :to="{ name: 'admin.cosplayers.create' }"
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
                @click="confirmDeleteSelectedCosplayers"
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
              @input="fetchCosplayers()"
            />
          </b-field>
        </div>
      </div>

      <b-table
        :data="cosplayers"
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
        icon-pack="fas"
        @page-change="onPageChange"
        checkable
        @sort="onSort"
      >
        <template slot-scope="cosplayer">
          <b-table-column
            field="name"
            label="Name"
            sortable
          >
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
import Cosplayer from '../../../models/cosplayer';

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
export default class CosplayersIndex extends Buefy {
    private cosplayers: Array<Cosplayer> = [];
    private checkedRows: Array<Cosplayer> = [];
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
                this.$buefy.snackbar.open({
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
        this.$buefy.dialog.confirm({
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
                .then(() => {
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
