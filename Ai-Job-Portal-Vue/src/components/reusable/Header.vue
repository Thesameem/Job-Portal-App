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
        <div class="profile-dropdown">
          <a href="/userprofile" class="profile-badge">
            <img src="./../../images/myphoto.JPG" alt="Profile Picture" />
            <span>Sameem Miya</span>
          </a>
          <div class="dropdown-content">
            <RouterLink to="/managejob"><i class="fas fa-briefcase"></i> Manage Jobs</RouterLink>
            <RouterLink to="/settings"><i class="fas fa-cog"></i> Settings</RouterLink>
            <RouterLink to="/logout"><i class="fas fa-sign-out-alt"></i> Logout</RouterLink>
          </div>
        </div>
        <!-- <button class="login-btn" onclick="window.location.href='/auth'">Log In</button>
        <button class="signup-btn" onclick="window.location.href='/auth'">Sign Up</button> -->
      </div>
    </div>
  </nav>
</template>
