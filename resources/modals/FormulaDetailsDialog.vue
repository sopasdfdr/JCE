<template>
    <v-dialog
      :model-value="show"
      @update:model-value="updateShow"
      max-width="700px"
    >
      <v-card>
        <v-card-title>
          <span class="headline">{{ formula.fml_nome }}</span>
        </v-card-title>
        <v-card-subtitle>Código: {{ formula.fml_codigo }}</v-card-subtitle>
        <v-card-text>
  <p><strong>Quantidade Total:</strong> {{ formula.fml_qtde }} Kg</p>
  <p><strong>Número de Produtos:</strong> {{ formula.fml_nrprodutos }}</p>

  <v-row>
    <v-col cols="6">
      <p><strong>Data de criação:</strong> {{ formatDate(formula.fml_datacriacao) }}</p>
    </v-col>
    <v-col cols="6" class="text-right">
      <p><strong>Última atualização:</strong> {{ formatDate(formula.fml_dataalteracao) }}</p>
    </v-col>
  </v-row>

  <v-divider class="mb-4 mt-4"></v-divider>

  <h3>Produtos associados por ordem de prioridade</h3>
  <v-list>
    <v-list-item-group v-if="formula.formulacoes.length">
      <v-list-item v-for="product in formula.formulacoes" :key="product.produto.prd_id">
        <v-list-item-content>
          <v-row align="center">
            <v-col cols="8">
              <v-list-item-title>
                {{ product.produto.prd_nome }} - {{ productTypeTranslate(product.produto.prd_tipo) }}
              </v-list-item-title>
            </v-col>
            <v-col cols="4" class="text-right">
              <v-chip color="primary" small>{{ product.fmc_qtde }} Kg</v-chip>
            </v-col>
          </v-row>
          <v-list-item-subtitle>{{ product.produto.prd_codigo }}</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>
    </v-list-item-group>
    <v-list-item v-else>
      <v-list-item-content>
        <v-list-item-title>No products associated</v-list-item-title>
      </v-list-item-content>
    </v-list-item>
  </v-list>
</v-card-text>

        <v-card-actions>
          <v-btn text @click="closeDialog">Close</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </template>
  
  <script>
  import axios from 'axios';
  export default {
    props: {
      show: Boolean,
      formulaId: Number,
    },
    data() {
      return {
        formula: {
          formulacoes: [],
        },
      };
    },
    watch: {
      show(newVal) {
        if (newVal && this.formulaId) {
          this.fetchFormulaDetails(this.formulaId);
        }
      },
    },
    methods: {
      formatDate(seconds) {
        if (!seconds) return "N/A";
        const date = new Date(seconds * 1000);
        const day = String(date.getDate()).padStart(2, "0");
        const month = String(date.getMonth() + 1).padStart(2, "0");
        const year = date.getFullYear();
        const hours = String(date.getHours()).padStart(2, "0");
        const minutes = String(date.getMinutes()).padStart(2, "0");
        return `${day}-${month}-${year} ${hours}:${minutes}`;
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
      async fetchFormulaDetails(id) {
        try {
          const response = await axios.get(`/api/formulas/${id}/details`);
          this.formula = response.data;
          this.formula.formulacoes.sort((a, b) => a.fmc_prioridade - b.fmc_prioridade);
          console.log("🚀 ~ fetchFormulaDetails ~ this.formula.formulacoes:", this.formula.formulacoes)
          console.log(this.formula); // Debugging
        } catch (error) {
          console.error('Error fetching formula details:', error);
        }
      },
      closeDialog() {
        this.$emit('update:show', false);
      },
      updateShow(value) {
        this.$emit('update:show', value);
      },
    },
  };
  </script>
