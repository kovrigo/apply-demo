Nova.booting((Vue, router, store) => {
  Vue.component('index-custom-morph-to', require('./components/IndexField'))
  Vue.component('detail-custom-morph-to', require('./components/DetailField'))
  Vue.component('form-custom-morph-to', require('./components/FormField'))
})
