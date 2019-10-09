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
                    {{ cosplayer.row.user && cosplayer.row.user.email }}
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
                <b>Total checked</b>
                : {{ checkedRows.length }}
            </template>
        </b-table>
    </div>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import Buefy from '../../admin/Buefy.vue';
import Album from '../../models/album';
import { Prop } from 'vue-property-decorator';

@Component({
    name: 'share-album-to-cosplayer',
})
export default class ShareAlbumToCosplayer extends Buefy {
    @Prop()
    protected album: Album;
    private checkedRows: Array<any> = [];
    private checkboxPosition: Array<any>;
}
</script>
