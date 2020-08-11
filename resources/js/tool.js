Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'mail-testing',
      path: '/mail-testing',
      component: require('./components/Tool'),
    },
    // {
    //     name: 'mail-testing-send',
    //     path: '/mail-testing/send-mail',
    //     component: require('./components/SendMail'),
    //   },
  ]);
})
