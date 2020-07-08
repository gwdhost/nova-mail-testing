Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'mail-testing',
      path: '/mail-testing',
      component: require('./components/Tool'),
    },
  ])
})
