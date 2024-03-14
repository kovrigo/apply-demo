Nova.booting((Vue, router, store) => {
  Vue.component('index-custom-trix', require('./components/IndexField'))
  Vue.component('detail-custom-trix', require('./components/DetailField'))
  Vue.component('form-custom-trix', require('./components/FormField'))
})
