<template>
  <!-- Navigation Bar -->
  <nav class="navbar" role="navigation" aria-label="Main navigation">
    <div class="logo">
      <RouterLink to="/" aria-label="Ai-Job Home">
        <h1>SRB</h1>
      </RouterLink>
    </div>
    <button 
      class="hamburger" 
      aria-label="Toggle navigation menu" 
      :aria-expanded="isMobileMenuOpen" 
      @click="toggleMobileMenu"
      :class="{ 'is-active': isMobileMenuOpen }"
    >
      <span class="hamburger-line"></span>
      <span class="hamburger-line"></span>
      <span class="hamburger-line"></span>
    </button>
    
    <!-- Mobile menu backdrop/overlay -->
    <div 
      class="mobile-backdrop" 
      v-if="isMobileMenuOpen" 
      @click="closeMobileMenu"
    ></div>
    
    <div class="nav-container" :class="{ 'mobile-open': isMobileMenuOpen }">
      <div class="mobile-menu-header">
        <h2>Menu</h2>
        <button class="close-menu-btn" @click="closeMobileMenu">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="nav-links">
        <RouterLink to="/" class="active" aria-current="page" @click="closeMobileMenu">
          <i class="fas fa-home mobile-only-icon"></i> Home
        </RouterLink>
        <RouterLink to="/findjob" @click="closeMobileMenu">
          <i class="fas fa-search mobile-only-icon"></i> Find Work
        </RouterLink>
        <RouterLink to="/createjob" @click="closeMobileMenu">
          <i class="fas fa-plus-circle mobile-only-icon"></i> Post a Job
        </RouterLink>
        <RouterLink to="/findtalent" @click="closeMobileMenu">
          <i class="fas fa-users mobile-only-icon"></i> Find Talent
        </RouterLink>
        <RouterLink to="/whyus" @click="closeMobileMenu">
          <i class="fas fa-question-circle mobile-only-icon"></i> Why Us
        </RouterLink>
        <RouterLink to="/blog" @click="closeMobileMenu">
          <i class="fas fa-newspaper mobile-only-icon"></i> Blog
        </RouterLink>
        <RouterLink to="/faq" @click="closeMobileMenu">
          <i class="fas fa-info-circle mobile-only-icon"></i> FAQ
        </RouterLink>
      </div>
      <div class="auth-buttons">
        <!-- Show profile dropdown when authenticated -->
        <div v-if="isAuthenticated" 
             class="profile-dropdown" 
             @mouseenter="showDropdown = true" 
             @mouseleave="showDropdown = false">
          <div class="profile-badge-wrapper" @click="toggleDropdown">
            <RouterLink to="/userprofile" class="profile-badge" @click.stop="closeMobileMenu">
              <div v-if="profilePhotoUrl" class="profile-photo">
                <img :src="profilePhotoUrl" alt="Profile Picture" @error="handleProfileImageError" />
              </div>
              <div v-else class="profile-initial">
                {{ userName.charAt(0).toUpperCase() }}
              </div>
              <span>{{ userName }}</span>
            </RouterLink>
            <span class="dropdown-arrow"><i class="fas fa-chevron-down"></i></span>
          </div>
          <div class="dropdown-content" :class="{ 'show': showDropdown || isDropdownOpen }">
            <RouterLink to="/userprofile" @click="closeDropdown"><i class="fas fa-user"></i> My Profile</RouterLink>
            <RouterLink to="/managejob" @click="closeDropdown"><i class="fas fa-briefcase"></i> Manage Jobs</RouterLink>
            <RouterLink to="/settings" @click="closeDropdown"><i class="fas fa-cog"></i> Settings</RouterLink>
            <a href="#" @click.prevent="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
          </div>
        </div>
        <!-- Show Get Started button when not authenticated -->
        <div v-else>
          <RouterLink to="/auth" class="get-started-btn" @click="closeMobileMenu">Get Started</RouterLink>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed, onMounted, watch, nextTick } from 'vue'
import { useRouter } from 'vue-router'
import Cookie from '@/scripts/Cookie'
import { useJobStore } from '@/stores/job'
import { useToast } from '@/scripts/toast'

const router = useRouter()
const jobStore = useJobStore()
const toast = useToast()
const isAuthenticated = ref(false)
const isInitialLoad = ref(true)
const profileImageError = ref(false)
const isMobileMenuOpen = ref(false)
const showDropdown = ref(false)
const isDropdownOpen = ref(false)

// Toggle mobile menu
const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
  // Add overflow hidden to body when menu is open to prevent scrolling
  if (isMobileMenuOpen.value) {
    document.body.style.overflow = 'hidden'
  } else {
    document.body.style.overflow = ''
  }
}

// Close mobile menu when a link is clicked
const closeMobileMenu = () => {
  if (isMobileMenuOpen.value) {
    isMobileMenuOpen.value = false
    document.body.style.overflow = ''
  }
}

// Close mobile menu when window is resized to desktop size
const handleResize = () => {
  if (window.innerWidth > 768 && isMobileMenuOpen.value) {
    isMobileMenuOpen.value = false
    document.body.style.overflow = ''
  }
}

// Try to restore user from localStorage
const restoreUserFromStorage = () => {
  const token = Cookie.getCookie('job-app')
  
  if (token && (!jobStore.user || Object.keys(jobStore.user).length === 0)) {
    try {
      const savedUser = localStorage.getItem('job-user')
      if (savedUser) {
        jobStore.user = JSON.parse(savedUser)
        isAuthenticated.value = true
        console.log('Restored user from localStorage:', jobStore.user)
        
        // Also restore/fetch profile data
        jobStore.initializeProfile()
        return true
      }
    } catch (error) {
      console.error('Error restoring user from storage:', error)
    }
  }
  return false
}

// Update authentication status
const checkAuth = () => {
  const token = Cookie.getCookie('job-app')
  const hasUser = !!jobStore.user && Object.keys(jobStore.user).length > 0
  
  // Update authentication state
  const newAuthState = !!token && hasUser
  
  console.log('Checking auth state:', { 
    token: !!token, 
    hasUser, 
    currentAuthState: isAuthenticated.value,
    newAuthState
  })
  
  // Only update if there's a change to prevent unnecessary re-renders
  if (isAuthenticated.value !== newAuthState || isInitialLoad.value) {
    console.log('Auth state changed:', newAuthState)
    isAuthenticated.value = newAuthState
    isInitialLoad.value = false
    
    // Clear user data if no token exists
    if (!token && Object.keys(jobStore.user).length > 0) {
      jobStore.user = {}
      jobStore.clearProfile()
      localStorage.removeItem('job-user')
    }
    
    // If we just became authenticated, save user to localStorage and fetch profile
    if (newAuthState) {
      localStorage.setItem('job-user', JSON.stringify(jobStore.user))
      
      // Initialize profile data
      jobStore.initializeProfile()
    }
  }
}

// Computed property to get authenticated user's name
const userName = computed(() => {
  return jobStore.user?.fullname || 'User'
})

// Computed property to get profile photo URL
const profilePhotoUrl = computed(() => {
  // Just use the basic profile photo URL getter from the store
  return jobStore.profilePhotoUrl;
})

// Handle profile image loading error
const handleProfileImageError = () => {
  console.error('Failed to load profile image from:', profilePhotoUrl.value)
  profileImageError.value = true
}

// Watch for changes in the user object to update the auth state immediately
watch(() => jobStore.user, (newVal, oldVal) => {
  console.log('User object changed:', { 
    hasNewUser: newVal && Object.keys(newVal).length > 0,
    hasOldUser: oldVal && Object.keys(oldVal).length > 0 
  })
  
  if (newVal && Object.keys(newVal).length > 0) {
    // User data is available, update localStorage and auth state
    localStorage.setItem('job-user', JSON.stringify(newVal))
    
    // Update auth state immediately
    nextTick(() => {
      isAuthenticated.value = true
      console.log('Auth state updated by watcher:', isAuthenticated.value)
      
      // Initialize profile when user changes
      jobStore.initializeProfile()
    })
  } else if (oldVal && Object.keys(oldVal).length > 0 && (!newVal || Object.keys(newVal).length === 0)) {
    // User was logged out
    isAuthenticated.value = false
    console.log('User logged out, auth state updated by watcher:', isAuthenticated.value)
  }
}, { deep: true, immediate: true })

// Also watch the cookie directly
const watchCookie = () => {
  const token = Cookie.getCookie('job-app')
  if (token && jobStore.user && Object.keys(jobStore.user).length > 0 && !isAuthenticated.value) {
    isAuthenticated.value = true
    console.log('Auth state updated by cookie watcher:', isAuthenticated.value)
    
    // Initialize profile when authentication status changes
    jobStore.initializeProfile()
  } else if (!token && isAuthenticated.value) {
    isAuthenticated.value = false
    console.log('Cookie removed, auth state updated by watcher:', isAuthenticated.value)
  }
}

// Toggle dropdown on click (for mobile)
const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value;
  if (window.innerWidth <= 768) {
    // On mobile, clicking the badge shouldn't navigate to profile page
    // but toggle the dropdown instead
    event?.preventDefault();
  }
}

// Close dropdown after clicking a link
const closeDropdown = () => {
  showDropdown.value = false;
  isDropdownOpen.value = false;
  closeMobileMenu();
}

// Handle click outside to close dropdown
const handleClickOutside = (event) => {
  const dropdown = document.querySelector('.profile-dropdown');
  if (dropdown && !dropdown.contains(event.target) && (showDropdown.value || isDropdownOpen.value)) {
    showDropdown.value = false;
    isDropdownOpen.value = false;
  }
}

// Check authentication when component mounts
onMounted(() => {
  // First try to restore from localStorage
  if (!restoreUserFromStorage()) {
    // If restoration failed, perform regular check
    checkAuth()
  }
  
  // Set up polling for cookie changes
  setInterval(watchCookie, 1000) // Check more frequently (every second)
  
  // Set up polling to check auth status (less frequent)
  setInterval(checkAuth, 30000) // Check every 30 seconds
  
  // Initial check after a brief delay to allow store to update
  setTimeout(checkAuth, 200)
  
  // Add window resize event listener
  window.addEventListener('resize', handleResize)
  
  // Add event listeners
  document.addEventListener('click', handleClickOutside)
})

// Force check auth method that can be called from other components
const forceCheckAuth = () => {
  checkAuth()
  return isAuthenticated.value
}

// Make this method available globally
window.checkAuthNow = forceCheckAuth

// Logout function
const logout = async () => {
  try {
    // Get username before logout for the toast message
    const name = jobStore.user?.fullname || 'User'
    
    // Clear the auth token cookie
    Cookie.setCookie('job-app', '', 0) // Setting expiry to 0 removes the cookie
    
    // Clear user data from store and localStorage
    jobStore.user = {}
    jobStore.clearProfile()
    localStorage.removeItem('job-user')
    
    // Update auth state
    isAuthenticated.value = false
    
    // Close mobile menu if open
    closeMobileMenu()
    
    // Redirect to the auth page - using replace to prevent back navigation to authenticated pages
    await router.replace({ name: 'auth', query: { logout: 'success' } })
    
    // Show logout toast notification after navigation is complete
    setTimeout(() => {
      toast.info(`Goodbye, ${name}! You have been logged out.`)
    }, 500)
    
    // Force a page reload to ensure all components update their authentication state
    if (window.location.pathname === '/auth') {
      setTimeout(() => {
        window.location.reload()
      }, 100)
    }
  } catch (error) {
    console.error('Error during logout:', error)
    toast.error('There was a problem logging out. Please try again.')
  }
}
</script>

<style scoped>
/* Main navbar container */
.navbar {
  display: flex;
  align-items: center;
  padding: 0 24px;
  position: relative;
}

/* Logo styling */
.logo {
  margin-right: 16px;
}

.logo h1 {
  margin: 0;
  font-size: 24px;
  color: #636ae8; /* Changed to match theme color */
}

/* Hamburger button */
.hamburger {
  display: none;
  background: none;
  border: none;
  cursor: pointer;
  padding: 10px;
  position: relative;
  z-index: 20;
}

.hamburger-line {
  display: block;
  width: 24px;
  height: 3px;
  margin: 5px 0;
  background-color: #636ae8; /* Changed to theme color */
  transition: all 0.3s ease;
  border-radius: 3px;
}

.hamburger.is-active .hamburger-line:nth-child(1) {
  transform: translateY(8px) rotate(45deg);
  background-color: #636ae8; /* Changed to theme color */
}

.hamburger.is-active .hamburger-line:nth-child(2) {
  opacity: 0;
}

.hamburger.is-active .hamburger-line:nth-child(3) {
  transform: translateY(-8px) rotate(-45deg);
  background-color: #636ae8; /* Changed to theme color */
}

/* Mobile menu backdrop */
.mobile-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 15;
  backdrop-filter: blur(2px);
}

/* Mobile menu header */
.mobile-menu-header {
  display: none;
  justify-content: space-between;
  align-items: center;
  padding: 16px 20px;
  border-bottom: 1px solid #e8e9f6; /* Lighter shade of theme color */
  margin-bottom: 0;
  background-color: #f5f6ff; /* Very light theme background */
  border-top-left-radius: 10px;
}

.mobile-menu-header h2 {
  margin: 0;
  font-size: 20px;
  color: #636ae8; /* Changed to theme color */
  font-weight: 600;
}

.close-menu-btn {
  background: none;
  border: none;
  font-size: 24px;
  color: #636ae8; /* Changed to theme color */
  cursor: pointer;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  transition: background-color 0.2s ease;
}

.close-menu-btn:hover {
  color: #636ae8; /* Changed to theme color */
  background-color: rgba(99, 106, 232, 0.1);
}

/* Container for navigation elements */
.nav-container {
  display: flex;
  width: 100%;
  justify-content: space-between;
  align-items: center;
}

/* Nav links styling for desktop */
.nav-links {
  display: flex;
  justify-content: center;
  flex: 1;
  gap: 32px; /* Increased spacing between links */
  margin: 0 48px; /* Add margin to push links toward center */
}

.nav-links a {
  text-decoration: none;
  color: #555; /* Lighter color for better appearance */
  font-weight: 400; /* Lighter font weight */
  padding: 8px 4px;
  transition: all 0.3s ease;
  letter-spacing: 0.2px; /* Slight letter spacing for smoother look */
}

.nav-links a:hover,
.nav-links a.active {
  color: #636ae8; /* Changed to theme color */
}

/* Hide icons on desktop view */
.mobile-only-icon {
  display: none; 
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
  /* padding-bottom: 20px; Add padding to prevent dropdown from disappearing */
}

.profile-badge-wrapper {
  display: flex;
  align-items: center;
  cursor: pointer;
  padding: 5px;
  border-radius: 4px;
  transition: background-color 0.2s ease;
}

.profile-badge-wrapper:hover {
  background-color: rgba(13, 110, 253, 0.05);
}

.dropdown-arrow {
  margin-left: 5px;
  color: #777;
  font-size: 12px;
  transition: transform 0.2s ease;
}

.profile-dropdown:hover .dropdown-arrow,
.profile-dropdown.active .dropdown-arrow {
  transform: rotate(180deg);
}

.profile-badge {
  display: flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
  color: #333;
}

.profile-photo {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  overflow: hidden;
}

.profile-photo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.profile-initial {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background-color: #0d6efd;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 18px;
}

/* Desktop dropdown styling */
.dropdown-content {
  display: none;
  position: absolute;
  right: 0;
  top: calc(100% - 15px); /* Position closer to badge */
  min-width: 200px;
  background-color: white;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  padding: 8px 0;
  z-index: 10;
  opacity: 0;
  transform: translateY(-10px);
  transition: opacity 0.2s ease, transform 0.2s ease;
}

.dropdown-content.show {
  display: block;
  opacity: 1;
  transform: translateY(0);
}

.profile-dropdown:hover .dropdown-content {
  display: block;
  opacity: 1;
  transform: translateY(0);
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

/* Mobile styles */
@media screen and (max-width: 768px) {
  .hamburger {
    display: block;
    margin-left: auto;
    position: relative;
    z-index: 30; /* Higher z-index to stay on top */
  }
  
  .mobile-menu-header {
    display: flex;
  }
  
  .nav-container {
    position: fixed;
    top: 0;
    right: 0; /* Position from right side instead of left */
    bottom: 0;
    width: 80%; /* Take only 80% of screen width */
    max-width: 300px; /* Maximum width */
    background-color: white;
    flex-direction: column;
    justify-content: flex-start;
    padding-top: 0;
    z-index: 20;
    transform: translateX(100%); /* Slide from right */
    transition: all 0.3s ease;
    box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
    visibility: hidden; /* Hide completely when closed */
    opacity: 0; /* Fade out */
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
    border-left: 3px solid #636ae8; /* Correct theme color */
    padding-bottom: 30px; /* Add some bottom padding for scrolling space */
  }
  
  .nav-container.mobile-open {
    transform: translateX(0);
    visibility: visible; /* Show when open */
    opacity: 1; /* Fade in */
  }
  
  .nav-links {
    flex-direction: column;
    align-items: center;
    margin: 0;
    gap: 8px;
    padding: 15px 0;
    width: 100%;
    overflow-y: auto;
  }
  
  .nav-links a {
    font-size: 16px;
    padding: 14px;
    width: 90%;
    text-align: left;
    border-bottom: 1px solid #f0f0f0;
    margin: 0;
    display: flex;
    align-items: center;
    transition: all 0.2s ease;
    border-radius: 4px;
    color: #333; /* Restore darker color for mobile */
    font-weight: 500; /* Restore bolder font weight for mobile */
    letter-spacing: normal; /* Remove letter spacing */
  }
  
  /* Show icons in mobile view */
  .mobile-only-icon {
    display: inline-block;
    margin-right: 10px;
    width: 20px;
    text-align: center;
    color: #636ae8; /* Theme color */
  }
  
  /* Override desktop hover styles for mobile */
  .nav-links a:hover,
  .nav-links a.active {
    background-color: rgba(99, 106, 232, 0.1);
    color: #636ae8; /* Theme color */
    border-bottom: 1px solid #f0f0f0;
  }
  
  /* Style the active nav link differently for mobile */
  .nav-links a.active {
    font-weight: 500;
    border-left: 3px solid #636ae8; /* Theme color */
    padding-left: 11px;
  }
  
  .nav-links a:last-child {
    border-bottom: none;
  }
  
  /* Auth buttons in mobile view */
  .auth-buttons {
    margin: 20px 0 0 0;
    width: 100%;
    display: flex;
    justify-content: center;
  }
  
  .profile-dropdown {
    width: 90%; /* Slightly wider */
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-bottom: 0; /* No padding needed in mobile view */
  }
  
  .profile-badge-wrapper {
    width: 100%;
    justify-content: center;
  }
  
  .profile-badge {
    flex-direction: column;
    text-align: center;
    padding: 10px;
    border-radius: 8px;
    background-color: #f5f5f5;
    width: 100%;
  }
  
  .profile-badge span {
    margin-top: 8px;
  }
  
  /* Mobile dropdown styling */
  .dropdown-content {
    position: static;
    display: none; /* Hidden by default on mobile too */
    width: 100%;
    box-shadow: none;
    margin-top: 10px;
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 5px;
    border-left: 2px solid #636ae8; /* Correct theme color */
    transform: none;
    opacity: 1;
  }
  
  .dropdown-content.show {
    display: block;
  }
  
  /* Override hover behavior for mobile - don't show on hover */
  .profile-dropdown:hover .dropdown-content {
    display: none;
  }
  
  /* Only show if explicitly set to show */
  .profile-dropdown:hover .dropdown-content.show {
    display: block;
  }
  
  .get-started-btn {
    width: 90%; /* Slightly wider */
    text-align: center;
  }
}
</style>
