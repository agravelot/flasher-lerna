<template>
  <b-field label="Select cosplayer">
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
        private filteredCosplayers: Cosplayer[] = [];
        private loading = false;

        selected(option: Cosplayer): void {
            //this.cosplayer = option;
            this.$emit('update:cosplayer', option);
            //this.cosplayerId = option.id;
            this.$emit('update:cosplayerId', option.id);
        }

        getFilteredCosplayers(text: string): void {
            this.loading = true;
            this.axios
                .get('/api/admin/cosplayers', {
                    params: {
                        'filter[name]': text,
                    },
                })
                .then(res => res.data)
                .then(res => {
                    this.filteredCosplayers = res.data;
                    this.loading = false;
                })
                .catch(err => {
                    // this.filteredCosplayers = [];
                    this.loading = false;
                    showError(this.$buefy, 'Unable to load cosplayers, maybe you are offline?', () => this.getFilteredCosplayers(text));
                    throw err;
                });
        }
    }
</script>

