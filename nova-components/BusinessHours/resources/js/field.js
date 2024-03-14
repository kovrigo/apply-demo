Nova.booting((Vue, router, store) => {
  Vue.component('index-business-hours', require('./components/IndexField'))
  Vue.component('detail-business-hours', require('./components/DetailField'))
  Vue.component('form-business-hours', require('./components/FormField'))
})
