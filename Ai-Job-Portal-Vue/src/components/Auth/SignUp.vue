<script setup>
import { ref } from 'vue'
import { POST } from '@/scripts/Fetch'

let emits = defineEmits(['login'])
let Fullname = ''
let email = ''
let password = ''

let SignupError = ref(false)
let ErrorText = ref('Something went wrong')

const RegisterUser = async () => {
  //create form data to send to server

  let formData = new FormData()

  formData.append('fullname', fullname)
  formData.append('email', email)
  formData.append('password', password)

  //send post method to register user
  let result = await POST('user/register', formData)

  if (!result.error) emits('login')
  else {
    SignupError.value = true
    ErrorText.value = result.reason
  }
}
</script>

<template>
  <!-- Sign Up Section -->
  <section class="auth-section">
    <div class="auth-container">
      <div class="auth-header">
        <h2>Create Your Account</h2>
        <p>Join Ai-Job and start your journey to success</p>
      </div>
      <form class="auth-form">
        <div class="form-group">
          <label for="fullname">Full Name</label>
          <input
            type="text"
            id="fullname"
            placeholder="Enter your full name"
            required
            v-model="fullname"
          />
        </div>
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
              placeholder="Create a password"
              required
              v-model="password"
            />
            <i class="fas fa-eye toggle-password"></i>
          </div>
        </div>
        <div class="form-group" style="display: none">
          <label for="confirm-password">Confirm Password</label>
          <div class="password-input">
            <input
              type="password"
              id="confirm-password"
              placeholder="Confirm your password"
              required
            />
            <i class="fas fa-eye toggle-password"></i>
          </div>
        </div>
        <div class="form-group">
          <label class="checkbox-label">
            <input type="checkbox" required />
            <span
              >I agree to the <a href="/termsofservice">Terms of Service</a> and
              <a href="/privacypolicy">Privacy Policy</a></span
            >
          </label>
        </div>
        <p v-if="SignupError" style="color: red">{{ ErrorText }}</p>

        <button type="submit" class="auth-button" @click="RegisterUser">Create Account</button>
        <div class="social-login">
          <p>Or sign up with</p>
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
        <p>Already have an account? <a href="#" @click="emits('login')">Log In</a></p>
      </div>
    </div>
  </section>
</template>
