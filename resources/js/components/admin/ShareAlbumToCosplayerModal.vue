<template>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">
        Share album to cosplayers
      </p>
    </header>
    <section class="modal-card-body">
      <b-field label="To">
        <b-taglist>
          <b-tag
            v-for="contact in contacts"
            :key="contact.id"
            rounded
          >
            {{ contact.name }} <{{ contact.email }}>
          </b-tag>
        </b-taglist>
      </b-field>

      <b-field label="Message">
        <b-input
          v-model="message"
          type="textarea"
        />
      </b-field>
    </section>
    <footer class="modal-card-foot">
      <button
        @click="$parent.close()"
        class="button"
        type="button"
      >
        Close
      </button>
      <button
        :disabled="!contacts"
        class="button is-primary"
      >
        Send
      </button>
    </footer>
  </div>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import Buefy from '../../admin/Buefy.vue';
import { Prop } from 'vue-property-decorator';
import Sharer from '../../models/sharer';

@Component({ name: 'share-album-to-cosplayer-modal' })
export default class ShareAlbumToCosplayerModal extends Buefy {
    @Prop()
    protected contacts: Array<Sharer> = [];
    protected message = '';
}
</script>
