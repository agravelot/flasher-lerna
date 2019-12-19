<template>
  <div class="card">
    <div class="card-content">
      <b-field
        :type="errors.name ? 'is-danger' : ''"
        :message="errors.name ? errors.name[0] : null"
        label="Name"
      >
        <b-input v-model="user.name" />
      </b-field>

      <b-field
        :type="errors.email ? 'is-danger' : ''"
        :message="errors.email ? errors.email[0] : null"
        label="Email"
      >
        <b-input
          v-model="user.email"
          type="email"
          maxlength="30"
        />
      </b-field>

      <b-field label="Role">
        <b-select
          v-model="user.role"
          placeholder="Select a role"
          required
        >
          <option value="admin">
            Administrator
          </option>
          <option value="user">
            User
          </option>
        </b-select>
      </b-field>

      <b-field
        :type="errors.password ? 'is-danger' : ''"
        :message="errors.password ? errors.password[0] : null"
        label="Password"
      >
        <b-input
          v-model="user.password"
          type="password"
          password-reveal
        />
      </b-field>

      <b-field
        :type="errors.password_confirmation ? 'is-danger' : ''"
        :message="errors.password_confirmation ? errors.password_confirmation[0] : null"
        label="Password confirmation"
      >
        <b-input
          v-model="user.password_confirmation"
          type="password"
          password-reveal
        />
      </b-field>

      <div class="buttons">
        <b-button
          :loading="loading"
          type="is-primary"
          @click="updateUser()"
        >
          Update
        </b-button>
        <b-button
          v-if="user && user.actions && user.actions.impersonate"
          :href="user.actions.impersonate"
          :loading="loading"
          type="is-info"
          icon-right="sign-in-alt"
          tag="a"
        >
          Impersonate
        </b-button>
        <b-button
          :loading="loading"
          type="is-danger"
          icon-right="trash-alt"
          @click="confirmDeleteUser()"
        >
          Delete
        </b-button>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import Buefy from '../../../admin/Buefy.vue';
import User from '../../../models/user';

@Component({
  name: 'UsersEdit'
})
export default class UsersEdit extends Buefy {
    private user: User|null = null;
    private loading = false;
    protected errors: object = {};

    created (): void {
      this.fetchUser();
    }

    updateUser (): void {
      this.loading = true;

      this.axios
        .patch(`/api/admin/users/${this.$route.params.id}`, this.user)
        .then(res => res.data)
        .then(() => {
          this.errors = {};
          this.loading = false;
          this.$router.push({ name: 'admin.users.index' });
          this.showSuccess('User updated');
        })
        .catch(err => {
          this.loading = false;
          // this.$buefy.snackbar.open({
          //     message: 'Unable to update user, maybe you are offline?',
          //     type: 'is-danger',
          //     position: 'is-top',
          //     actionText: 'Retry',
          //     //indefinite: true,
          //     onAction: () => {
          //         this.updateUser();
          //     },
          // });
          this.errors = err.response.data.errors;
          throw err;
        });
    }

    fetchUser (): void {
      this.loading = true;

      this.axios
        .get(`/api/admin/users/${this.$route.params.id}`)
        .then(res => res.data)
        .then(res => {
          this.user = res.data;
          this.loading = false;
        })
        .catch(err => {
          this.loading = false;
          this.$buefy.snackbar.open({
            message: 'Unable to load user, maybe you are offline?',
            type: 'is-danger',
            position: 'is-top',
            actionText: 'Retry',
            indefinite: true,
            onAction: () => {
              this.fetchUser();
            }
          });
          throw err;
        });
    }

    confirmDeleteUser (): void {
      this.$buefy.dialog.confirm({
        title: 'Deleting Albums',
        message:
                'Are you sure you want to <b>delete</b> these users? This action cannot be undone.',
        confirmText: 'Delete Albums',
        type: 'is-danger',
        hasIcon: true,
        onConfirm: () => {
          this.deleteUser();
        }
      });
    }

    /**
     * Delete user from slug
     */
    deleteUser (): void {
      if (this.user === null) {
        throw new DOMException('Unable to delete album from undefined.');
      }
      this.axios
        .delete(`/api/admin/users/${this.user.id}`)
        .then(() => {
          this.$router.push({ name: 'admin.users.index' });
          this.showSuccess('User deleted');
        })
        .catch(err => {
          this.showError(`Unable to delete user <br> <small>${err.message}</small>`);
          throw err;
        });
    }
}
</script>
