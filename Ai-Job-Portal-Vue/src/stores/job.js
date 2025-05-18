import { defineStore } from 'pinia'
import { GET } from '@/scripts/Fetch'
import Cookie from '@/scripts/Cookie'
import Config from '@/scripts/Config'

export const useJobStore = defineStore('job', {
  state: () => ({
    user: {},
    joblist: [],
    userprofile: {},
    isLoadingProfile: false
  }),
  
  getters: {
    isAuthenticated() {
      return !!Cookie.getCookie('job-app') && Object.keys(this.user).length > 0
    },
    
    // Get profile photo URL if available
    profilePhotoUrl() {
      if (!this.userprofile || !this.userprofile.profile_photo) {
        return null
      }
      
      // Use profile_photo_url if available from backend
      if (this.userprofile.profile_photo_url) {
        return this.userprofile.profile_photo_url
      }
      
      // Otherwise construct URL from filename
      const filename = this.userprofile.profile_photo
      // Ensure it's not a temporary path
      if (filename.includes('tmp') || filename.includes('php') || filename.includes('C:')) {
        console.warn('Temporary path detected in profile photo:', filename)
        return null
      }
      
      // Construct proper URL
      return `${Config.baseURL.replace('/api', '')}/storage/profile-photos/${filename}`
    }
  },
  
  actions: {
    // Fetch user profile from API if authenticated
    async fetchUserProfile() {
      // Check if we have auth cookie
      const token = Cookie.getCookie('job-app')
      if (!token) {
        console.log('No authentication token found, skipping profile fetch')
        return null
      }
      
      // Prevent duplicate requests
      if (this.isLoadingProfile) {
        console.log('Profile fetch already in progress')
        return
      }
      
      try {
        this.isLoadingProfile = true
        console.log('Fetching user profile from API')
        
        const result = await GET('my-profile')
        
        if (!result.error && result.response) {
          // Store the profile data
          if (result.response.profile) {
            this.userprofile = result.response.profile
            
            // Store in localStorage for persistence
            localStorage.setItem('job-user-profile', JSON.stringify(this.userprofile))
            console.log('Profile data saved to store and localStorage')
          }
          
          return this.userprofile
        } else {
          console.error('Failed to fetch profile:', result.reason || 'Unknown error')
          return null
        }
      } catch (error) {
        console.error('Error fetching profile:', error)
        return null
      } finally {
        this.isLoadingProfile = false
      }
    },
    
    // Try to restore profile from localStorage
    restoreProfile() {
      try {
        const savedProfile = localStorage.getItem('job-user-profile')
        if (savedProfile) {
          this.userprofile = JSON.parse(savedProfile)
          console.log('Restored profile from localStorage')
          return true
        }
      } catch (error) {
        console.error('Error restoring profile from localStorage:', error)
      }
      return false
    },
    
    // Call this after login or when app starts
    async initializeProfile() {
      // First try to restore from local storage
      const restored = this.restoreProfile()
      
      // If we have an auth token, fetch fresh data from API
      if (Cookie.getCookie('job-app')) {
        await this.fetchUserProfile()
      }
      
      return this.userprofile
    },
    
    // Clear profile data on logout
    clearProfile() {
      this.userprofile = {}
      localStorage.removeItem('job-user-profile')
    },
    
    // Update user profile data - only used after server-side updates
    updateUserProfile(newProfileData) {
      // Update the store with server-confirmed data
      this.userprofile = { ...newProfileData }
      
      // Save to localStorage for persistence between page loads
      try {
        localStorage.setItem('job-user-profile', JSON.stringify(this.userprofile))
      } catch (error) {
        console.error('Error saving profile to localStorage:', error)
      }
      
      return this.userprofile
    }
  }
});
