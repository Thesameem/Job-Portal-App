// import './assets/main.css'
import './styles/styles.css'
import './styles/findjobs.css'
import './styles/createjob.css'
import './styles/findtalent.css'
import './styles/whyus.css'
import './styles/blog.css'
import './styles/faq.css'
import './styles/auth.css'
import './styles/managejobs.css'
import './styles/settings.css'
import './styles/jobdetails.css'
import './styles/applyjob.css'
import './styles/editjob.css'
import './styles/dashboard.css'
import './styles/reviewjob.css'
import './styles/aboutus.css'
import './styles/contact.css'
import './styles/privacypolicy.css'
import './styles/termsofservice.css'
import './styles/userprofile.css'
import './styles/editprofile.css'
import '@fortawesome/fontawesome-free/css/all.min.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

// Create the Vue app
const app = createApp(App)

// Use plugins
app.use(createPinia())
app.use(router)

// Mount the app
app.mount('#app')
