# 📸 Camagru

Welcome to **Camagru**!  
A full-stack web application built as part of the 42 school curriculum, designed to showcase my skills in modern web development, security, and DevOps.  
This project is a mini Instagram-like platform where users can create accounts, take and upload photos with fun filters, comment, like, and manage their profiles, all in a secure, containerized environment.

---

## 🚀 What is Camagru?

Camagru is a photo-sharing web app where users can:
- 📷 Take photos with their webcam or upload images
- 🎨 Apply cool filters (like Shrek, UFO, devil horns, and more)
- 🖼️ View a gallery of all user images
- ❤️ Like and 💬 comment on images
- 👤 Manage their account details and notification preferences
- 🔒 Reset their password securely
- 📨 Receive email notifications for account activation and comments

---

## 🛠️ What I Learned & Did

### 🏗️ **Architecture & Technologies**
- **MVC Pattern**: Built from scratch in PHP using a custom MVC (Model-View-Controller) architecture for clean separation of concerns.
- **MySQL**: Used as the relational database, with a schema for users, images, comments, and likes.
- **Docker**: The entire stack runs in Docker containers (PHP/Apache, MySQL, phpMyAdmin) for easy setup and deployment.
- **Makefile**: Automated build, reset, and destroy commands for containers and data.

### 🔒 **Security Best Practices**
- **CSRF Protection**: Every form and sensitive action is protected by CSRF tokens.
- **Input Validation**: All user input is validated and sanitized (server-side and client-side).
- **Password Hashing**: Passwords are securely hashed using PHP’s `password_hash`.
- **Account Activation**: Users must verify their email before logging in.
- **Password Reset**: Secure, token-based password reset with expiry.
- **Session Management**: User sessions are handled securely.

### ✨ **Features**
- **User Authentication**: Signup, login, logout, and email verification.
- **Photo Gallery**: Paginated gallery with likes and comments.
- **Camera & Filters**: Take photos with webcam and overlay fun PNG filters.
- **Image Upload**: Upload images from your device.
- **Account Management**: Edit username, email, and notification settings.
- **Notifications**: Email notifications for comments and account actions.
- **Responsive UI**: Clean, modern, and responsive interface using Pico.css.
- **Error Handling**: Friendly error pages with animated GIFs.

### 🐳 **DevOps & Deployment**
- **Docker Compose**: One command to spin up the entire stack.
- **phpMyAdmin**: Easy database management via web UI.
- **Fixtures**: Automated loading of test users and data for development.

---

## 🧑‍💻 How to Run

1. **Clone the repo**  
   `git clone https://github.com/yourusername/42_Camagru.git`

2. **Set up environment variables**  
   Copy `.env.example` to `.env` and fill in the required values (MySQL root password, email settings, etc).

3. **Build and start the app**  
   ```sh
   make build
   ```

4. **Access the app**  
   - App: [http://localhost:8000](http://localhost:8000)
   - phpMyAdmin: [http://localhost:8001](http://localhost:8001)

5. **Stop or reset**  
   - Stop: `make stop`
   - Reset: `make re`

---

## 🏆 Skills Demonstrated

- Full-stack web development (PHP, SQL, JS, HTML, CSS)
- Secure authentication and authorization
- RESTful routing and MVC design
- Docker & container orchestration
- Automated testing with fixtures
- Clean, user-friendly UI/UX
- DevOps automation with Makefile

---

## 👨‍🎓 About the Author

Made with ❤️ by Flamiing (me)
