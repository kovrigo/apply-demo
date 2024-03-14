Nova.booting((Vue, router, store) => {
  Vue.component('index-profile-skill-level', require('./components/IndexField'))
  Vue.component('detail-profile-skill-level', require('./components/DetailField'))
  Vue.component('form-profile-skill-level', require('./components/FormField'))
})
