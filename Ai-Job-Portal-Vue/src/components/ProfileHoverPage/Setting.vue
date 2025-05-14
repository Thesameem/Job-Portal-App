<template>
  <!-- Settings Section -->
  <div class="settings-container">
    <div class="settings-sidebar">
      <h2>Settings</h2>
      <ul class="settings-menu">
        <li class="active">
          <a href="#account"><i class="fas fa-user"></i> Account Settings</a>
        </li>
        <li>
          <a href="#notifications"><i class="fas fa-bell"></i> Notifications</a>
        </li>
        <li>
          <a href="#privacy"><i class="fas fa-shield-alt"></i> Privacy</a>
        </li>
        <li>
          <a href="#security"><i class="fas fa-lock"></i> Security</a>
        </li>
        <li>
          <a href="#preferences"><i class="fas fa-cog"></i> Preferences</a>
        </li>
      </ul>
    </div>

    <div class="settings-content">
      <section id="account" class="settings-section">
        <h2>Account Settings</h2>
        <div class="settings-form">
          <div class="form-group">
            <label>Profile Picture</label>
            <div class="profile-picture-upload">
              <img src="./../../images/myphoto.JPG" alt="Profile Picture" />
              <button class="upload-btn" onclick="window.location.href='editaccountsettings.html'">
                Edit Profile
              </button>
            </div>
          </div>
          <div class="form-group">
            <label>Full Name</label>
            <input type="text" value="Sameem Miya" readonly />
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" value="iamshamim1999@email.com" readonly />
          </div>
          <div class="form-group">
            <label>Phone</label>
            <input type="tel" value="+977 9800000000" readonly />
          </div>
          <button class="save-btn" onclick="window.location.href='editaccountsettings.html'">
            Edit Account Settings
          </button>
        </div>
      </section>
    </div>
  </div>
</template>
