<template>
  <!-- Navigation Bar -->
  <nav class="navbar" role="navigation" aria-label="Main navigation">
    <div class="logo">
      <RouterLink to="/" aria-label="Ai-Job Home">
        <h1>SRB</h1>
      </RouterLink>
    </div>
    <button class="hamburger" aria-label="Toggle navigation menu" aria-expanded="false">
      <span class="hamburger-line"></span>
      <span class="hamburger-line"></span>
      <span class="hamburger-line"></span>
    </button>
    <div class="nav-container">
      <div class="nav-links">
        <RouterLink to="/" class="active" aria-current="page">Home</RouterLink>
        <RouterLink to="/findjob">Find Work</RouterLink>
        <RouterLink to="/createjob">Post a Job</RouterLink>
        <RouterLink to="/findtalent">Find Talent</RouterLink>
        <RouterLink to="/whyus">Why Us</RouterLink>
        <RouterLink to="/blog">Blog</RouterLink>
        <RouterLink to="/faq">FAQ</RouterLink>
      </div>
      <div class="auth-buttons">
        <!-- Show profile dropdown when authenticated -->
        <div v-if="isAuthenticated" class="profile-dropdown">
          <a href="/userprofile" class="profile-badge">
            <img src="./../../images/myphoto.JPG" alt="Profile Picture" />
            <span>{{ userName }}</span>
          </a>
          <div class="dropdown-content">
            <RouterLink to="/managejob"><i class="fas fa-briefcase"></i> Manage Jobs</RouterLink>
            <RouterLink to="/settings"><i class="fas fa-cog"></i> Settings</RouterLink>
            <a href="#" @click.prevent="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
          </div>
        </div>
        <!-- Show Get Started button when not authenticated -->
        <div v-else>
          <RouterLink to="/auth" class="get-started-btn">Get Started</RouterLink>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Cookie from '@/scripts/Cookie'
import { useJobStore } from '@/stores/job'

const router = useRouter()
const jobStore = useJobStore()
const isAuthenticated = ref(false)

// Update authentication status
const checkAuth = () => {
  const token = Cookie.getCookie('job-app')
  const hasUser = !!jobStore.user && Object.keys(jobStore.user).length > 0
  
  // Update authentication state
  isAuthenticated.value = !!token && hasUser
  
  // Clear user data if no token exists
  if (!token && Object.keys(jobStore.user).length > 0) {
    jobStore.user = {}
  }
}

// Computed property to get authenticated user's name
const userName = computed(() => {
  return jobStore.user?.fullname || 'User'
})

// Check authentication when component mounts
onMounted(() => {
  // Initial check
  checkAuth()
  
  // Set up simple polling to check auth status
  setInterval(checkAuth, 2000)
})

// Logout function
const logout = () => {
  // Clear the auth token cookie
  Cookie.setCookie('job-app', '', 0) // Setting expiry to 0 removes the cookie
  
  // Clear user data from store
  jobStore.user = {}
  
  // Update auth state
  isAuthenticated.value = false
  
  // Redirect to the auth page
  router.push('/auth')
}
</script>

<style scoped>
/* Main navbar container */
.navbar {
  display: flex;
  align-items: center;
  padding: 0 24px;
}

/* Container for navigation elements */
.nav-container {
  display: flex;
  width: 100%;
  justify-content: space-between;
  align-items: center;
}

/* Nav links styling */
.nav-links {
  display: flex;
  justify-content: center;
  flex: 1;
  gap: 32px; /* Increased spacing between links */
  margin: 0 48px; /* Add margin to push links toward center */
}

.nav-links a {
  text-decoration: none;
  color: #333;
  font-weight: 500;
  padding: 8px 4px;
  transition: color 0.3s;
}

.nav-links a:hover,
.nav-links a.active {
  color: #0d6efd;
}

/* Auth buttons section */
.auth-buttons {
  margin-left: 48px; /* Increase space between nav links and auth button */
  display: flex;
  align-items: center;
}

/* Profile dropdown styling */
.profile-dropdown {
  position: relative;
}

.profile-badge {
  display: flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
  color: #333;
}

.profile-badge img {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  object-fit: cover;
}

.dropdown-content {
  display: none;
  position: absolute;
  right: 0;
  min-width: 180px;
  background-color: white;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  padding: 8px 0;
  z-index: 1;
}

.dropdown-content a {
  display: block;
  padding: 10px 16px;
  text-decoration: none;
  color: #333;
  transition: background-color 0.3s;
}

.dropdown-content a:hover {
  background-color: #f5f5f5;
}

.profile-dropdown:hover .dropdown-content {
  display: block;
}

/* Get Started button styling */
.get-started-btn {
  padding: 10px 24px;
  background-color: #0d6efd;
  color: white;
  border-radius: 4px;
  text-decoration: none;
  font-weight: 500;
  font-size: 16px;
  letter-spacing: 0.5px;
  transition: all 0.3s ease;
  display: inline-block;
}

.get-started-btn:hover {
  background-color: #0b5ed7;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
</style>
