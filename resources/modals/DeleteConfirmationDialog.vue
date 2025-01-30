<template>
  <v-dialog v-model="dialogVisible" max-width="400px">
    <v-card>
      <v-card-title>{{ title }}</v-card-title>
      <v-card-text>
        {{ message }}
      </v-card-text>
      <v-card-actions>
        <v-btn class="delete" text @click="confirmDelete">Delete</v-btn>
        <v-btn class="cancel" text @click="closeDialog">Cancel</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'DeleteConfirmationDialog',
  props: {
    show: {
      type: Boolean,
      required: true,
    },
    title: {
      type: String,
      default: 'Confirm Delete',
    },
    message: {
      type: String,
      default: 'Are you sure you want to delete this item?',
    },
  },
  emits: ['confirm', 'update:show'],
  computed: {
    dialogVisible: {
      get() {
        return this.show; // Proxy for the `show` prop
      },
      set(value) {
        this.$emit('update:show', value); // Emit the `update:show` event when value changes
      },
    },
  },
  methods: {
    confirmDelete() {
      this.$emit('confirm'); // Emit confirm event
      this.closeDialog();
    },
    closeDialog() {
      this.dialogVisible = false; // Close the dialog
    },
  },
};
</script>

<style scoped>
.cancel {
  background-color: #9e9e9e;
  color: white;
}

.delete {
  background-color: #f44336;
  color: white;
}
</style>
