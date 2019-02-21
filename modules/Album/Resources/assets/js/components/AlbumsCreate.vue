<template>
    <div>
        <h1>Create A Post</h1>
        <form @submit.prevent="addAlbum">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Title:</label>
                        <input type="text" class="form-control" v-model="album.title">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Body:</label>
                        <textarea class="form-control" v-model="album.body" rows="5"></textarea>
                    </div>
                </div>
            </div>
            <input type="checkbox" v-model="album.private">
            <br/>
            <div class="form-group">
                <button class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                album: {}
            }
        },
        methods: {
            addAlbum() {
                console.log(this.album);
                let uri = '/api/admin/albums';
                this.axios.post(uri, this.album)
                    .then(res => res.data)
                    .then(res => {
                        console.log(res);
                        this.$router.push({name: 'albums.index'});
                    })
                    .catch(res => {
                        console.log(res);
                    });
            }
        }
    }
</script>