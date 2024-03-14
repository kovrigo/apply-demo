Nova.booting((Vue, router, store) => {
  Vue.component('index-job-skill-level', require('./components/IndexField'))
  Vue.component('detail-job-skill-level', require('./components/DetailField'))
  Vue.component('form-job-skill-level', require('./components/FormField'))
})
