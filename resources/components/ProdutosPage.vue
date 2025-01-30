<template>
  <div>
    <div style="display: flex; justify-content: space-between; align-items: center;">
      <h2>JCE Produtos</h2>
      <v-btn prepend-icon="mdi-plus-box" @click="showModal = true" class="add-product-button">Adicionar Produto</v-btn>

      <v-dialog v-model="showFormulasDialog" max-width="600px">
  <v-card>
    <v-card-title>Erro! Existem fórmulas a usar o produto "{{ productToDelete?.prd_nome }}"</v-card-title>
    <v-card-text>
      <p>Este produto está a ser utilizado nas seguintes fórmulas:</p>
      <v-list>
        <v-list-item-group v-if="formulasUsingProduct.length">
          <v-list-item v-for="formula in formulasUsingProduct" :key="formula.fml_id">
            <v-list-item-content>
              <v-list-item-title>{{ formula.fml_nome }}</v-list-item-title>
              <v-list-item-subtitle>{{ formula.fml_codigo }}</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
        <v-list-item v-else>
          <v-list-item-content>Nenhuma fórmula está a utilizar este produto.</v-list-item-content>
        </v-list-item>
      </v-list>
    </v-card-text>
    <v-card-actions>
      <v-btn text @click="showFormulasDialog = false">Close</v-btn>
    </v-card-actions>
  </v-card>
</v-dialog>

    </div>
    <p>Gestão de Produtos</p>

    <!-- Table to display the products -->
    <v-table v-if="products.length > 0" fixed-header>
      <thead>
        <tr>
          <th>Nome</th>
          <th>Código</th>
          <th>Tipo</th>
          <th>Ações</th> <!-- Actions column -->
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in products" :key="product.prd_id">
          <td @click="openEditModal(product)" class="clickable-name">{{ product.prd_nome }}</td>
          <td>{{ product.prd_codigo }}</td>
          <td>{{ productTypeTranslate(product.prd_tipo) }}</td>
          <td>
            <v-btn icon="mdi-trash-can-outline" variant="plain" @click="confirmDelete(product)">
            </v-btn>
          </td>
        </tr>
      </tbody>
    </v-table>

    <!-- Show a message if there are no products -->
    <p v-else>No products found.</p>

    <!-- Add Product Modal -->
    <AddProductModal
      :show="showModal"
      :product="selectedProduct"
      title="Add Product"
      @close="showModal = false; fetchProducts()"
    />

    <!-- Edit Product Modal -->
    <EditProductModal
      :show="showModalEdit"
      :product="selectedProduct"
      title="Edit Product"
      @close="showModalEdit = false; fetchProducts()"
    />

    <!-- Reusable Delete Confirmation Dialog -->
    <DeleteConfirmationDialog
      :show="showDeleteDialog"
      title="Confirm Delete"
      :message="`Are you sure you want to delete the product '${productToDelete?.prd_nome}'?`"
      @confirm="deleteProduct"
      @update:show="showDeleteDialog = $event"
    />
  </div>
</template>


<script>
import axios from 'axios';
import AddProductModal from '../modals/AddProductModal.vue';
import EditProductModal from '../modals/EditProductModal.vue';
import DeleteConfirmationDialog from '../modals/DeleteConfirmationDialog.vue';

export default {
  name: 'ProdutosPage',
  components: { AddProductModal, EditProductModal, DeleteConfirmationDialog },
  data() {
    return {
      products: [], // Array to hold the products
      showModal: false, // Control modal visibility for Add Product
      showModalEdit: false, // Control modal visibility for Edit Product
      showDeleteDialog: false, // Control delete confirmation dialog
      selectedProduct: null, // To store the selected product for editing
      productToDelete: null, // To store the product selected for deletion
      showFormulasDialog: false, // Control visibility of formulas dialog
      formulasUsingProduct: [], // Store formulas using the product
    };
  },
  mounted() {
    // Fetch products when the component is mounted
    this.fetchProducts();
  },
  methods: {
    async fetchProducts() {
      try {
        const response = await axios.get('/api/produtos'); // Adjust the URL if necessary
        this.products = response.data; // Store the products in the component's data
      } catch (error) {
        console.error('Error fetching products:', error);
      }
    },
    productTypeTranslate(tipo) {
      switch (tipo) {
        case 0:
          return 'Matéria-Prima';
        case 1:
          return 'Líquido';
        case 2:
          return 'Adição Manual';
      }
    },
    openEditModal(product) {
      this.selectedProduct = product; // Set the selected product
      this.showModalEdit = true; // Open the modal
    },
    async confirmDelete(product) {
      this.productToDelete = product; // Store the product to be deleted

      try {
        // Make an API call to check if the product is used in any formulas
        const response = await axios.get(`/api/formulas/used/${product.prd_id}`);
        
        if (response.data.length > 0) {
          // If the product is used in any formulas, show the dialog with formulas
          this.formulasUsingProduct = response.data;
          this.showFormulasDialog = true;
        } else {
          // If the product is not used, show the delete confirmation dialog
          this.showDeleteDialog = true;
        }
      } catch (error) {
        console.error('Error checking if product is used in formulas:', error);
      }
    },
    async deleteProduct() {
      if (!this.productToDelete) return;

      try {
        await axios.delete(`/api/produtos/${this.productToDelete.prd_id}`); // Adjust the URL if necessary
        this.fetchProducts(); // Refresh the product list
        this.showDeleteDialog = false; // Close the dialog
        this.productToDelete = null; // Reset the selected product
      } catch (error) {
        console.error('Error deleting product:', error);
      }
    },
  },
};
</script>


<style scoped>
.add-product-button {
  background-color: #4CAF50;
  color: white;
  border: none;
  padding: 10px 20px;
  cursor: pointer;
  border-radius: 4px;
}

.add-product-button:hover {
  background-color: #45a049;
}

thead {
  color: #004e7a;
}

th {
  background-color: #857f7f;
}

tr:nth-child(even) {
  background-color: #f9f9f9;
}

/* Style for clickable product name */
.clickable-name {
  text-decoration: underline;
  color: #007bff;
  cursor: pointer;
}

.clickable-name:hover {
  color: #0056b3;
}
</style>
