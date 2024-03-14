import CustomNavigation from './components/CustomNavigation';

Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'custom-navigation',
      path: '/custom-navigation',
      component: require('./components/Tool'),
    },
  ])
})

Nova.booting((Vue, router, store) => {
    Vue.component('custom-navigation', CustomNavigation)
});