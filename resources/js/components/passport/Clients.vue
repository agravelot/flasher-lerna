<style scoped>
.action-link {
    cursor: pointer;
}

.m-b-none {
    margin-bottom: 0;
}
</style>

<template>
  <div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <div style="display: flex; justify-content: space-between; align-items: center;">
          <span>
            OAuth Clients
          </span>

          <a
            class="action-link"
            @click="showCreateClientForm"
          >
            Create New Client
          </a>
        </div>
      </div>

      <div class="panel-body">
        <!-- Current Clients -->
        <p
          v-if="clients.length === 0"
          class="m-b-none"
        >
          You have not created any OAuth clients.
        </p>

        <table
          v-if="clients.length > 0"
          class="table table-borderless m-b-none"
        >
          <thead>
            <tr>
              <th>Client ID</th>
              <th>Name</th>
              <th>Secret</th>
              <th />
              <th />
            </tr>
          </thead>

          <tbody>
            <tr v-for="client in clients">
              <!-- ID -->
              <td style="vertical-align: middle;">
                {{ client.id }}
              </td>

              <!-- Name -->
              <td style="vertical-align: middle;">
                {{ client.name }}
              </td>

              <!-- Secret -->
              <td style="vertical-align: middle;">
                <code>{{ client.secret }}</code>
              </td>

              <!-- Edit Button -->
              <td style="vertical-align: middle;">
                <a
                  class="action-link"
                  @click="edit(client)"
                >
                  Edit
                </a>
              </td>

              <!-- Delete Button -->
              <td style="vertical-align: middle;">
                <a
                  class="action-link text-danger"
                  @click="destroy(client)"
                >
                  Delete
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Create Client Modal -->
    <div
      id="modal-create-client"
      class="modal"
      tabindex="-1"
      role="dialog"
      :class="[clientModal ? 'is-active' : '']"
    >
      <div class="modal-background" />
      <div class="modal-card">
        <div class="modal-card-head">
          <h4 class="modal-card-title">
            Create Client
          </h4>
          <a
            class="delete"
            @click.prevent="closeClientModal"
          />
        </div>

        <div class="modal-card-body">
          <!-- Form Errors -->
          <div
            v-if="createForm.errors.length > 0"
            class="notification is-danger"
          >
            <strong>Whoops! Something went wrong!</strong>
            <br>
            <ul>
              <li v-for="error in createForm.errors">
                {{ error }}
              </li>
            </ul>
          </div>

          <!-- Create Client Form -->
          <div class="control">
            <div class="field">
              <label class="label">Name</label>
              <p class="control">
                <input
                  v-model="createForm.name"
                  class="input"
                  type="text"
                  @keyup.enter="store"
                >
              </p>
              <p class="help">
                Something your users will recognize and trust.
              </p>
            </div>
          </div>
          <div class="control">
            <div class="field">
              <label class="label">Redirect URL</label>
              <p class="control">
                <input
                  v-model="createForm.redirect"
                  class="input"
                  type="text"
                  @keyup.enter="store"
                >
              </p>
              <p class="help">
                Your application's authorization callback URL.
              </p>
            </div>
          </div>
        </div>

        <!-- Modal Actions -->
        <div class="modal-card-foot">
          <a
            class="button"
            @click.prevent="closeClientModal"
          >Close</a>
          <a
            class="button is-primary"
            @click="store"
          >Create</a>
        </div>
      </div>
    </div>

    <!-- Edit Client Modal -->
    <div
      id="modal-edit-client"
      class="modal fade"
      tabindex="-1"
      role="dialog"
      :class="[]"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <a
              class="delete"
              @click.prevent="closeEditClientModal"
            />

            <h4 class="modal-title">
              Edit Client
            </h4>
          </div>

          <div class="modal-body">
            <!-- Form Errors -->
            <div
              v-if="editForm.errors.length > 0"
              class="notification is-danger"
            >
              <strong>Whoops! Something went wrong!</strong>
              <br>
              <ul>
                <li v-for="error in editForm.errors">
                  {{ error }}
                </li>
              </ul>
            </div>

            <!-- Edit Client Form -->
            <form
              class="form-horizontal"
              role="form"
            >
              <!-- Name -->
              <div class="form-group">
                <label class="col-md-3 control-label">Name</label>

                <div class="col-md-7">
                  <input
                    id="edit-client-name"
                    v-model="editForm.name"
                    type="text"
                    class="form-control"
                    @keyup.enter="update"
                  >

                  <span class="help-block">
                    Something your users will recognize and trust.
                  </span>
                </div>
              </div>

              <!-- Redirect URL -->
              <div class="form-group">
                <label class="col-md-3 control-label">Redirect URL</label>

                <div class="col-md-7">
                  <input
                    v-model="editForm.redirect"
                    type="text"
                    class="form-control"
                    name="redirect"
                    @keyup.enter="update"
                  >

                  <span class="help-block">
                    Your application's authorization callback URL.
                  </span>
                </div>
              </div>
            </form>
          </div>

          <!-- Modal Actions -->
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-default"
              data-dismiss="modal"
            >
              Close
            </button>

            <button
              type="button"
              class="btn btn-primary"
              @click="update"
            >
              Save Changes
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

const { $ } = window;
export default {
  /*
     * The component's data.
     */
  data() {
    return {
      clients: [],

      createForm: {
        errors: [],
        name: '',
        redirect: '',
      },

      editForm: {
        errors: [],
        name: '',
        redirect: '',
      },
      clientModal: false,
      editClientModal: false,
    };
  },

  /**
     * Prepare the component (Vue 1.x).
     */
  ready() {
    this.prepareComponent();
  },

  /**
     * Prepare the component (Vue 2.x).
     */
  mounted() {
    this.prepareComponent();
  },

  methods: {
    /**
         * Prepare the component.
         */
    prepareComponent() {
      this.getClients();
    },

    /**
         * Get all of the OAuth clients for the user.
         */
    getClients() {
      axios.get('/oauth/clients').then((response) => {
        this.clients = response.data;
      });
    },

    /**
         * Show the form for creating new clients.
         */
    showCreateClientForm() {
      this.clientModal = true;
    },

    /**
         * Create a new OAuth client for the user.
         */
    store() {
      this.persistClient('post', '/oauth/clients', this.createForm, '#modal-create-client');
    },

    /**
         * Edit the given client.
         */
    edit(client) {
      this.editForm.id = client.id;
      this.editForm.name = client.name;
      this.editForm.redirect = client.redirect;

      this.closeEditClientModal();
    },

    /**
         * Update the client being edited.
         */
    update() {
      this.persistClient(
        'put',
        `/oauth/clients/${this.editForm.id}`,
        this.editForm,
        '#modal-edit-client',
      );
    },

    /**
         * Persist the client to storage using the given form.
         */
    persistClient(method, uri, form, modal) {
      form.errors = [];

      axios[method](uri, form)
        .then((response) => {
          this.getClients();

          form.name = '';
          form.redirect = '';
          form.errors = [];

          this.closeClientModal();
          this.closeEditClientModal();
        })
        .catch((response) => {
          if (typeof response.data === 'object') {
            form.errors = _.flatten(_.toArray(response.data));
          } else {
            form.errors = ['Something went wrong. Please try again.'];
          }
        });
    },

    /**
         * Destroy the given client.
         */
    destroy(client) {
      axios.delete(`/oauth/clients/${client.id}`).then((response) => {
        this.getClients();
      });
    },

    closeClientModal() {
      this.clientModal = false;
    },

    closeEditClientModal() {
      this.editClientModal = false;
    },
  },
};
</script>
