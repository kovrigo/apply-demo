Nova.booting((Vue, router, store) => {
  Vue.component('index-custom-text', require('./components/IndexField'))
  Vue.component('detail-custom-text', require('./components/DetailField'))
  Vue.component('form-custom-text', require('./components/FormField'))
})
