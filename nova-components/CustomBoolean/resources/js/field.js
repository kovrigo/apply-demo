Nova.booting((Vue, router, store) => {
  Vue.component('index-custom-boolean', require('./components/IndexField'))
  Vue.component('detail-custom-boolean', require('./components/DetailField'))
  Vue.component('form-custom-boolean', require('./components/FormField'))
})
