<template>
    <div class="users">
        <div class="loading" v-if="loading">Loading...</div>

        <div v-if="error" class="error">
            {{ error }}
            <button @click.prevent="fetchData()">Try Again</button>
        </div>

        <ul v-if="users">
            <li v-for="{ id, name, email } in users" :key="id">
                <strong>Name:</strong>
                {{ name }},
                <strong>Email:</strong>
                {{ email }}
            </li>
        </ul>
    </div>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import axios from 'axios';
import VueBuefy from '../../buefy';

@Component
export default class UserIndex extends VueBuefy {
    loading: boolean = false;
    users: Array<object> = [];
    error: any = null;

    created(): void {
        this.fetchData();
    }

    fetchData(): void {
        this.error = null;
        this.users = [];
        this.loading = true;
        axios
            .get('/api/users')
            .then(response => {
                console.log(response);
                this.users = response.data;
                this.loading = false;
            })
            .catch(error => {
                this.loading = false;
                this.error = error.response.data.message || error.message;
            });
    }
}
</script>
