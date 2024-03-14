Nova.booting((Vue, router, store) => {
  Vue.component('index-price-range', require('./components/IndexField'))
  Vue.component('detail-price-range', require('./components/DetailField'))
  Vue.component('form-price-range', require('./components/FormField'))
})
