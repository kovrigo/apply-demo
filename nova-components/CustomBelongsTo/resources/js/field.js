Nova.booting((Vue, router, store) => {
  Vue.component('index-custom-belongs-to', require('./components/IndexField'))
  Vue.component('detail-custom-belongs-to', require('./components/DetailField'))
  Vue.component('form-custom-belongs-to', require('./components/FormField'))
})
