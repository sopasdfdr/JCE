<template>
    <v-dialog :model-value="show" @update:model-value="$emit('update:show', $event)" @click:outside="resetDialog" max-width="800px">
      <v-card>
        <v-card-title>
          <span class="text-h5">Editar Fórmula</span>
        </v-card-title>
  
        <v-card-text>
          <v-stepper v-model="step" :items="items" show-actions>
            <template v-slot:item.1>
              <v-sheet border>
                <v-table>
                  <thead>
                    <tr>
                      <th>Descrição</th>
                      <th>Ação</th>
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
  
            <template v-slot:item.2>
              <v-list v-if="selectedProducts.length" dense>
                <draggable v-model="selectedProducts" :options="{ handle: '.drag-handle' }" @start="onDragStart" @end="onDragEnd">
                  <template #item="{ element, index }">
                    <v-list-item>
                      <v-row align="center" no-gutters>
                        <v-col cols="4">
                            <span class="drag-handle" style="cursor: move;">{{ element.prd_nome }}</span>
                        </v-col>
                        <v-col cols="4" class="mr-4 text-center">
                          <v-text-field v-model="element.quantity" type="number" min="1" dense outlined label="Quantidade (Kg)"></v-text-field>
                        </v-col>
                        <v-col cols="3" class="ml-4 text-center">
                          <v-btn icon  v-if="selectedProducts.length > 1" color="red" density="compact" @click="removeProduct(element)">
                            <v-icon>mdi-delete</v-icon>
                          </v-btn>
                        </v-col>
                      </v-row>
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
                <v-btn color="green" @click="submitFormula" v-if="step === items.length" class="ml-auto mb-1 ml-1" >
                Update
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
    components: { draggable },
    props: {
      show: Boolean,
      formula: Object,
      products: Array,
    },
    emits: ['update:show', 'update-formula'],
    data() {
      return {
        step: 1,
        selectedProducts: [],
        formulaName: '',
        formulaCode: '',
        formValid: false,
        items: ['Selecionar Produtos', 'Definir Quantidade e Prioridade', 'Informações da Fórmula'],
      };
    },
    computed: {
      availableProducts() {
        return this.products.filter((product) => !this.selectedProducts.some((selected) => selected.prd_id === product.prd_id));
        
      },
    },
    watch: {
  formula: {
    immediate: true,
    handler(newFormula) {
      if (newFormula) {
        this.formulaName = newFormula.fml_nome;
        this.formulaCode = newFormula.fml_codigo;
        this.selectedProducts = newFormula.formulacoes.map((formulacao) => ({
          ...formulacao.produto,
          quantity: formulacao.fmc_qtde,
          priority: formulacao.fmc_prioridade,
        }));

        // Sort by priority after mapping
        this.selectedProducts.sort((a, b) => a.priority - b.priority);
      }
    },
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
        const updatedFormula = {
          ...this.formula,
          fml_nome: this.formulaName,
          fml_codigo: this.formulaCode,
          formulacoes: this.selectedProducts.map((product) => ({
            produto: product,
            fmc_qtde: product.quantity,
            fmc_prioridade: product.priority,
          })),
        };
        this.$emit('update-formula', updatedFormula);
        this.resetDialog();
        this.$emit('update:show', false);
      },
      nextStep() {
        if (this.step < this.items.length) this.step += 1;
      },
      previousStep() {
        if (this.step > 1) this.step -= 1;
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