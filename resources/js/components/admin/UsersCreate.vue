<template>
    <div class="card">
        <div class="card-content">
            <b-field
                label="Name"
                :type="errors.name ? 'is-danger' : ''"
                :message="errors.name ? errors.name[0] : null"
            >
                <b-input v-model="user.name"></b-input>
            </b-field>

            <b-field
                label="Email"
                :type="errors.email ? 'is-danger' : ''"
                :message="errors.email ? errors.email[0] : null"
            >
                <b-input type="email" maxlength="30" v-model="user.email"></b-input>
            </b-field>

            <b-field label="Role">
                <b-select placeholder="Select a role" v-model="user.role" required>
                    <option value="admin">Administrator</option>
                    <option value="user">User</option>
                </b-select>
            </b-field>

            <b-field
                label="Password"
                :type="errors.password ? 'is-danger' : ''"
                :message="errors.password ? errors.password[0] : null"
            >
                <b-input v-model="user.password" type="password" password-reveal></b-input>
            </b-field>

            <b-field
                label="Password confirmation"
                :type="errors.password_confirmation ? 'is-danger' : ''"
                :message="errors.password_confirmation ? errors.password_confirmation[0] : null"
            >
                <b-input
                    v-model="user.password_confirmation"
                    type="password"
                    password-reveal
                ></b-input>
            </b-field>

            <div class="buttons">
                <b-button type="is-primary" :loading="this.loading" @click="createUser()">
                    Create
                </b-button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import VueBuefy from '../../admin/Buefy.vue';
import User from '../../models/user';

@Component({
    name: 'UsersCreate',
})
export default class UsersCreate extends VueBuefy {
    private user: User = new User();
    private loading: boolean = false;
    protected errors: object = {};

    createUser(): void {
        this.loading = true;

        this.axios
            .post('/api/admin/users', this.user)
            .then(res => res.data)
            .then(res => {
                this.errors = {};
                this.loading = false;
                this.$router.push({ name: 'admin.users.index' });
                this.showSuccess('User created');
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
            .then(res => {
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
