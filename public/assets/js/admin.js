// Admin JavaScript - assets/js/admin.js

document.addEventListener("DOMContentLoaded", function () {
  // Sidebar toggle for mobile
  const sidebar = document.querySelector(".sidebar");
  const mainContent = document.querySelector(".main-content");

  // Create mobile toggle button
  if (window.innerWidth <= 1024) {
    createMobileToggle();
  }

  function createMobileToggle() {
    const toggleBtn = document.createElement("button");
    toggleBtn.className = "mobile-toggle";
    toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
    toggleBtn.style.cssText = `
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: #2c5aa0;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        `;

    document.body.appendChild(toggleBtn);

    toggleBtn.addEventListener("click", function () {
      sidebar.classList.toggle("active");

      if (sidebar.classList.contains("active")) {
        this.innerHTML = '<i class="fas fa-times"></i>';
      } else {
        this.innerHTML = '<i class="fas fa-bars"></i>';
      }
    });

    // Close sidebar when clicking outside
    document.addEventListener("click", function (e) {
      if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
        sidebar.classList.remove("active");
        toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
      }
    });
  }

  // Handle window resize
  window.addEventListener("resize", function () {
    if (window.innerWidth > 1024) {
      sidebar.classList.remove("active");
      const mobileToggle = document.querySelector(".mobile-toggle");
      if (mobileToggle) {
        mobileToggle.remove();
      }
    } else {
      if (!document.querySelector(".mobile-toggle")) {
        createMobileToggle();
      }
    }
  });

  // Dropdown functionality
  const dropdownToggles = document.querySelectorAll(".dropdown-toggle");

  dropdownToggles.forEach((toggle) => {
    toggle.addEventListener("click", function (e) {
      e.preventDefault();
      const navItem = this.closest(".nav-item");
      const dropdownMenu = navItem.querySelector(".dropdown-menu");

      // Close other dropdowns
      dropdownToggles.forEach((otherToggle) => {
        if (otherToggle !== this) {
          otherToggle.closest(".nav-item").classList.remove("active");
        }
      });

      // Toggle current dropdown
      navItem.classList.toggle("active");
    });
  });

  // Stats animation
  const statNumbers = document.querySelectorAll(".stat-content h3");

  const animateNumbers = () => {
    statNumbers.forEach((stat) => {
      const finalNumber = parseInt(stat.textContent);
      const increment = Math.ceil(finalNumber / 20);
      let currentNumber = 0;

      stat.textContent = "0";

      const counter = setInterval(() => {
        currentNumber += increment;
        if (currentNumber >= finalNumber) {
          stat.textContent = finalNumber;
          clearInterval(counter);
        } else {
          stat.textContent = currentNumber;
        }
      }, 50);
    });
  };

  // Trigger animation when stats come into view
  const observerOptions = {
    threshold: 0.5,
    rootMargin: "0px 0px -100px 0px",
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        animateNumbers();
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  const statsGrid = document.querySelector(".stats-grid");
  if (statsGrid) {
    observer.observe(statsGrid);
  }

  // Add hover effects to stat cards
  const statCards = document.querySelectorAll(".stat-card");

  statCards.forEach((card) => {
    card.addEventListener("mouseenter", function () {
      this.style.transform = "translateY(-5px) scale(1.02)";
    });

    card.addEventListener("mouseleave", function () {
      this.style.transform = "translateY(0) scale(1)";
    });
  });

  // Recent items animation
  const recentItems = document.querySelectorAll(".recent-item");

  recentItems.forEach((item, index) => {
    item.style.opacity = "0";
    item.style.transform = "translateX(-30px)";

    setTimeout(() => {
      item.style.transition = "all 0.5s ease";
      item.style.opacity = "1";
      item.style.transform = "translateX(0)";
    }, index * 100);
  });

  // Add loading states for navigation links
  const navLinks = document.querySelectorAll(
    ".nav-link[href]:not(.dropdown-toggle)"
  );

  navLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      // Don't show loading for same page links
      if (this.href === window.location.href) {
        return;
      }

      // Add loading spinner
      const icon = this.querySelector("i");
      const originalIcon = icon.className;

      icon.className = "fas fa-spinner fa-spin";

      // Reset after delay if navigation doesn't happen
      setTimeout(() => {
        if (icon) {
          icon.className = originalIcon;
        }
      }, 3000);
    });
  });

  // Logout confirmation
  const logoutBtn = document.querySelector(".logout-btn");
  if (logoutBtn) {
    logoutBtn.addEventListener("click", function (e) {
      e.preventDefault();

      const confirmed = confirm("Apakah Anda yakin ingin logout?");
      if (confirmed) {
        // Add loading state
        this.innerHTML =
          '<i class="fas fa-spinner fa-spin"></i> <span>Logging out...</span>';

        // Navigate to logout
        setTimeout(() => {
          window.location.href = this.href;
        }, 1000);
      }
    });
  }

  // Auto refresh for dashboard stats (optional)
  let autoRefreshInterval;

  function startAutoRefresh() {
    autoRefreshInterval = setInterval(() => {
      // Only refresh if user is active (has interacted recently)
      const lastActivity = localStorage.getItem("lastActivity");
      const now = Date.now();

      if (!lastActivity || now - parseInt(lastActivity) < 300000) {
        // 5 minutes
        refreshDashboardStats();
      }
    }, 300000); // Refresh every 5 minutes
  }

  function refreshDashboardStats() {
    // Add visual indicator
    const statsGrid = document.querySelector(".stats-grid");
    if (statsGrid) {
      statsGrid.style.opacity = "0.7";

      // Simulate API call (replace with actual API call)
      fetch("/api/stats")
        .then((response) => response.json())
        .then((data) => {
          // Update stats (implement based on your API response)
          updateStatsDisplay(data);
        })
        .catch((error) => {
          console.log("Stats refresh failed:", error);
        })
        .finally(() => {
          statsGrid.style.opacity = "1";
        });
    }
  }

  function updateStatsDisplay(data) {
    // Example implementation
    const statElements = document.querySelectorAll(".stat-content h3");
    if (data && data.stats) {
      statElements.forEach((element, index) => {
        if (data.stats[index]) {
          element.textContent = data.stats[index];
        }
      });
    }
  }

  // Track user activity
  function trackActivity() {
    localStorage.setItem("lastActivity", Date.now().toString());
  }

  // Track various user interactions
  document.addEventListener("click", trackActivity);
  document.addEventListener("keypress", trackActivity);
  document.addEventListener("scroll", trackActivity);

  // Start auto refresh if enabled
  if (localStorage.getItem("autoRefresh") !== "false") {
    startAutoRefresh();
  }

  // Cleanup on page unload
  window.addEventListener("beforeunload", function () {
    if (autoRefreshInterval) {
      clearInterval(autoRefreshInterval);
    }
  });

  // Smooth scrolling for internal links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();

      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start",
        });
      }
    });
  });

  // Add ripple effect to buttons
  function createRipple(event) {
    const button = event.currentTarget;
    const rect = button.getBoundingClientRect();

    const circle = document.createElement("span");
    const diameter = Math.max(button.clientWidth, button.clientHeight);
    const radius = diameter / 2;

    circle.style.width = circle.style.height = `${diameter}px`;
    circle.style.left = `${event.clientX - rect.left - radius}px`;
    circle.style.top = `${event.clientY - rect.top - radius}px`;
    circle.classList.add("ripple");

    const ripple = button.getElementsByClassName("ripple")[0];
    if (ripple) {
      ripple.remove();
    }

    button.appendChild(circle);

    setTimeout(() => {
      circle.remove();
    }, 600);
  }

  // Apply ripple to buttons
  document.querySelectorAll(".submit-btn, .logout-btn").forEach((button) => {
    button.addEventListener("click", createRipple);

    // Add ripple styles
    if (!document.querySelector("#ripple-styles")) {
      const style = document.createElement("style");
      style.id = "ripple-styles";
      style.textContent = `
                .ripple {
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.6);
                    transform: scale(0);
                    animation: ripple-animation 0.6s linear;
                    pointer-events: none;
                }
                
                @keyframes ripple-animation {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
      document.head.appendChild(style);
    }
  });

  // Initialize tooltips (if you want to add them)
  function initTooltips() {
    const elementsWithTooltip = document.querySelectorAll("[data-tooltip]");

    elementsWithTooltip.forEach((element) => {
      element.addEventListener("mouseenter", showTooltip);
      element.addEventListener("mouseleave", hideTooltip);
    });
  }

  function showTooltip(e) {
    const tooltip = document.createElement("div");
    tooltip.className = "custom-tooltip";
    tooltip.textContent = e.target.getAttribute("data-tooltip");

    document.body.appendChild(tooltip);

    const rect = e.target.getBoundingClientRect();
    tooltip.style.left =
      rect.left + rect.width / 2 - tooltip.offsetWidth / 2 + "px";
    tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + "px";
  }

  function hideTooltip() {
    const tooltip = document.querySelector(".custom-tooltip");
    if (tooltip) {
      tooltip.remove();
    }
  }

  initTooltips();
});

// GANTI BAGIAN NOTIFICATION SYSTEM DI admin.js DENGAN KODE INI

// =============== NOTIFICATION SYSTEM (UPDATED) ===============
// Notification system variables
let notificationData = [];
let showingNotifications = true;
let notificationCheckInterval;

// Initialize notifications when document is ready
function initNotifications() {
  // Load notifications immediately
  loadNotifications();

  // Check for new notifications every 30 seconds
  notificationCheckInterval = setInterval(loadNotifications, 30000);

  // Create notification elements if they don't exist
  createNotificationElements();
}

// REPLACE the createNotificationElements function in admin.js with this fixed version

function createNotificationElements() {
  // Create floating notifications container (positioned better)
  if (!document.getElementById("floatingNotifications")) {
    const floatingContainer = document.createElement("div");
    floatingContainer.className = "floating-notifications";
    floatingContainer.id = "floatingNotifications";
    // Updated positioning - centered vertically on the right side
    floatingContainer.style.cssText = `
      position: fixed;
      bottom: 70px;
      right: 20px;
      z-index: 1000;
      max-width: 350px;
    `;
    document.body.appendChild(floatingContainer);
  }

  // Create notification summary button (positioned above bottom)
  if (!document.getElementById("notificationSummary")) {
    const summaryButton = document.createElement("button");
    summaryButton.className = "notification-summary";
    summaryButton.id = "notificationSummary";
    summaryButton.style.display = "none";
    summaryButton.onclick = toggleNotifications;
    summaryButton.style.cssText = `
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
      padding: 12px 20px;
      border-radius: 25px;
      font-size: 13px;
      font-weight: 600;
      box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
      cursor: pointer;
      transition: all 0.3s ease;
      z-index: 999;
      border: none;
      display: flex;
      align-items: center;
      gap: 8px;
    `;
    summaryButton.innerHTML = `
      <i class="fas fa-bell"></i>
      <span id="notificationCount">0</span> Pengaduan Aktif
    `;
    document.body.appendChild(summaryButton);
  }
}

// Load notifications from server
function loadNotifications() {
  // Only load if we're on an admin page
  if (!window.location.pathname.includes("/admin")) {
    return;
  }

  fetch("/admin/pengaduan/notifications")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      if (data.success) {
        notificationData = data.notifications || [];
        updateNotificationDisplay(data);
        updateSidebarBadge(data.total_active || 0);
      }
    })
    .catch((error) => {
      console.error("Error loading notifications:", error);
      // Don't show error to user, just log it
    });
}

// Update notification display
function updateNotificationDisplay(data) {
  const container = document.getElementById("floatingNotifications");
  const summary = document.getElementById("notificationSummary");
  const countSpan = document.getElementById("notificationCount");

  if (!container || !summary || !countSpan) {
    return; // Elements not created yet
  }

  const totalActive = data.total_active || 0;

  // Update summary button
  if (totalActive > 0) {
    countSpan.textContent = totalActive;
    summary.style.display = "flex";
  } else {
    summary.style.display = "none";
    container.innerHTML = "";
    return;
  }

  // Show floating notifications if enabled and we have notifications
  if (showingNotifications && notificationData.length > 0) {
    renderFloatingNotifications(notificationData.slice(0, 3)); // Show max 3
  } else {
    container.innerHTML = "";
  }
}

// Render floating notifications
function renderFloatingNotifications(notifications) {
  const container = document.getElementById("floatingNotifications");

  if (!container) return;

  container.innerHTML = notifications
    .map((notif) => {
      const isUrgent = ["tinggi", "sangat_tinggi"].includes(notif.urgency);
      const timeAgo = getTimeAgoFixed(notif.created_at);

      return `
      <div class="notification-item ${
        isUrgent ? "urgent" : ""
      }" onclick="goToPengaduan(${notif.id})">
        <div class="notification-header">
          <span class="notification-title">Pengaduan Baru - ${escapeHtml(
            notif.ticket_number || "N/A"
          )}</span>
          <button class="notification-close" onclick="event.stopPropagation(); dismissNotification(${
            notif.id
          })" title="Tutup">×</button>
        </div>
        <div class="notification-content">
          <strong>${escapeHtml(
            notif.nama || "Unknown"
          )}</strong> meminta: "${escapeHtml(
        notif.judul_dokumen || "Dokumen tidak disebutkan"
      )}"
        </div>
        <div class="notification-meta">
          <span class="notification-time">
            <i class="fas fa-clock"></i> ${timeAgo}
          </span>
          ${
            isUrgent
              ? `<span class="urgency-indicator urgency-${notif.urgency}">
            ${notif.urgency === "sangat_tinggi" ? "Sangat Urgent" : "Urgent"}
          </span>`
              : ""
          }
        </div>
      </div>
    `;
    })
    .join("");
}

// Update sidebar badge
function updateSidebarBadge(count) {
  // Find the pengaduan menu link
  const pengaduanLink = document.querySelector('a[href*="pengaduan"]');

  if (pengaduanLink) {
    // Remove existing badge
    const existingBadge = pengaduanLink.querySelector(".notification-badge");
    if (existingBadge) {
      existingBadge.remove();
    }

    // Add new badge if count > 0
    if (count > 0) {
      const badge = document.createElement("span");
      badge.className = "notification-badge";
      badge.textContent = count > 99 ? "99+" : count;

      // Make sure link has relative positioning
      pengaduanLink.style.position = "relative";
      pengaduanLink.appendChild(badge);
    }
  }
}

// Toggle notifications visibility
function toggleNotifications() {
  showingNotifications = !showingNotifications;

  const container = document.getElementById("floatingNotifications");
  if (!container) return;

  if (showingNotifications) {
    loadNotifications(); // Reload to show current notifications
  } else {
    container.innerHTML = ""; // Hide notifications
  }
}

// Dismiss single notification
function dismissNotification(id) {
  // Remove from local array
  notificationData = notificationData.filter((n) => n.id !== id);

  const container = document.getElementById("floatingNotifications");
  if (!container) return;

  // Re-render with remaining notifications
  if (notificationData.length > 0) {
    renderFloatingNotifications(notificationData.slice(0, 3));
  } else {
    container.innerHTML = "";

    // Update summary button if no notifications left
    const summary = document.getElementById("notificationSummary");
    if (summary && notificationData.length === 0) {
      summary.style.display = "none";
    }
  }
}

// Navigate to pengaduan page
function goToPengaduan(id = null) {
  const baseUrl = "/admin/pengaduan";
  const url = id ? `${baseUrl}?highlight=${id}` : baseUrl;
  window.location.href = url;
}

// Helper function to calculate time ago (FIXED)
function getTimeAgoFixed(dateString) {
  if (!dateString) return "Waktu tidak diketahui";

  try {
    // Parse tanggal dengan timezone yang benar
    const date = new Date(
      dateString + (dateString.includes("T") ? "" : "T00:00:00")
    );
    const now = new Date();

    // Adjust untuk timezone Indonesia (UTC+7)
    const diffInSeconds = Math.floor((now.getTime() - date.getTime()) / 1000);

    if (diffInSeconds < 0) return "Baru saja";
    if (diffInSeconds < 60) return "Baru saja";
    if (diffInSeconds < 3600)
      return Math.floor(diffInSeconds / 60) + " menit lalu";
    if (diffInSeconds < 86400)
      return Math.floor(diffInSeconds / 3600) + " jam lalu";
    if (diffInSeconds < 604800)
      return Math.floor(diffInSeconds / 86400) + " hari lalu";

    // Untuk lebih dari seminggu, tampilkan tanggal
    return date.toLocaleDateString("id-ID", {
      day: "numeric",
      month: "short",
      year: "numeric",
    });
  } catch (error) {
    console.error("Error parsing date:", error);
    return "Waktu tidak valid";
  }
}

// Helper function to escape HTML
function escapeHtml(text) {
  if (!text) return "";
  const map = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#039;",
  };
  return text.toString().replace(/[&<>"']/g, function (m) {
    return map[m];
  });
}

// Clean up interval when page unloads
function cleanupNotifications() {
  if (notificationCheckInterval) {
    clearInterval(notificationCheckInterval);
    notificationCheckInterval = null;
  }
}

// Initialize notifications when DOM is ready
document.addEventListener("DOMContentLoaded", function () {
  // Initialize notification system after a small delay to ensure page is ready
  setTimeout(initNotifications, 1000);
});

// Clean up on page unload
window.addEventListener("beforeunload", cleanupNotifications);

// Also clean up if user navigates away (for SPA-like behavior)
window.addEventListener("pagehide", cleanupNotifications);
