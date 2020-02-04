<template>
  <b-field
    label="Select cosplayer"
    :type="errors.cosplayer_id ? 'is-danger' : ''"
    :message="errors.cosplayer_id ? errors.cosplayer_id[0] : null"
  >
    <b-autocomplete
      :data="filteredCosplayers"
      :loading="loading"
      placeholder="Cosplayer name"
      field="name"
      @typing="getFilteredCosplayers"
      @select="selected"
    >
      <template slot-scope="props">
        {{ props.option.name }}
      </template>
    </b-autocomplete>
  </b-field>
</template>

<script lang="ts">
    import {Component, Prop} from "vue-property-decorator";
    import Buefy from "../../admin/Buefy.vue";
    import Cosplayer from "../../models/cosplayer";
    import {showError} from "../../admin/toast";

    @Component({
        name: 'pick-one-cosplayer'
    })
    export default class PickOneCosplayer extends Buefy {

        @Prop()
        private cosplayerId?: number;
        @Prop()
        private cosplayer?: Cosplayer;
        @Prop({default: () => ({}), required: false})
        private errors?: object;

        private filteredCosplayers: Cosplayer[] = [];
        private loading = false;

        selected(option: Cosplayer): void {
            //this.cosplayer = option;
            this.$emit('update:cosplayer', option);
            //this.cosplayerId = option.id;
            this.$emit('update:cosplayerId', option.id);
        }

        async getFilteredCosplayers(text: string): Promise<void> {
            this.loading = true;
            try {
                const res = await this.axios.get('/api/admin/cosplayers', {
                    params: {
                        'filter[name]': text,
                    },
                });
                const { data } = res.data;
                console.log(data);
                this.filteredCosplayers = data;
                this.loading = false;
            } catch (exception) {
                this.loading = false;
                showError(this.$buefy, 'Unable to load cosplayers, maybe you are offline?', () => this.getFilteredCosplayers(text));
                throw exception;
            }
        }
    }
</script>

