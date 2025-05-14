<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Cookie from '@/scripts/Cookie'
import axios from 'axios'
import { GET } from '@/scripts/Fetch'
import Header from '@/components/reusable/Header.vue'
import Footer from '@/components/reusable/Footer.vue'

let router = useRouter()

onMounted(() => {
  let Token = Cookie.getCookie('job-app')
  if (!Token) {
    router.push({ path: '/auth' })
    return
  }

  // let result = await GET('user/jobs');
  // if (!result.error) JobStore.jobList = result.response;
})
</script>

<template>
  <!-- Hero Section -->
  <section class="hero" role="banner">
    <div class="hero-content">
      <h1>Welcome!!</h1>
      <h1>16,789 Jobs For You</h1>
      <p>
        Connect with top employers and discover opportunities that match your skills and
        aspirations.
      </p>
      <div class="search-container">
        <form action="" method="GET" role="search">
          <input
            type="text"
            name="search"
            placeholder="Search for jobs, skills, or companies"
            aria-label="Search jobs"
          />
          <button type="submit" class="search-btn" aria-label="Search jobs">
            <i class="fas fa-search"></i> Search
          </button>
        </form>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features" aria-labelledby="features-heading">
    <h2 id="features-heading" class="visually-hidden">Our Features</h2>
    <div class="feature-grid">
      <div class="feature-card">
        <i class="fas fa-search" aria-hidden="true"></i>
        <h3>Find Jobs</h3>
        <p>Browse through thousands of job listings from top companies.</p>
        <RouterLink to="/findjob" class="feature-link"
          >Explore Jobs <i class="fas fa-arrow-right" aria-hidden="true"></i
        ></RouterLink>
      </div>
      <div class="feature-card">
        <i class="fas fa-briefcase" aria-hidden="true"></i>
        <h3>Post Jobs</h3>
        <p>Hire the best talent for your company's needs.</p>
        <RouterLink to="/createjob" class="feature-link"
          >Post a Job <i class="fas fa-arrow-right" aria-hidden="true"></i
        ></RouterLink>
      </div>
      <div class="feature-card">
        <i class="fas fa-users" aria-hidden="true"></i>
        <h3>Connect</h3>
        <p>Build your professional network and grow your career.</p>
        <RouterLink to="/findtalent" class="feature-link"
          >Find Talent <i class="fas fa-arrow-right" aria-hidden="true"></i
        ></RouterLink>
      </div>
    </div>
  </section>

  <!-- How It Works Section -->
  <section class="how-it-works" aria-labelledby="how-it-works-heading">
    <h2 id="how-it-works-heading">How It Works</h2>
    <div class="steps-container">
      <div class="step">
        <i class="fas fa-user-plus" aria-hidden="true"></i>
        <h3>Create Account</h3>
        <p>Sign up and create your professional profile</p>
        <RouterLink to="/" class="step-link"
          >Get Started <i class="fas fa-arrow-right" aria-hidden="true"></i
        ></RouterLink>
      </div>
      <div class="step">
        <i class="fas fa-search" aria-hidden="true"></i>
        <h3>Search Jobs</h3>
        <p>Browse and apply for jobs that match your skills</p>
        <RouterLink to="/findjobs" class="step-link"
          >Browse Jobs <i class="fas fa-arrow-right" aria-hidden="true"></i
        ></RouterLink>
      </div>
      <div class="step">
        <i class="fas fa-handshake" aria-hidden="true"></i>
        <h3>Get Hired</h3>
        <p>Connect with employers and land your dream job</p>
        <RouterLink to="/findtalent" class="step-link"
          >Find Opportunities <i class="fas fa-arrow-right" aria-hidden="true"></i
        ></RouterLink>
      </div>
    </div>
  </section>
</template>
