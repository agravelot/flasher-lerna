<template>
  <section>
    <h1 class="title">
      Create invitation
    </h1>

    <div class="card">
      <div class="card-content">
        <form @submit.prevent="createInvitation">
          <b-field
            :type="errors.email ? 'is-danger' : ''"
            :message="errors.email ? errors.email[0] : null"
            label="Email"
          >
            <b-input
              v-model="invitation.email"
              type="email"
            />
          </b-field>

          <b-field
            :type="errors.message ? 'is-danger' : ''"
            :message="errors.message ? errors.message[0] : null"
            label="Message"
          >
            <b-input
              v-model="invitation.message"
              type="textarea"
            />
          </b-field>

          <pick-one-cosplayer :cosplayer-id.sync="invitation.cosplayer_id" />

          <button class="button is-primary">
            Send invitation
          </button>
        </form>
      </div>
    </div>
  </section>
</template>


<script lang="ts">
    import { Component } from "vue-property-decorator";
    import Buefy from "../../../admin/Buefy.vue";
    import Invitation from "../../../models/invitation";
    import Cosplayer from "../../../models/cosplayer";
    import PickOneCosplayer from "../../../components/admin/PickOneCosplayer.vue";

    @Component({
        components: {
            PickOneCosplayer
        }
    })
    export default class InvitationsCreate extends Buefy {
        private invitation: Invitation = new Invitation();
        protected errors: object = {};
        private loading = false;
        private filteredCosplayers: Cosplayer[] = [];

        createInvitation(): void {
            this.axios
                .post(`/api/admin/invitations/`, this.invitation)

                .then(res => res.data)
                .then(res => {
                    this.showSuccess('Invitation successfully created');
                    this.$router.push({name: 'admin.invitations.index'});
                })
                .catch(err => {
                    this.showError(
                        `Unable to create the invitation <br><small>${err.response.data.message}</small>`
                    );
                    this.errors = err.response.data.errors;
                });
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
                    this.$buefy.snackbar.open({
                        message: 'Unable to load cosplayers, maybe you are offline?',
                        type: 'is-danger',
                        position: 'is-top',
                        actionText: 'Retry',
                        indefinite: true,
                        onAction: () => {
                            this.getFilteredCosplayers(text);
                        },
                    });
                    throw err;
                });
        }
    }
</script>
