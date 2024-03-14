Nova.booting((Vue, router, store) => {
  Vue.component('index-custom-number', require('./components/IndexField'))
  Vue.component('detail-custom-number', require('./components/DetailField'))
  Vue.component('form-custom-number', require('./components/FormField'))
})
