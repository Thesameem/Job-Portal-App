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
    },
    {
      path: '/managejob',
      name: 'managejob',
      component: ManageJob,
    },
    {
      path: '/settings',
      name: 'setting',
      component: Setting,
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
    },
    {
      path: '/editjob',
      name: 'editjob',
      component: EditJob,
    },
    {
      path: '/reviewjob',
      name: 'review',
      component: ReviewJob,
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
    },
    {
      path: '/editprofile',
      name: 'editprofile',
      component: EditProfile,
    },
    {
      path: '/faq',
      name: 'faq',
      component: Faq,
    },
  ],
})

export default router
