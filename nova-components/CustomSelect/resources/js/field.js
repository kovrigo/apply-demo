Nova.booting((Vue, router, store) => {
  Vue.component('index-custom-select', require('./components/IndexField'))
  Vue.component('detail-custom-select', require('./components/DetailField'))
  Vue.component('form-custom-select', require('./components/FormField'))
})
