import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap"
import { createApp, h } from 'vue'
import { createInertiaApp, Link } from '@inertiajs/vue3'
import Layout from './Shared/Layout.vue'

createInertiaApp({
  resolve: name => {

    name = name.replace(/\./g, '/');
    console.log(name);

    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    let page = pages[`./Pages/${name}.vue`]

    page.default.layout = page.default.layout || Layout
    
    return page
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .component('Link', Link)
      .mount(el)
  },
  progress: {
    color: 'red',
    showSpinner: true
  }
})
