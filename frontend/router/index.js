import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('../src/components/Home.vue')
    }, {
        path: '/produto/tipo',
        name: 'tipo_produto',
        component: () => import('../src/components/TipoProduto.vue')
    }, {
      path: '/produto',
      name: 'produto',
      component: () => import('../src/components/Produto.vue')
    }, {
      path: '/venda',
      name: 'venda',
      component: () => import('../src/components/Venda.vue')
    }
  ]
})

export default router