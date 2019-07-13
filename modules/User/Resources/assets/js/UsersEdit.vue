<template>
    <section>
        <b-field label="Name">
            <b-input v-model="this.user.name"></b-input>
        </b-field>

        <b-field label="Email">
            <b-input type="email" maxlength="30" v-model="this.user.email"></b-input>
        </b-field>

        <b-field label="Password">
            <b-input v-model="this.user.password" type="password" password-reveal> </b-input>
        </b-field>

        <b-field label="Password confirmation">
            <b-input v-model="this.user.password_confirmation" type="password" password-reveal>
            </b-input>
        </b-field>

        <b-button type="is-primary" :loading="this.loading" @click="updateUser()">Update</b-button>
    </section>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import VueBuefy from '../../../../../resources/js/admin/Buefy.vue';
import User from './user';

@Component({
    name: 'UsersEdit',
})
export default class UsersEdit extends VueBuefy {
    private user: User = null;
    private loading: boolean = false;

    created(): void {
        this.fetchUser();
    }

    updateUser(): void {
        this.loading = true;

        this.axios
            .patch(`/api/admin/users/${this.$route.params.id}`, this.user)
            .then(res => res.data)
            .then(res => {
                this.user = res.data;
                this.loading = false;
            })
            .catch(err => {
                this.loading = false;
                this.$snackbar.open({
                    message: 'Unable to update user, maybe you are offline?',
                    type: 'is-danger',
                    position: 'is-top',
                    actionText: 'Retry',
                    indefinite: true,
                    onAction: () => {
                        this.updateUser();
                    },
                });
                throw err;
            });
    }

    fetchUser(): void {
        this.loading = true;

        this.axios
            .get(`/api/admin/users/${this.$route.params.id}`)
            .then(res => res.data)
            .then(res => {
                this.user = res.data;
                this.loading = false;
            })
            .catch(err => {
                this.user = null;
                this.loading = false;
                this.$snackbar.open({
                    message: 'Unable to load user, maybe you are offline?',
                    type: 'is-danger',
                    position: 'is-top',
                    actionText: 'Retry',
                    indefinite: true,
                    onAction: () => {
                        this.fetchUser();
                    },
                });
                throw err;
            });
    }

    showSuccess(message: string): void {
        this.$toast.open({
            message: message,
            type: 'is-success',
        });
    }

    showError(message: string): void {
        this.$toast.open({
            message: message,
            type: 'is-danger',
            duration: 5000,
        });
    }

    toggle(row: object): void {
        this.$refs.table.toggleDetails(row);
    }

    confirmDeleteSelectedUser(): void {
        this.$dialog.confirm({
            title: 'Deleting Albums',
            message:
                'Are you sure you want to <b>delete</b> these users? This action cannot be undone.',
            confirmText: 'Delete Albums',
            type: 'is-danger',
            hasIcon: true,
            onConfirm: () => {
                this.deleteSelectedUser();
            },
        });
    }

    /**
     * Delete user from slug
     */
    deleteSelectedUser(): void {
        this.axios
            .delete(`/api/admin/users/${this.user.id}`)
            .then(res => {
                this.showSuccess('User deleted');
                // TODO Redirect index
            })
            .catch(err => {
                this.showError(`Unable to delete user <br> <small>${err.message}</small>`);
                throw err;
            });
    }
}
</script>
