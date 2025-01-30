<template>
    <v-dialog
      :model-value="show"
      @update:model-value="updateShow"
      @click:outside="resetDialog"
      max-width="800px"
    >
      <v-card>
        <v-card-title>
          <span class="text-h5">Adicionar Fórmula</span>
        </v-card-title>
  
        <v-card-text>
          <v-stepper v-model="step" :items="items" show-actions>
            <!-- Step 1: Select Products -->
            <template v-slot:item.1>
              <br />
              <v-sheet border>
                <v-table>
                  <thead>
                    <tr>
                      <th>Description</th>
                      <th>Action</th>
                    </tr>
                  </thead>
  
                  <tbody>
                    <tr v-for="(product, index) in availableProducts" :key="index">
                      <td v-text="product.prd_nome"></td>
                      <td>
                        <v-btn color="green" icon="mdi-plus" density="compact" @click="addProduct(product)">
                        </v-btn>
                      </td>
                    </tr>
                  </tbody>
                </v-table>
              </v-sheet>
            </template>
  
            <!-- Step 2: Define Quantity and Priority -->
            <template v-slot:item.2>
              <!-- List for selected products with draggable feature -->
              <v-list v-if="selectedProducts.length" dense>
                <draggable v-model="selectedProducts" :options="{ handle: '.drag-handle' }" @start="onDragStart" @end="onDragEnd">
                  <template #item="{ element, index }">
                    <v-list-item :key="element.prd_id">
                      <v-list-item-content>
                        <v-row align="center" no-gutters>
                          <v-col cols="4">
                            <span class="drag-handle" style="cursor: move;">{{ element.prd_nome }}</span>
                          </v-col>
                          <v-col cols="3">
                            <v-text-field v-model="element.quantity" type="number" min="1" hide-details dense outlined label="Quantidade (Kg)"></v-text-field>
                          </v-col>
                          <v-col cols="3" class="ml-4 text-center">
                            <v-chip color="primary" text-color="white" class="mr-2" dense rounded>
                              {{ index + 1 }}
                            </v-chip>
                          </v-col>
                          <v-col cols="1" class="text-center">
                            <!-- Conditionally render the trash icon if there are at least 2 products selected -->
                            <v-btn v-if="selectedProducts.length > 1" icon color="red" density="compact" @click="removeProduct(element)">
                              <v-icon>mdi-delete</v-icon>
                            </v-btn>
                          </v-col>
                        </v-row>
                      </v-list-item-content>
                    </v-list-item>
                  </template>
                </draggable>
              </v-list>
            </template>
  
            <!-- Step 3: Formula Name and Code -->
            <template v-slot:item.3>
              <v-form ref="form">
                <v-text-field
                  v-model="formulaName"
                  label="Nome da Fórmula"
                  required
                  :rules="[v => !!v || 'O nome da fórmula é obrigatório']"
                ></v-text-field>
                <v-text-field
                  v-model="formulaCode"
                  label="Código da Fórmula"
                  required
                  :rules="[v => !!v || 'O código da fórmula é obrigatório']"
                ></v-text-field>
              </v-form>
            </template>
  
            <!-- Actions: Next, Previous, and Save buttons -->
            <template v-slot:actions>
              <v-btn color="blue" @click="nextStep" v-if="step === 1" class="mr-4 mb-1 ml-1" :disabled="selectedProducts.length === 0">
                Next
              </v-btn>
              <v-btn color="blue" @click="previousStep" v-if="step === 2" class="mr-4 mb-1 ml-1">
                Previous
              </v-btn>
              <v-btn color="blue" @click="nextStep" v-if="step === 2" class="mr-4 mb-1 ml-1">
                Next
              </v-btn>
              <v-btn color="blue" @click="previousStep" v-if="step === 3" class="mr-4 mb-1 ml-1">
                Previous
              </v-btn>
              <v-btn color="green" @click="submitFormula" v-if="step === items.length" class="ml-auto mb-1 ml-1">
                Save
              </v-btn>
            </template>
          </v-stepper>
        </v-card-text>
      </v-card>
    </v-dialog>
  </template>
  
  <script>
  import draggable from 'vuedraggable';
  
  export default {
    components: {
      draggable,
    },
    props: {
      show: Boolean,
      products: Array,
    },
    emits: ['update:show', 'save-formula'],
    data() {
      return {
        step: 1,
        selectedProducts: [],
        formulaName: '',
        formulaCode: '',
        formValid: false,
        items: [
          'Selecionar Produtos',
          'Definir Quantidade e Prioridade',
          'Informações da Fórmula',
        ],
      };
    },
    computed: {
      availableProducts() {
        return this.products.filter(
          (product) => !this.selectedProducts.some((selected) => selected.prd_id === product.prd_id)
        );
      },
    },
    methods: {
      addProduct(product) {
        this.selectedProducts.push({ ...product, quantity: 1,
          priority: this.selectedProducts.length + 1 // Assign priority immediately
         });
      },
      removeProduct(product) {
        this.selectedProducts = this.selectedProducts.filter((p) => p.prd_id !== product.prd_id);
      },
      async submitFormula() {
        const { valid } = await this.$refs.form.validate(); // Validate the form

        if (!valid) {
          return; // Stop submission if validation fails
        }

        const formula = {
          nome: this.formulaName,
          codigo: this.formulaCode,
          products: this.selectedProducts,
        };
        console.log("🚀 ~ submitFormula ~ formula:", formula)
        this.$emit('save-formula', formula);
        this.resetDialog();
        this.$emit('update:show', false);
      },
      nextStep() {
        if (this.step < this.items.length) {
          this.step += 1;
        }
      },
      previousStep() {
        if (this.step > 1) {
          this.step -= 1;
        }
      },
      updateShow(value) {
        this.$emit('update:show', value);
      },
      onDragStart() {
        // Optional: Any custom logic when drag starts
      },
      onDragEnd() {
        // After drag ends, update priorities based on the new order
        this.selectedProducts.forEach((product, index) => {
          product.priority = index + 1; // Set priority based on position
        });
      },
      resetDialog() {
        this.step = 1;
        this.selectedProducts = [];
        this.formulaName = '';
        this.formulaCode = '';
        this.formValid = false;
      },
    },
  };
  </script>
  
  <style scoped>
    .v-stepper {
        box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.5);
    }
  </style>
  