Nova.booting((Vue, router, store) => {
  Vue.component('index-action-validation-error', require('./components/IndexField'))
  Vue.component('detail-action-validation-error', require('./components/DetailField'))
  Vue.component('form-action-validation-error', require('./components/FormField'))
})
