<script setup>
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useJobStore } from '@/stores/job'
import Cookie from '@/scripts/Cookie'
import { POST } from '@/scripts/Fetch'

let emits = defineEmits(['signup'])

let email = ''
let password = ''

const JobStore = useJobStore()
const router = useRouter()

const LogInUser = async () => {
  //create form data to send to server

  let formData = new FormData()

  formData.append('email', email)
  formData.append('password', password)

  //send post method to register user
  let result = await POST('user/login', formData)
  if (!result.error) {
    Cookie.setCookie('job-app', result.response.token, 30)
    JobStore.user = result.response.user
    router.push({
      path: '/',
    })
  }
}
</script>

<template>
  <!-- Log In Section -->
  <section class="auth-section">
    <div class="auth-container">
      <div class="auth-header">
        <h2>Welcome Back</h2>
        <p>Log in to your Ai-Job account to continue</p>
      </div>
      <form class="auth-form">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" placeholder="Enter your email" required v-model="email" />
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <div class="password-input">
            <input
              type="password"
              id="password"
              placeholder="Enter your password"
              required
              v-model="password"
            />
            <i class="fas fa-eye toggle-password"></i>
          </div>
        </div>
        <div class="form-options">
          <label class="remember-me">
            <input type="checkbox" />
            Remember Me
          </label>
          <a href="#" class="forgot-password">Forgot Password?</a>
        </div>
        <span class="auth-button" @click="LogInUser">Log In</span>
        <div class="social-login">
          <p>Or log in with</p>
          <div class="social-buttons">
            <button type="button" class="social-btn google">
              <i class="fab fa-google"></i>
              Google
            </button>
            <button type="button" class="social-btn linkedin">
              <i class="fab fa-linkedin"></i>
              LinkedIn
            </button>
          </div>
        </div>
      </form>
      <div class="auth-footer">
        <p>Don't have an account? <a href="#" @click="emits('signup')">Sign Up</a></p>
      </div>
    </div>
  </section>
</template>
