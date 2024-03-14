import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
Nova.booting((Vue, router, store) => {
  Vue.component('index-custom-json', require('./components/IndexField'))
  Vue.component('detail-custom-json', require('./components/DetailField'))
  Vue.component('form-custom-json', require('./components/FormField'))
  Vue.use(ElementUI);
})
