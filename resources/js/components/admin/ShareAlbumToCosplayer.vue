<template>
  <div>
    <b-table
      :data="album.cosplayers"
      :loading="album === null"
      :checked-rows.sync="checkedRows"
      striped
      hoverable
      mobile-cards
      checkable
    >
      <template slot-scope="cosplayer">
        <b-table-column
          field="name"
          label="Name"
        >
          {{ cosplayer.row.name }}
        </b-table-column>
        <b-table-column
          field="email"
          label="Email"
        >
          <div v-if="cosplayer.row.user">
            {{ cosplayer.row.user.email }}
          </div>
          <div v-else>
            <b-field>
              <b-input
                v-model="customEmails[cosplayer.row.id]"
                placeholder="Email"
                type="email"
                maxlength="50"
                icon="mail"
              />
            </b-field>
          </div>
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
            <p>No cosplayers.</p>
          </div>
        </section>
      </template>

      <template slot="bottom-left">
        <b-button
          :disabled="!checkedRows.length"
          @click="showModal()"
          type="is-success"
        >
          Next
        </b-button>
        <span>
          <b>Total checked</b>
          : {{ checkedRows.length }}
        </span>
      </template>
    </b-table>
  </div>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import Buefy from '../../admin/Buefy.vue';
import Album from '../../models/album';
import { Prop } from 'vue-property-decorator';
import ShareAlbumToCosplayerModal from './ShareAlbumToCosplayerModal.vue';
import Cosplayer from '../../models/cosplayer';
import CosplayerContact from '../../models/sharer';
import User from '../../models/user';

@Component({
    name: 'share-album-to-cosplayer',
})
export default class ShareAlbumToCosplayer extends Buefy {
    @Prop()
    protected album: Album;
    private checkedRows: Array<any> = [];
    private checkboxPosition: Array<any>;
    private customEmails: Array<string> = [];

    showModal(): void {
        this.$buefy.modal.open({
            parent: this,
            component: ShareAlbumToCosplayerModal,
            hasModalCard: true,
            props: {
                contacts: this.getSelectedRows(),
            },
        });
    }

    private getSelectedRows(): Array<CosplayerContact> {
        return this.checkedRows.map((cosplayer: Cosplayer) => {
            console.log(cosplayer.id);
            console.log(customElements[cosplayer.id]);
            return new CosplayerContact(
                cosplayer.id,
                cosplayer.name,
                (cosplayer.user && cosplayer.user.email) || this.customEmails[cosplayer.id]
            );
        });
    }
}
</script>
