<template>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Share album to cosplayers</p>
        </header>
        <section class="modal-card-body">
            <b-field label="To">
                <b-taglist>
                    <b-tag rounded v-for="contact in contacts" :key="contact.id">
                        {{ contact.name }} <{{ contact.email }}>
                    </b-tag>
                </b-taglist>
            </b-field>

            <b-field label="Message">
                <b-input type="textarea" v-model="message"></b-input>
            </b-field>
        </section>
        <footer class="modal-card-foot">
            <button class="button" type="button" @click="$parent.close()">Close</button>
            <button class="button is-primary" :disabled="!contacts">Send</button>
        </footer>
    </div>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import Buefy from '../../admin/Buefy.vue';
import { Prop } from 'vue-property-decorator';

@Component({ name: 'share-album-to-cosplayer-modal' })
export default class ShareAlbumToCosplayerModal extends Buefy {
    @Prop()
    protected contacts: Array<object> = [];
    protected message: string;
}
</script>
