<template>
    <div>
        <b-table
            :data="album.cosplayers"
            :columns="columns"
            :checked-rows.sync="checkedRows"
            checkable
            :checkbox-position="checkboxPosition"
        >
            <template slot="bottom-left">
                <b>Total checked</b>: {{ checkedRows.length }}
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
    album: Album;
    private columns: Array<object> = [
        {
            field: 'name',
            label: 'Name',
        },
        {
            field: 'user.email',
            label: 'Email',
        },
    ];
    private checkedRows: Array<any> = [];
    private checkboxPosition: Array<any>;
}
</script>
