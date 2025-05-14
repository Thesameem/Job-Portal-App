<template>
      <!-- Profile Header -->
      <section class="profile-header">
        <div class="profile-cover"></div>
        <div class="profile-info">
            <div class="profile-pic">
                <img src="./../../images/myphoto.JPG" alt="Profile Picture">
                <button class="edit-pic"><i class="fas fa-camera"></i></button>
            </div>
            <div class="profile-details">
                <h1>Sameem Miya</h1>
                <p class="title">Senior Full Stack Developer</p>
                <div class="profile-meta">
                    <span><i class="fas fa-map-marker-alt"></i> Bharatpur, Nepal</span>
                    <span><i class="fas fa-envelope"></i> iamshamim1999@email.com</span>
                    <span><i class="fas fa-phone"></i> +977 9800000000</span>
                </div>
                <div class="profile-actions">
                    <button class="edit-profile-btn" onclick="window.location.href='/editprofile'"><i class="fas fa-edit"></i> Edit Profile</button>
                    <button class="share-profile-btn"><i class="fas fa-share-alt"></i> Share Profile</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="profile-content">
        <!-- Left Column -->
        <div class="profile-left">
            <!-- About Section -->
            <section class="profile-section">
                <h2>About</h2>
                <p>Experienced frontend developer with a passion for creating beautiful and performant user interfaces. Specialized in React, TypeScript, and modern web technologies. Always eager to learn and adapt to new challenges.</p>
            </section>

            <!-- Skills Section -->
            <section class="profile-section">
                <h2>Skills</h2>
                <div class="skills-grid">
                    <div class="skill-category">
                        <h3>Frontend</h3>
                        <div class="skill-tags">
                            <span>React</span>
                            <span>TypeScript</span>
                            <span>JavaScript</span>
                            <span>HTML5</span>
                            <span>CSS3</span>
                            <span>SASS</span>
                        </div>
                    </div>
                    <div class="skill-category">
                        <h3>Backend</h3>
                        <div class="skill-tags">
                            <span>Node.js</span>
                            <span>Express</span>
                            <span>MongoDB</span>
                            <span>REST APIs</span>
                        </div>
                    </div>
                    <div class="skill-category">
                        <h3>Tools & Others</h3>
                        <div class="skill-tags">
                            <span>Git</span>
                            <span>Docker</span>
                            <span>AWS</span>
                            <span>Jest</span>
                            <span>Webpack</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Work Experience Section -->
            <section class="profile-section">
                <h2>Work Experience</h2>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-date">2020 - Present</div>
                        <div class="timeline-content">
                            <h3>Senior Frontend Developer</h3>
                            <p class="company">Tech Solutions Inc.</p>
                            <ul>
                                <li>Led frontend development for enterprise applications</li>
                                <li>Implemented responsive designs and optimized performance</li>
                                <li>Mentored junior developers and conducted code reviews</li>
                            </ul>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-date">2018 - 2020</div>
                        <div class="timeline-content">
                            <h3>Frontend Developer</h3>
                            <p class="company">Digital Innovations</p>
                            <ul>
                                <li>Developed and maintained multiple web applications</li>
                                <li>Collaborated with UX designers to implement designs</li>
                                <li>Improved application performance by 40%</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Education Section -->
            <section class="profile-section">
                <h2>Education</h2>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-date">2014 - 2018</div>
                        <div class="timeline-content">
                            <h3>Bachelor of Science in Computer Science</h3>
                            <p class="institution">University of Technology</p>
                            <p>Graduated with honors, specialized in Software Engineering</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Right Column -->
        <div class="profile-right">
            <!-- Availability Section -->
            <section class="profile-section">
                <h2>Availability</h2>
                <div class="availability-info">
                    <div class="availability-status available">
                        <i class="fas fa-circle"></i>
                        <span>Available for Work</span>
                    </div>
                    <div class="availability-details">
                        <p><strong>Preferred Work:</strong> Full-time, Remote</p>
                        <p><strong>Notice Period:</strong> 2 weeks</p>
                        <p><strong>Expected Salary:</strong> $80,000 - $100,000</p>
                    </div>
                </div>
            </section>

            <!-- Languages Section -->
            <section class="profile-section">
                <h2>Languages</h2>
                <div class="languages-list">
                    <div class="language-item">
                        <span>English</span>
                        <div class="proficiency">
                            <span class="dot filled"></span>
                            <span class="dot filled"></span>
                            <span class="dot filled"></span>
                            <span class="dot filled"></span>
                            <span class="dot filled"></span>
                        </div>
                    </div>
                    <div class="language-item">
                        <span>Spanish</span>
                        <div class="proficiency">
                            <span class="dot filled"></span>
                            <span class="dot filled"></span>
                            <span class="dot filled"></span>
                            <span class="dot"></span>
                            <span class="dot"></span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Certifications Section -->
            <section class="profile-section">
                <h2>Certifications</h2>
                <div class="certifications-list">
                    <div class="certification-item">
                        <i class="fas fa-certificate"></i>
                        <div class="certification-info">
                            <h4>AWS Certified Developer</h4>
                            <p>Amazon Web Services</p>
                            <span class="cert-date">2022</span>
                        </div>
                    </div>
                    <div class="certification-item">
                        <i class="fas fa-certificate"></i>
                        <div class="certification-info">
                            <h4>React Advanced Concepts</h4>
                            <p>Meta</p>
                            <span class="cert-date">2021</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Social Links Section -->
            <section class="profile-section">
                <h2>Social Links</h2>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-github"></i> GitHub</a>
                    <a href="#" class="social-link"><i class="fab fa-linkedin"></i> LinkedIn</a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i> Twitter</a>
                    <a href="#" class="social-link"><i class="fas fa-globe"></i> Portfolio</a>
                </div>
            </section>
        </div>
    </main>
</template>