<template>
  <b-field label="Select user">
    <b-autocomplete
      :data="filteredUsers"
      :loading="loading"
      placeholder="User name"
      field="name"
      @typing="getFilteredUsers"
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
    import User from "../../models/user";
    import {showError} from "../../admin/toast";

    @Component({
        name: 'pick-one-user'
    })
    export default class PickOneUser extends Buefy {

        @Prop()
        private userId?: number;
        @Prop()
        private user?: User;
        private filteredUsers: User[] = [];
        private loading = false;

        selected(option: User): void {
            //this.user = option;
            this.$emit('update:user', option);
            //this.userId = option.id;
            this.$emit('update:userId', option.id);
        }

        getFilteredUsers(text: string): void {
            this.loading = true;
            this.axios
                .get('/api/admin/users', {
                    params: {
                        'filter[name]': text,
                    },
                })
                .then(res => res.data)
                .then(res => {
                    this.filteredUsers = res.data;
                    this.loading = false;
                })
                .catch(err => {
                    // this.filteredUsers = [];
                    this.loading = false;
                    showError(this.$buefy, 'Unable to load users, maybe you are offline?', () => this.getFilteredUsers(text));
                    throw err;
                });
        }
    }
</script>

