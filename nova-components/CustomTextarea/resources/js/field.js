Nova.booting((Vue, router, store) => {
  Vue.component('index-custom-textarea', require('./components/IndexField'))
  Vue.component('detail-custom-textarea', require('./components/DetailField'))
  Vue.component('form-custom-textarea', require('./components/FormField'))
})
