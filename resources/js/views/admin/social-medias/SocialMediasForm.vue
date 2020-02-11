<template>
  <section>
    <h1 class="title">
      {{ isCreating ? "Create SocialMedia" : "Update SocialMedia" }}
    </h1>

    <div class="card">
      <div class="card-content">
        <form @submit.prevent="sendOrCreateSocialMedia">
          <b-field
            :type="errors.name ? 'is-danger' : ''"
            :message="errors.name ? errors.name[0] : null"
            label="Name"
          >
            <b-input
              v-model="socialMedia.name"
              type="text"
              maxlength="30"
            />
          </b-field>

          <b-field
            :type="errors.url ? 'is-danger' : ''"
            :message="errors.url ? errors.url[0] : null"
            label="Url"
          >
            <b-input
              v-model="socialMedia.url"
              type="text"
            />
          </b-field>

          <b-field
            :type="errors.icon ? 'is-danger' : ''"
            :message="errors.icon ? errors.icon[0] : null"
            label="Icon"
          >
            <b-input
              v-model="socialMedia.icon"
              type="text"
            />
          </b-field>

          <b-field
            :type="errors.color ? 'is-danger' : ''"
            :message="errors.color ? errors.color[0] : null"
            label="Color"
          >
            <b-input
              v-model="socialMedia.color"
              type="text"
            />
          </b-field>

          <b-field
            :type="errors.active ? 'is-danger' : ''"
            :message="errors.active ? errors.active[0] : null"
            label="Active?"
          >
            <div class="field">
              <b-switch
                v-model="socialMedia.active"
                :true-value="true"
                :false-value="false"
              >
                {{ socialMedia.active ? "Yes" : "No" }}
              </b-switch>
            </div>
          </b-field>

          <div class="buttons">
            <b-button
              :loading="loading"
              tag="input"
              native-type="submit"
              type="is-primary"
              :value="isCreating ? 'Create' : 'Update'"
            />
            <b-button
              v-if="!isCreating"
              :loading="loading"
              type="is-danger"
              @click="confirmDeleteSocialMedia()"
            >
              Delete
            </b-button>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>

<script lang="ts">
import Component from "vue-class-component";
import { showSuccess, showError } from "../../../admin/toast";
import Buefy from "../../../admin/Buefy.vue";
import { Prop } from "vue-property-decorator";
import SocialMedia from "../../../models/social-media";
import { RawLocation } from "vue-router";
import { Dictionary } from "vue-router/types/router";

interface SocialMediaErrorsInterface {
    name?: object;
    url?: object;
    icon?: object;
    color?: object;
    active?: object;
}

@Component({
    name: "SocialMediasForm"
})
export default class SocialMediasForm extends Buefy {
    @Prop({ required: true, type: Boolean })
    protected isCreating: boolean;

    protected drag = false;
    protected errors: SocialMediaErrorsInterface = {};
    protected socialMedia: SocialMedia = new SocialMedia();
    protected loading = true;

    created(): void {
        if (this.isCreating) {
            this.socialMedia = new SocialMedia();
            this.loading = false;
        } else {
            this.fetchSocialMedia();
        }
    }

    sendOrCreateSocialMedia(): void {
        this.loading = true;

        if (this.isCreating) {
            this.createSocialMedia();
        } else {
            this.updateSocialMedia();
        }

        this.loading = false;
    }

    async fetchSocialMedia(): Promise<void> {
        this.loading = true;

        try {
            const res = await this.axios.get(
                `/api/admin/social-medias/${this.$route.params.id}`
            );
            const { data } = res.data;
            this.socialMedia = data;
            this.loading = false;
        } catch (exception) {
            showError(this.$buefy, "Unable to fetch SocialMedia");
            console.error(exception);
        }
    }

    async updateSocialMedia(): Promise<void> {
        if (this.socialMedia === undefined) {
            throw new DOMException("Unable to update undefined SocialMedia.");
        }

        try {
            const res = await this.axios.patch(
                `/api/admin/social-medias/${this.$route.params.id}`,
                this.socialMedia
            );
            const { data } = res.data;
            this.socialMedia = data;
            showSuccess(this.$buefy, "SocialMedia updated");
            this.errors = {};
        } catch (exception) {
            showError(
                this.$buefy,
                `Unable to update the SocialMedia <br><small>${exception.response.data.message}</small>`
            );
            this.errors = exception.response.data.errors;
            throw exception;
        }
    }

    async createSocialMedia(): Promise<void> {
        try {
            const res = await this.axios.post(
                `/api/admin/social-medias/`,
                this.socialMedia
            );
            const { data } = res.data;
            this.socialMedia = data;
            this.errors = {};
            showSuccess(this.$buefy, "SocialMedia successfully created");
            await this.$router.push({
                name: "admin.social-medias.edit",
                params: { id: this.socialMedia.id.toString() }
            });
        } catch (exception) {
            showError(
                this.$buefy,
                `Unable to create the SocialMedia <br><small>${exception.response.data.message}</small>`
            );
            this.errors = exception.response.data.errors;
            throw exception;
        }
    }

    async confirmDeleteSocialMedia(): Promise<void> {
        this.$buefy.dialog.confirm({
            title: "Deleting social media",
            message:
                "Are you sure you want to <b>delete</b> this SocialMedia? This action cannot be undone.",
            confirmText: "Delete social media",
            type: "is-danger",
            hasIcon: true,
            onConfirm: () => this.deleteSocialMedia()
        });
    }

    async deleteSocialMedia(): Promise<void> {
        if (this.socialMedia === undefined) {
            throw new DOMException("Unable to delete undefined SocialMedia.");
        }

        this.loading = true;

        try {
            await this.axios.delete(
                `/api/admin/social-medias/${this.socialMedia.id}`
            );
            showSuccess(this.$buefy, "SocialMedia successfully deleted!");
            await this.$router.push({ name: "admin.SocialMedias.index" });
        } catch (exception) {
            showError(this.$buefy, `Unable to delete the picture`);
            throw exception;
        }

        this.loading = false;
    }
}
</script>
