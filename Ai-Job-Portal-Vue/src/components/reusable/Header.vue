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
    <div class="nav-container" :class="{ 'centered-nav': !isAuthenticated }">
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
        <!-- Show login/signup buttons when not authenticated -->
        <div v-else class="login-signup-buttons">
          <RouterLink to="/auth" class="login-btn">Log In</RouterLink>
          <RouterLink to="/auth" class="signup-btn">Sign Up</RouterLink>
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

// Computed property to check if user is authenticated
const isAuthenticated = computed(() => {
  return !!Cookie.getCookie('job-app') && !!jobStore.user && Object.keys(jobStore.user).length > 0
})

// Computed property to get authenticated user's name
const userName = computed(() => {
  return jobStore.user?.fullname || 'User'
})

// Check authentication on component mount
onMounted(() => {
  checkAuth()
})

// Function to check authentication status
const checkAuth = () => {
  const token = Cookie.getCookie('job-app')
  // If there's no token but user data exists in store, clear it
  if (!token && Object.keys(jobStore.user).length > 0) {
    jobStore.user = {}
  }
}

// Logout function
const logout = () => {
  // Clear the auth token cookie
  Cookie.setCookie('job-app', '', 0) // Setting expiry to 0 removes the cookie
  
  // Clear user data from store
  jobStore.user = {}
  
  // Redirect to the auth page
  router.push('/auth')
}
</script>

<style scoped>
/* Centering nav-links when user is not authenticated */
.centered-nav .nav-links {
  margin: 0 auto;
}

/* Style adjustments for login/signup buttons */
.login-signup-buttons {
  display: flex;
  gap: 10px;
  align-items: center;
}

.login-btn, .signup-btn {
  padding: 8px 16px;
  border-radius: 4px;
  text-decoration: none;
  font-weight: 500;
  transition: background-color 0.3s, color 0.3s;
}

.login-btn {
  color: #0d6efd;
  border: 1px solid #0d6efd;
}

.login-btn:hover {
  background-color: #f0f7ff;
}

.signup-btn {
  background-color: #0d6efd;
  color: white;
  border: 1px solid #0d6efd;
}

.signup-btn:hover {
  background-color: #0b5ed7;
}
</style>
