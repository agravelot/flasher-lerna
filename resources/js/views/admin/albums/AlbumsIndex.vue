<template>
  <div>
    <section>
      <div class="level">
        <div class="level-left">
          <div class="level-item">
            <div class="buttons">
              <b-button
                :to="{ name: 'admin.albums.create' }"
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
                @click="confirmDeleteSelectedAlbums"
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
              @input="fetchAlbums()"
            />
          </b-field>
        </div>
      </div>

      <b-table
        :data="albums"
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
        <template slot-scope="album">
          <b-table-column
            field="title"
            label="Title"
            sortable
          >
            <router-link
              :to="{ name: 'admin.albums.edit', params: { slug: album.row.slug } }"
            >
              {{ album.row.title }}
            </router-link>
          </b-table-column>

          <b-table-column
            field="media_count"
            label="Nb. photos"
            centered
            numeric
          >
            {{ album.row.media_count }}
          </b-table-column>

          <b-table-column
            field="status"
            label="Status"
            centered
          >
            <span
              v-if="album.row.private === 1"
              :title="'This album is private'"
              class="tag is-danger"
            >
              {{ 'Private' }}
            </span>
            <span
              v-else-if="typeof album.row.published_at === 'string'"
              :title="new Date(album.row.published_at).toLocaleDateString()"
              class="tag is-success"
            >
              {{ 'Published' }}
            </span>
            <span
              v-else
              :title="'This album is a draft'"
              class="tag is-dark"
            >{{
              'Draft'
            }}</span>
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
import Album from '../../../models/album';
import {showError, showSuccess} from "../../../admin/toast";

@Component({
    name: 'AlbumsIndex',
    filters: {
        /**
         * Filter to truncate string, accepts a length parameter
         */
        truncate(value: string, length: number): string {
            return value.length > length ? value.substr(0, length) + '...' : value;
        },
    },
})
export default class AlbumsIndex extends Buefy {
    private albums: Array<Album> = [];
    private checkedRows: Array<Album> = [];
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
        this.fetchAlbums();
    }

    fetchAlbums(): void {
        this.loading = true;
        const sortOrder = this.sortOrder === 'asc' ? '' : '-';

        this.axios
            .get('/api/admin/albums', {
                params: {
                    page: this.page,
                    sort: sortOrder + this.sortField,
                    'filter[title]': this.search,
                },
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
                showError(this.$buefy,'Unable to load albums, maybe you are offline?', this.fetchAlbums);
                console.error(err);
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
        this.fetchAlbums();
    }

    /*
     * Handle sort event
     */
    onSort(field: string, order: string): void {
        this.sortField = field;
        this.sortOrder = order;
        this.fetchAlbums();
    }

    confirmDeleteSelectedAlbums(): void {
        this.$buefy.dialog.confirm({
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
    }

    /**
     * Delete album from slug
     */
    async deleteSelectedAlbums(): Promise<void> {
        const deleteAlbum = async (album: Album): Promise<void> => {
            try {
                await this.axios.delete(`/api/admin/albums/${album.slug}`);
                showSuccess(this.$buefy, 'Albums deleted');
                this.checkedRows = this.checkedRows.filter(selectedAlbum => {
                    return selectedAlbum.id != album.id;
                });
                this.albums = this.albums.filter(albumItem => {
                    return albumItem.id != album.id;
                });
            } catch (exception) {
                showError(this.$buefy, `Unable to delete album <br> <small>${exception.message}</small>`);
                console.error(exception);
            }
        };

        await this.checkedRows.forEach((album) => deleteAlbum(album));
        await this.fetchAlbums();
    }
}
</script>
