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
          @click="createUser()"
        >
          Create
        </b-button>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import Buefy from '../../../admin/Buefy.vue';
import User from '../../../models/user';
import {showError, showSuccess} from "../../../admin/toast";

@Component({
    name: 'UsersCreate',
})
export default class UsersCreate extends Buefy {
    private user: User = new User();
    private loading = false;
    protected errors: object = {};

    createUser(): void {
        this.loading = true;

        this.axios
            .post('/api/admin/users', this.user)
            .then(res => res.data)
            .then(() => {
                this.errors = {};
                this.loading = false;
                this.$router.push({ name: 'admin.users.index' });
                showSuccess(this.$buefy,'User created');
            })
            .catch(err => {
                this.loading = false;
                showError(this.$buefy,'Unable to create user, maybe you are offline?', this.createUser);
                this.errors = err.response.data.errors;
                throw err;
            });
    }

    confirmDeleteUser(): void {
        this.$buefy.dialog.confirm({
            title: 'Deleting Albums',
            message:
                'Are you sure you want to <b>delete</b> these users? This action cannot be undone.',
            confirmText: 'Delete Albums',
            type: 'is-danger',
            hasIcon: true,
            onConfirm: () => {
                this.deleteUser();
            },
        });
    }

    /**
     * Delete user from slug
     */
    deleteUser(): void {
        this.axios
            .delete(`/api/admin/users/${this.user.id}`)
            .then(() => {
                this.$router.push({ name: 'admin.users.index' });
                showSuccess(this.$buefy,'User deleted');
            })
            .catch(err => {
                showError(this.$buefy,`Unable to delete user <br> <small>${err.message}</small>`);
                throw err;
            });
    }
}
</script>
