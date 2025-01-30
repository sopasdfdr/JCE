<template>
    <v-dialog :model-value="show" @update:model-value="updateShow" max-width="500px">
      <template v-slot:default="{ isActive, close }">
        <v-card>
          <v-card-title>
            <span class="headline">{{ title }}</span>
          </v-card-title>
  
          <v-card-text>
            <form @submit.prevent="submit">
              <div class="form-group">
                <v-text-field
                  id="nome"
                  v-model="formData.nome"
                  required
                  label="Nome"
                ></v-text-field>
              </div>
  
              <div class="form-group">
                <v-text-field
                  id="codigo"
                  v-model="formData.codigo"
                  required
                  label="Código"
                ></v-text-field>
              </div>
  
              <div class="form-group">
                <v-select
                  clearable
                  id="tipo"
                  label="Tipo"
                  :items="['MPrima', 'Líquido', 'Adição Manual']"
                  v-model="selectedTipo"
                  required
                ></v-select>
              </div>
            </form>
          </v-card-text>
  
          <v-card-actions>
            <v-btn class="buttonSave" @click="submit">Save</v-btn>
            <v-btn class="buttonClose" @click="closeModal">Cancel</v-btn>
          </v-card-actions>
        </v-card>
      </template>
    </v-dialog>
  </template>
  
  <script>
import axios from 'axios';

export default {
  name: 'AddProductModal',
  props: {
    show: {
      type: Boolean,
      required: true,
    },
    title: {
      type: String,
      default: 'Add Product',
    },
  },
  data() {
    return {
      formData: {
        nome: '',
        codigo: '',
        tipo: '',
      },
      selectedTipo: '', // To hold the selected value from v-select
    };
  },
  methods: {
    // Function to map the selected value to a number
    updateTipo() {
      const tipoMapping = {
        'MPrima': 0,
        'Líquido': 1,
        'Adição Manual': 2,
      };
      this.formData.tipo = tipoMapping[this.selectedTipo] || 0; // Map the value or reset if not found
    },
    submit() {
      this.updateTipo(); // Ensure tipo is updated before submission
      try {
        const response = axios.post('/api/adicionarProdutos', this.formData);
        this.formData = { nome: '', codigo: '', tipo: '' }; // Reset form
        this.selectedTipo = ''; // Reset dropdown
        this.$emit('close'); // Close the modal after submitting
      } catch (error) {
        console.error('Error adding product:', error);
      }
    },
    closeModal() {        
      this.formData = { nome: '', codigo: '', tipo: '' }; // Reset form
      this.selectedTipo = ''; // Reset dropdown
      this.$emit('close');
    },
    updateShow(value) {
      this.$emit('update:show', value); // Emit the updated value to the parent component
    },
  },
};
</script>

  
  <style scoped>
.modal {
  padding: 20px;
  background-color: white;
  border-radius: 8px;
  box-shadow: 0px 0px 10px 0px rgb(0 0 0 / 0.8);
  max-width: 500px;
  margin: 0 auto;
}

.form-group {
  display: flex;
  flex-direction: column-reverse; /* Reverse the order of label and input */
  margin-bottom: 20px;
}

label {
  margin-top: 5px;
  font-size: 14px;
  color: #555;
}

input {
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.modal-actions {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
}

button {
  padding: 10px 15px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.buttonSave {
  background-color: #4caf50;
  color: white;
}

.buttonClose {
  background-color: #f44336;
  color: white;
}
</style>

  