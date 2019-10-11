<template>
    <div>
        <b-table
            :data="album.cosplayers"
            :loading="album === null"
            striped
            hoverable
            mobile-cards
            checkable
            :checked-rows.sync="checkedRows"
        >
            <template slot-scope="cosplayer">
                <b-table-column field="name" label="Name">
                    {{ cosplayer.row.name }}
                </b-table-column>
                <b-table-column field="email" label="Email">
                    <div v-if="cosplayer.row.user">
                        {{ cosplayer.row.user.email }}
                    </div>
                    <div v-else>
                        <b-field>
                            <b-input
                                placeholder="Email"
                                type="email"
                                maxlength="50"
                                icon="mail"
                            ></b-input>
                        </b-field>
                    </div>
                </b-table-column>
            </template>

            <template slot="empty">
                <section class="section">
                    <div class="content has-text-grey has-text-centered">
                        <p>
                            <b-icon icon="sad-tear" size="is-large"></b-icon>
                        </p>
                        <p>No cosplayers.</p>
                    </div>
                </section>
            </template>

            <template slot="bottom-left">
                <b-button type="is-success" :disabled="!checkedRows.length" @click="showModal()"
                    >Next</b-button
                >
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

@Component({
    name: 'share-album-to-cosplayer',
})
export default class ShareAlbumToCosplayer extends Buefy {
    @Prop()
    protected album: Album;
    private checkedRows: Array<any> = [];
    private checkboxPosition: Array<any>;

    showModal(): void {
        this.$buefy.modal.open({
            parent: this,
            component: ShareAlbumToCosplayerModal,
            hasModalCard: true,
            props: {
                contacts: this.getSelectedContacts(),
            },
        });
    }

    getSelectedContacts(): Array<object> {
        return this.checkedRows.map((cosplayer: Cosplayer) => {
            return { id: cosplayer.id, name: cosplayer.name, email: cosplayer.user && cosplayer.user.email || '' };
        });
    }
}
</script>
