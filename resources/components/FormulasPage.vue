<template>
  <div style="display: flex; justify-content: space-between; align-items: center;">
    <h2>JCE Fórmulas</h2>
    <v-btn prepend-icon="mdi-plus-box" @click="showDialog = true" class="add-formula-button">Adicionar Fórmula</v-btn>

    <AddFormulaDialog
      :show="showDialog"
      :products="products"
      @update:show="showDialog = $event"
      @save-formula="saveFormula"
    />

    <EditFormulaDialog
      :show="showEditDialog"
      :formula="formulaToEdit"
      :products="products"
      @update:show="showEditDialog = $event"
      @update-formula="updateFormula"
    />

    <!-- Reusable Delete Confirmation Dialog -->
    <DeleteConfirmationDialog
      :show="showDeleteDialog"
      title="Confirm Delete"
      :message="`Are you sure you want to delete the Formula '${formulaToDelete?.fml_nome}'?`"
      @confirm="deleteFormula"
      @update:show="showDeleteDialog = $event"
    />

    <!-- Formula Details Dialog -->
    <FormulaDetailsDialog
      :show="showFormulaDialog"
      :formulaId="selectedFormulaId"
      @update:show="showFormulaDialog = $event"
    />
  </div>
  <p>Gestão de Fórmulas</p>
  <v-container>
  <v-row>
    <v-col v-for="formula in formulas" :key="formula.frm_id" cols="12" md="4" lg="3">
      <v-card class="formula-card" @click="showFormulaDetails(formula.fml_id)">
        <v-card-title>{{ formula.fml_nome }}</v-card-title>
        <v-card-subtitle>Código: {{ formula.fml_codigo }}</v-card-subtitle>
        <v-card-text>
          <p><strong>Quantidade Total:</strong> {{ formula.fml_qtde }} Kg</p>
          <p><strong>Nr. Produtos:</strong> {{ formula.fml_nrprodutos }}</p>
        </v-card-text>
        <v-card-actions>
          <v-btn color="blue" icon="mdi-pencil" variant="plain" @click.stop="editFormula(formula)"></v-btn>
          <v-spacer></v-spacer>
          <v-btn color="red" icon="mdi-trash-can-outline" variant="plain" @click.stop="confirmDelete(formula)"></v-btn>
        </v-card-actions>
      </v-card>
    </v-col>
  </v-row>
</v-container>

</template>

<script>
import axios from 'axios';
import AddFormulaDialog from "../modals/AddFormulaDialog.vue";
import DeleteConfirmationDialog from '../modals/DeleteConfirmationDialog.vue';
import EditFormulaDialog from "../modals/EditFormulaDialog.vue";
import FormulaDetailsDialog from '../modals/FormulaDetailsDialog.vue';

export default {
  name: 'FormulasPage',
  components: { AddFormulaDialog, DeleteConfirmationDialog, FormulaDetailsDialog, EditFormulaDialog },
  data() {
    return {
      formulas: [],
      showDialog: false,
      showEditDialog: false,
      products: [],
      formulaToEdit: null,
      showDeleteDialog: false,
      formulaToDelete: null,
      showFormulaDialog: false, // Control formula details dialog
      selectedFormulaId: null,  // Store the ID of the selected formula
    };
  },
  mounted() {
    this.fetchFormulas();
    this.fetchProducts();
  },
  methods: {
    async fetchFormulas() {
      try {
        const response = await axios.get('/api/formulas');
        this.formulas = response.data;
      } catch (error) {
        console.error('Error fetching formulas:', error);
      }
    },
    async fetchProducts() {
      try {
        const response = await axios.get("/api/produtos");
        this.products = response.data;
      } catch (error) {
        console.error('Error fetching products:', error);
      }
    },
    saveFormula(selectedProducts) {
      console.log("Saved formula:", selectedProducts);
      try {
        const response = axios.post('/api/adicionarFormula', selectedProducts);
        this.selectedProducts = []; // Reset form
        this.fetchFormulas();
        this.$emit('close'); // Close the modal after submitting
      } catch (error) {
        console.error('Error adding product:', error);
      }
    },
    async editFormula(formula) {
      try {
        const response = await axios.get(`/api/formulas/${formula.fml_id}/details`);
        this.formulaToEdit = response.data;
        this.showEditDialog = true;
      } catch (error) {
        console.error('Error fetching formula details:', error);
      }
    },
    async updateFormula(updatedFormula) {
      console.log("🚀 ~ updateFormula ~ updatedFormula:", updatedFormula)
      try {
        await axios.put(`/api/formulas/${updatedFormula.fml_id}`, updatedFormula);
        this.fetchFormulas();
        this.showEditDialog = false;
      } catch (error) {
        console.error('Error updating formula:', error);
      }
    },
    confirmDelete(formula) {
      this.formulaToDelete = formula; // Store the product to be deleted
      this.showDeleteDialog = true; // Open the confirmation dialog
    },
    async deleteFormula() {
      if (!this.formulaToDelete) return;

      try {
        await axios.delete(`/api/formulas/${this.formulaToDelete.fml_id}`); // Adjust the URL if necessary
        this.fetchFormulas(); // Refresh the product list
        this.showDeleteDialog = false; // Close the dialog
        this.formulaToDelete = null; // Reset the selected formula
      } catch (error) {
        console.error('Error deleting formula:', error);
      }
    },
    showFormulaDetails(formulaId) {
      console.log("🚀 ~ showFormulaDetails ~ formulaId:", formulaId)
      this.selectedFormulaId = formulaId;
      this.showFormulaDialog = true; // Open the formula details dialog
    },
  },
};
</script>

<style scoped>
.add-formula-button {
  background-color: #4CAF50;
  color: white;
  border: none;
  padding: 10px 20px;
  cursor: pointer;
  border-radius: 4px;
}
</style>
