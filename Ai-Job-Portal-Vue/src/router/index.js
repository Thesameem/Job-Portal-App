import { createRouter, createWebHistory } from 'vue-router'
import Home from '@/views/Home.vue'
import FindJob from '@/views/FindJob.vue'
import CreateJob from '@/views/CreateJob.vue'
import WhyUs from '@/views/WhyUs.vue'
import FindTalent from '@/views/FindTalent.vue'
import Blog from '@/views/Blog.vue'
import Auth from '@/views/Auth.vue'
import Faq from '@/views/Faq.vue'
import ManageJob from '@/components/ProfileHoverPage/ManageJob.vue'
import Setting from '@/components/ProfileHoverPage/Setting.vue'
import JobDetails from '@/components/Jobs/JobDetails.vue'
import ApplyJob from '@/components/Jobs/ApplyJob.vue'
import EditJob from '@/components/Jobs/EditJob.vue'
import ReviewJob from '@/components/Jobs/ReviewJob.vue'
import AboutUs from '@/components/QuickLinks/AboutUs.vue'
import PrivacyPolicy from '@/components/QuickLinks/PrivacyPolicy.vue'
import ContactUs from '@/components/QuickLinks/ContactUs.vue'
import UserProfile from '@/components/User/UserProfile.vue'
import EditProfile from '@/components/User/EditProfile.vue'
import Cookie from '@/scripts/Cookie'
import TermsOfServive from '@/components/QuickLinks/TermsOfServive.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home,
    },
    {
      path: '/findjob',
      name: 'findjob',
      component: FindJob,
    },
    {
      path: '/createjob',
      name: 'createjob',
      component: CreateJob,
      meta: { requiresAuth: true }
    },
    {
      path: '/findtalent',
      name: 'findtalent',
      component: FindTalent,
    },
    {
      path: '/whyus',
      name: 'whyus',
      component: WhyUs,
    },
    {
      path: '/blog',
      name: 'blog',
      component: Blog,
    },
    {
      path: '/auth',
      name: 'auth',
      component: Auth,
      meta: { guestOnly: true }
    },
    {
      path: '/managejob',
      name: 'managejob',
      component: ManageJob,
      meta: { requiresAuth: true }
    },
    {
      path: '/settings',
      name: 'setting',
      component: Setting,
      meta: { requiresAuth: true }
    },
    {
      path: '/jobdetails',
      name: 'jobdetails',
      component: JobDetails,
    },
    {
      path: '/applyjob',
      name: 'applyjob',
      component: ApplyJob,
      meta: { requiresAuth: true }
    },
    {
      path: '/editjob',
      name: 'editjob',
      component: EditJob,
      meta: { requiresAuth: true }
    },
    {
      path: '/reviewjob',
      name: 'review',
      component: ReviewJob,
      meta: { requiresAuth: true }
    },
    {
      path: '/about',
      name: 'about',
      component: AboutUs,
    },
    {
      path: '/privacypolicy',
      name: 'privacypolicy',
      component: PrivacyPolicy,
    },
    {
      path: '/contact',
      name: 'contact',
      component: ContactUs,
    },
    {
      path: '/userprofile',
      name: 'userprofile',
      component: UserProfile,
      meta: { requiresAuth: true }
    },
    {
      path: '/editprofile',
      name: 'editprofile',
      component: EditProfile,
      meta: { requiresAuth: true }
    },
    {
      path: '/faq',
      name: 'faq',
      component: Faq,
    },
    {
      path: '/terms',
      name: 'terms',
      component: TermsOfServive,
    },
    {
      // Catch all route for 404s
      path: '/:pathMatch(.*)*',
      redirect: { name: 'home' }
    }
  ],
  // Add scroll behavior configuration
  scrollBehavior(to, from, savedPosition) {
    // If the user used browser back/forward buttons and has a saved position
    if (savedPosition) {
      return savedPosition;
    }
    
    // Check if the route has a hash (anchor)
    if (to.hash) {
      return {
        el: to.hash,
        behavior: 'smooth',
      }
    }
    
    // For all other navigation, scroll to top
    return { top: 0 }
  }
})

// Navigation guard to check authentication status
router.beforeEach((to, from, next) => {
  const token = Cookie.getCookie('job-app')
  const isAuthenticated = !!token
  
  // Handle routes requiring authentication
  if (to.meta.requiresAuth && !isAuthenticated) {
    // If route requires auth and user is not authenticated, redirect to login
    next({ 
      name: 'auth', 
      query: { redirect: to.fullPath }  // Store the intended destination
    })
  } 
  // Handle guest-only routes (like login/register) when user is already authenticated
  else if (to.meta.guestOnly && isAuthenticated) {
    // If route is for guests only and user is authenticated, redirect to home
    next({ name: 'home' })
  } 
  else {
    // Otherwise proceed as normal
    next()
  }
})

export default router
