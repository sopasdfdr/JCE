import { createRouter, createWebHistory } from 'vue-router';
import HomeComponent from '../components/HomeComponent.vue';
import FormulasPage from '../components/FormulasPage.vue';
import ProdutosPage from '../components/ProdutosPage.vue';

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeComponent,
    children: [
      {
        path: 'formulas',
        name: 'formulas',
        component: FormulasPage,
      },
      {
        path: 'produtos',
        name: 'produtos',
        component: ProdutosPage,
      },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
