const API_BASE_URL = "http://localhost/fitlife_api/backend";

// Current Auth Session State
let currentUser = JSON.parse(localStorage.getItem("fitlife_user")) || null;

document.addEventListener("DOMContentLoaded", () => {
  checkSession();

  document.getElementById("login-form").addEventListener("submit", handleLogin);
  document
    .getElementById("add-member-form")
    .addEventListener("submit", handleRegisterMember);
  document
    .getElementById("self-checkin-btn")
    .addEventListener("click", handleSelfCheckin);
});

// Single Page Application View Navigator
function navigateTo(viewId) {
  document
    .querySelectorAll(".view-section")
    .forEach((view) => view.classList.remove("active"));
  document
    .querySelectorAll(".nav-btn")
    .forEach((btn) => btn.classList.remove("active"));

  const targetView = document.getElementById(viewId);
  if (targetView) {
    targetView.classList.add("active");
  }

  // Refresh view specific data
  if (viewId === "admin-dashboard-view") {
    loadDashboardStats();
    loadMembers();
  }
}

// Session Validator
function checkSession() {
  const portalHolder = document.getElementById("portal-link-holder");

  if (currentUser) {
    portalHolder.innerHTML = `
            <button class="nav-btn primary-btn" onclick="openPortal()">My Dashboard (${currentUser.role})</button>
        `;
  } else {
    portalHolder.innerHTML = `
            <button class="nav-btn primary-btn" onclick="navigateTo('login-view')">Portal Login</button>
        `;
  }
}

function openPortal() {
  if (!currentUser) return navigateTo("login-view");
  if (currentUser.role === "admin") {
    navigateTo("admin-dashboard-view");
  } else {
    document.getElementById("member-profile-name").innerText = currentUser.name;
    navigateTo("member-dashboard-view");
  }
}

// Handle User/Admin Login
async function handleLogin(e) {
  e.preventDefault();
  const email = document.getElementById("login-email").value.trim();
  const password = document.getElementById("login-password").value.trim();

  try {
    const response = await fetch(`${API_BASE_URL}/public/index.php/login`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ email, password }),
    });

    const result = await response.json();

    if (response.ok && result.status === "success") {
      currentUser = result.user;
      localStorage.setItem("fitlife_user", JSON.stringify(currentUser));
      localStorage.setItem("fitlife_token", result.token);
      checkSession();
      openPortal();
    } else {
      alert("Login Failed: " + (result.message || "Invalid credentials"));
    }
  } catch (error) {
    alert("Server communication error. Make sure XAMPP Apache is running.");
  }
}

// Logout session
function logout() {
  localStorage.removeItem("fitlife_user");
  localStorage.removeItem("fitlife_token");
  currentUser = null;
  checkSession();
  navigateTo("home-view");
}

// Fetch Admin Stats Overview
async function loadDashboardStats() {
  try {
    const response = await fetch(`${API_BASE_URL}/stats.php`);
    const result = await response.json();
    if (result.status === "success") {
      document.getElementById("total-members-count").innerText =
        result.data.active_members;
      document.getElementById("today-checkins-count").innerText =
        result.data.today_checkins;
    }
  } catch (error) {
    console.error("Stats API Connection Error:", error);
  }
}

// Load Members List for Admin
async function loadMembers() {
  const tableBody = document.getElementById("members-table-body");
  try {
    const response = await fetch(`${API_BASE_URL}/members.php`);
    const result = await response.json();

    if (result.status === "success" && result.data.length > 0) {
      tableBody.innerHTML = result.data
        .map(
          (m) => `
                <tr>
                    <td>#${m.id}</td>
                    <td><strong>${m.name}</strong></td>
                    <td>${m.email}</td>
                    <td>${m.phone}</td>
                    <td><span class="badge badge-active">${m.status}</span></td>
                    <td>
                        <button class="btn btn-primary" onclick="adminCheckin(${m.id})">Check-In</button>
                    </td>
                </tr>
            `,
        )
        .join("");
    } else {
      tableBody.innerHTML = `<tr><td colspan="6" style="text-align:center;">No members recorded yet.</td></tr>`;
    }
  } catch (error) {
    tableBody.innerHTML = `<tr><td colspan="6" style="text-align:center; color:red;">Failed to load members from server.</td></tr>`;
  }
}

// Register Member Action
async function handleRegisterMember(e) {
  e.preventDefault();

  const payload = {
    name: document.getElementById("member-name").value,
    email: document.getElementById("member-email").value,
    phone: document.getElementById("member-phone").value,
    password: document.getElementById("member-password").value,
  };

  try {
    const response = await fetch(`${API_BASE_URL}/register_member.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    });

    const result = await response.json();
    if (result.status === "success") {
      alert("New Member Registered Successfully!");
      document.getElementById("add-member-form").reset();
      loadMembers();
      loadDashboardStats();
    } else {
      alert("Error: " + result.message);
    }
  } catch (error) {
    alert("Server communication error.");
  }
}

// Admin Check-In Action
async function adminCheckin(memberId) {
  try {
    const response = await fetch(`${API_BASE_URL}/attendance.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ member_id: memberId }),
    });
    const result = await response.json();
    if (result.status === "success") {
      alert("Attendance Checked-In!");
      loadDashboardStats();
    } else {
      alert("Check-in failed: " + result.message);
    }
  } catch (error) {
    alert("Server error when checking in.");
  }
}

// Member Self Check-In Action
async function handleSelfCheckin() {
  if (!currentUser || !currentUser.member_id) {
    alert("Member ID profile missing.");
    return;
  }
  await adminCheckin(currentUser.member_id);
  document.getElementById("member-checkin-count").innerText = "1 Logged Today";
}
