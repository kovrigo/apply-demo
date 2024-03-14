Nova.booting((Vue, router, store) => {
  Vue.component('index-price-calculator', require('./components/IndexField'))
  Vue.component('detail-price-calculator', require('./components/DetailField'))
  Vue.component('form-price-calculator', require('./components/FormField'))
})
