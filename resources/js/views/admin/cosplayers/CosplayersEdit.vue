<template>
  <div class="card">
    <div class="card-content">
      <b-field
        :type="errors.name ? 'is-danger' : ''"
        :message="errors.name ? errors.name[0] : null"
        label="Name"
      >
        <b-input v-model="cosplayer.name" />
      </b-field>

      <b-field
        :type="errors.description ? 'is-danger' : ''"
        :message="errors.description ? errors.description[0] : null"
        label="Description"
      >
        <quill-editor
          ref="myQuillEditor"
          v-model="cosplayer.description"
          :options="editorOption"
        />
      </b-field>

      <div class="columns">
        <div class="column">
          <div v-if="cosplayer.avatar">
            <label class="label">Current avatar</label>
            <img :src="cosplayer.avatar.thumb"
alt="" >
            <b-button
              type="is-danger"
              icon-right="trash-alt"
              @click="cosplayer.avatar = null"
            />
          </div>

          <b-field
            v-else
            :type="errors.avatar ? 'is-danger' : ''"
            :message="errors.avatar ? errors.avatar[0] : null"
            label="Upload avatar"
          >
            <b-upload
v-model="cosplayer.avatar"
drag-drop>
              <section class="section">
                <div class="content has-text-centered">
                  <p>
                    <b-icon
icon="upload"
size="is-large" />
                  </p>
                  <p>
                    Drop your files here or click to upload
                  </p>
                  <span
                    v-if="cosplayer.avatar"
                    class="file-name"
                  >
                    {{ cosplayer.avatar.name }}
                  </span>
                </div>
              </section>
            </b-upload>
          </b-field>
        </div>
        <div class="column">
          <b-field
            :type="errors.user_id ? 'is-danger' : ''"
            :message="errors.user_id ? errors.user_id[0] : null"
            label="Linked user"
          >
            <div
              v-if="cosplayer && cosplayer.user"
              class="media box"
            >
              <div class="media-content">
                <div class="content">
                  <p>
                    <strong>{{
                      cosplayer.user.name
                    }}</strong>
                    <br >
                    Role : {{ cosplayer.user.role }}
                  </p>
                </div>
              </div>
              <div class="media-right">
                <b-button
                  :to="{
                    name: 'admin.users.edit',
                    params: { id: cosplayer.user.id }
                  }"
                  tag="router-link"
                  type="is-text"
                  size="is-small"
                >
                  Update
                </b-button>
                <button
                  class="delete"
                  @click="cosplayer.user = null"
                />
              </div>
            </div>
            <div v-else-if="cosplayer && !cosplayer.user">
              <pick-one-user
                :user.sync="cosplayer.user"
                :errors="errors"
              />
            </div>
          </b-field>
        </div>
      </div>

      <b-button
        :loading="loading"
        type="is-primary"
        @click="updateCosplayer()"
      >
        Update
      </b-button>
    </div>
  </div>
</template>

<script lang="ts">
import { Component } from "vue-property-decorator";
import { quillEditor } from "vue-quill-editor";
import Buefy from "../../../admin/Buefy.vue";
import Cosplayer from "../../../models/cosplayer";
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";
import "quill/dist/quill.bubble.css";
import User from "../../../models/user";
import PickOneUser from "../../../components/admin/PickOneUser.vue";
import { showError, showSuccess } from "../../../admin/toast";

@Component({
    name: "CosplayersEdit",
    components: {
        quillEditor,
        PickOneUser
    }
})
export default class CosplayersEdit extends Buefy {
    private cosplayer: Cosplayer = new Cosplayer();
    private loading = false;
    private searchUsers: Array<User> = [];
    protected errors: object = {};

    protected editorOption: object = {
        placeholder: "Enter your description...",
        theme: "snow"
    };

    created(): void {
        this.fetchCosplayer();
    }

    async updateCosplayer(): Promise<void> {
        try {
            this.loading = true;
            const formData: FormData = await this.cosplayerToFormData(
                this.cosplayer
            );
            const res = await this.axios.post(
                `/api/admin/cosplayers/${this.$route.params.slug}`,
                formData,
                {
                    headers: { "Content-Type": "multipart/form-data" }
                }
            );
            const { data } = res.data;
            showSuccess(this.$buefy, "Cosplayer updated");
            this.errors = {};
            this.cosplayer = data;
            this.loading = false;
        } catch (exception) {
            this.loading = false;
            showError(
                this.$buefy,
                "Unable to update cosplayer",
                this.updateCosplayer
            );
            this.errors = exception.response.data.errors;
            throw exception;
        }
    }

    async fetchCosplayer(): Promise<void> {
        this.loading = true;
        try {
            const res = await this.axios.get(
                `/api/admin/cosplayers/${this.$route.params.slug}`
            );
            const { data } = res.data;
            this.cosplayer = data;
            this.loading = false;
        } catch (exception) {
            this.cosplayer = new Cosplayer();
            this.loading = false;
            showError(
                this.$buefy,
                "Unable to load cosplayer, maybe you are offline?",
                this.fetchCosplayer
            );
            throw exception;
        }
    }

    async searchUser(name: string): Promise<void> {
        try {
            const res = await this.axios.get("/api/admin/users", {
                params: { "filter[name]": name }
            });
            const { data } = res.data;
            this.searchUsers = data;
        } catch (exception) {
            this.$buefy.snackbar.open({
                message: "Unable to load users, maybe you are offline?",
                type: "is-danger",
                position: "is-top"
            });
            throw exception;
        }
    }

    cosplayerToFormData(cosplayer: Cosplayer): FormData {
        const formData = new FormData();
        formData.append("_method", "PATCH");
        //TODO Rewrite
        if (cosplayer.name) {
            formData.append("name", cosplayer.name);
        }
        if (cosplayer.description) {
            formData.append("description", cosplayer.description);
        }
        if (this.cosplayer.user && cosplayer.user.id) {
            formData.append("user_id", String(cosplayer.user.id));
        }
        if (cosplayer.avatar) {
            formData.append("avatar", cosplayer.avatar);
        }

        return formData;
    }
}
</script>
