<template>
  <v-app>
    <!-- Top Navigation Bar -->
    <v-app-bar color="primary" dark app>
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title>JCE Project</v-toolbar-title>
    </v-app-bar>

    <!-- Sidebar Navigation Drawer -->
    <v-navigation-drawer v-model="drawer" app rail expand-on-hover color="grey-lighten-3">
      <v-list>
        <v-list-item prepend-icon="mdi-flask" title="Fórmulas" to="/formulas" router exact></v-list-item>
        <v-list-item prepend-icon="mdi-package-variant" title="Produtos" to="/produtos" router exact></v-list-item>
      </v-list>
    </v-navigation-drawer>

    <!-- Main Content -->
    <v-main>
      <v-container fluid>
        <!-- Show welcome message only on the home route ("/") -->
        <v-row v-if="$route.path === '/'" justify="center">
          <v-col cols="12" md="8" lg="6">
            <v-card class="pa-5 text-center">
              <v-card-title class="text-h5">Bem-vindo ao JCE Project</v-card-title>
              <v-card-subtitle>Gerencie fórmulas e produtos de forma eficiente</v-card-subtitle>
              <v-card-text>
                <p>Escolha uma das opções abaixo para começar:</p>
              </v-card-text>
              <v-row justify="center">
                <v-col cols="12" sm="6">
                  <v-btn block color="primary" to="/formulas">
                    <v-icon left>mdi-flask</v-icon> Fórmulas
                  </v-btn>
                </v-col>
                <v-col cols="12" sm="6">
                  <v-btn block color="secondary" to="/produtos">
                    <v-icon left>mdi-package-variant</v-icon> Produtos
                  </v-btn>
                </v-col>
              </v-row>
            </v-card>
          </v-col>
        </v-row>

        <!-- Always show router-view to load other pages -->
        <router-view></router-view>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
export default {
  name: 'HomeComponent',
  data() {
    return {
      drawer: false, // Controls the navigation drawer visibility
    };
  },
};
</script>

<style scoped>
/* Styling for a clean, modern look */
.v-card {
  background-color: #f5f5f5;
  border-radius: 12px;
}
</style>
