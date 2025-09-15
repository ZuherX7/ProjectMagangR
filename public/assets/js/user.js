document.addEventListener("DOMContentLoaded", function () {
  // Search functionality
  const searchForm = document.querySelector(".search-form");
  const searchInput = document.querySelector(".search-input");

  if (searchForm && searchInput) {
    // Auto-focus search on Ctrl/Cmd + K
    document.addEventListener("keydown", function (e) {
      if ((e.ctrlKey || e.metaKey) && e.key === "k") {
        e.preventDefault();
        searchInput.focus();
      }
    });

    // Search form validation
    searchForm.addEventListener("submit", function (e) {
      const query = searchInput.value.trim();
      if (!query) {
        e.preventDefault();
        searchInput.focus();
        showAlert("Masukkan kata kunci pencarian", "warning");
      }
    });

    // Search suggestions (simple implementation)
    let searchTimeout;
    searchInput.addEventListener("input", function () {
      clearTimeout(searchTimeout);
      const query = this.value.trim();

      if (query.length >= 3) {
        searchTimeout = setTimeout(() => {
          // Here you could implement AJAX search suggestions
          // fetchSearchSuggestions(query);
        }, 300);
      }
    });
  }

  // Menu card hover effects
  const menuCards = document.querySelectorAll(".menu-card");
  menuCards.forEach((card) => {
    const icon = card.querySelector(".menu-icon");

    card.addEventListener("mouseenter", function () {
      if (icon) {
        icon.style.transform = "scale(1.1) rotate(5deg)";
        icon.style.transition = "all 0.3s ease";
      }
    });

    card.addEventListener("mouseleave", function () {
      if (icon) {
        icon.style.transform = "scale(1) rotate(0deg)";
      }
    });
  });

  // Document card animations
  const documentCards = document.querySelectorAll(".document-card");
  documentCards.forEach((card, index) => {
    // Add staggered animation delay
    card.style.animationDelay = `${index * 0.1}s`;

    // Add hover effects for document actions
    const actionBtns = card.querySelectorAll(".btn-action");
    actionBtns.forEach((btn) => {
      btn.addEventListener("click", function (e) {
        // Add loading state
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
        this.style.pointerEvents = "none";

        // Reset after 2 seconds (for demo purposes)
        setTimeout(() => {
          this.innerHTML = originalText;
          this.style.pointerEvents = "auto";
        }, 2000);
      });
    });
  });

  // Smooth scrolling for internal links
  const internalLinks = document.querySelectorAll('a[href^="#"]');
  internalLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      const targetId = this.getAttribute("href").substring(1);
      const targetElement = document.getElementById(targetId);

      if (targetElement) {
        targetElement.scrollIntoView({
          behavior: "smooth",
          block: "start",
        });
      }
    });
  });

  // Logout confirmation
  const logoutBtn = document.querySelector(".logout-btn");
  if (logoutBtn) {
    logoutBtn.addEventListener("click", function (e) {
      e.preventDefault();

      if (confirm("Apakah Anda yakin ingin logout?")) {
        // Show loading state
        const originalText = this.textContent;
        this.textContent = "Logging out...";
        this.style.pointerEvents = "none";

        // Redirect after short delay
        setTimeout(() => {
          window.location.href = this.getAttribute("href");
        }, 500);
      }
    });
  }

  // Intersection Observer for scroll animations
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = "1";
        entry.target.style.transform = "translateY(0)";
      }
    });
  }, observerOptions);

  // Observe elements for scroll animations
  const animatedElements = document.querySelectorAll(
    ".menu-card, .document-card, .section-header"
  );
  animatedElements.forEach((el, index) => {
    el.style.opacity = "0";
    el.style.transform = "translateY(30px)";
    el.style.transition = `all 0.6s ease ${index * 0.1}s`;
    observer.observe(el);
  });

  // Dynamic document counter animation
  const docCounts = document.querySelectorAll(".doc-count");
  docCounts.forEach((counter) => {
    const targetNumber = parseInt(counter.textContent);
    if (targetNumber > 0) {
      animateCounter(counter, targetNumber);
    }
  });

  function animateCounter(element, target) {
    let current = 0;
    const increment = target / 30; // 30 frames
    const timer = setInterval(() => {
      current += increment;
      if (current >= target) {
        current = target;
        clearInterval(timer);
      }
      element.textContent = Math.floor(current) + " dokumen";
    }, 50);
  }

  // Toast/Alert system
  function showAlert(message, type = "info") {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll(".toast-alert");
    existingAlerts.forEach((alert) => alert.remove());

    // Create alert element
    const alert = document.createElement("div");
    alert.className = `toast-alert toast-${type}`;
    alert.innerHTML = `
            <div class="toast-content">
                <i class="fas fa-${getAlertIcon(type)}"></i>
                <span>${message}</span>
            </div>
            <button class="toast-close">&times;</button>
        `;

    // Add styles
    Object.assign(alert.style, {
      position: "fixed",
      top: "20px",
      right: "20px",
      background: getAlertColor(type),
      color: "white",
      padding: "15px 20px",
      borderRadius: "10px",
      boxShadow: "0 4px 12px rgba(0, 0, 0, 0.15)",
      zIndex: "9999",
      display: "flex",
      alignItems: "center",
      justifyContent: "space-between",
      minWidth: "300px",
      transform: "translateX(400px)",
      transition: "transform 0.3s ease",
    });

    // Add to document
    document.body.appendChild(alert);

    // Animate in
    setTimeout(() => {
      alert.style.transform = "translateX(0)";
    }, 10);

    // Close button functionality
    const closeBtn = alert.querySelector(".toast-close");
    closeBtn.addEventListener("click", () => {
      removeAlert(alert);
    });

    // Auto-hide after 4 seconds
    setTimeout(() => {
      removeAlert(alert);
    }, 4000);
  }

  function removeAlert(alert) {
    alert.style.transform = "translateX(400px)";
    setTimeout(() => {
      if (alert.parentNode) {
        alert.parentNode.removeChild(alert);
      }
    }, 300);
  }

  function getAlertIcon(type) {
    const icons = {
      info: "info-circle",
      success: "check-circle",
      warning: "exclamation-triangle",
      error: "times-circle",
    };
    return icons[type] || "info-circle";
  }

  function getAlertColor(type) {
    const colors = {
      info: "#3498db",
      success: "#27ae60",
      warning: "#f39c12",
      error: "#e74c3c",
    };
    return colors[type] || "#3498db";
  }

  // Handle form submissions with loading states
  const forms = document.querySelectorAll("form");
  forms.forEach((form) => {
    form.addEventListener("submit", function () {
      const submitBtn = form.querySelector(
        'button[type="submit"], input[type="submit"]'
      );
      if (submitBtn) {
        const originalText = submitBtn.textContent || submitBtn.value;
        submitBtn.textContent = "Loading...";
        submitBtn.disabled = true;

        // Reset after 3 seconds if form doesn't actually submit
        setTimeout(() => {
          submitBtn.textContent = originalText;
          submitBtn.disabled = false;
        }, 3000);
      }
    });
  });

  // Lazy loading for images
  const images = document.querySelectorAll("img[data-src]");
  const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const img = entry.target;
        img.src = img.dataset.src;
        img.removeAttribute("data-src");
        imageObserver.unobserve(img);
      }
    });
  });

  images.forEach((img) => imageObserver.observe(img));

  // Handle network status
  window.addEventListener("online", function () {
    showAlert("Koneksi internet tersambung kembali", "success");
  });

  window.addEventListener("offline", function () {
    showAlert("Koneksi internet terputus", "warning");
  });

  // Performance: Debounce scroll events
  let scrollTimeout;
  window.addEventListener("scroll", function () {
    clearTimeout(scrollTimeout);
    scrollTimeout = setTimeout(handleScroll, 10);
  });

  function handleScroll() {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    // Add shadow to navbar on scroll
    const navbar = document.querySelector(".top-navbar");
    if (navbar) {
      if (scrollTop > 10) {
        navbar.style.boxShadow = "0 4px 20px rgba(0, 0, 0, 0.15)";
      } else {
        navbar.style.boxShadow = "0 2px 10px rgba(0, 0, 0, 0.1)";
      }
    }

    // Show/hide scroll to top button (if exists)
    const scrollTopBtn = document.querySelector(".scroll-to-top");
    if (scrollTopBtn) {
      if (scrollTop > 500) {
        scrollTopBtn.style.opacity = "1";
        scrollTopBtn.style.pointerEvents = "auto";
      } else {
        scrollTopBtn.style.opacity = "0";
        scrollTopBtn.style.pointerEvents = "none";
      }
    }
  }

  // Add scroll to top functionality
  function addScrollToTop() {
    const scrollBtn = document.createElement("button");
    scrollBtn.className = "scroll-to-top";
    scrollBtn.innerHTML = '<i class="fas fa-chevron-up"></i>';

    Object.assign(scrollBtn.style, {
      position: "fixed",
      bottom: "30px",
      right: "30px",
      width: "50px",
      height: "50px",
      borderRadius: "50%",
      background: "linear-gradient(135deg, #4a6fa5 0%, #166ba0 100%)",
      color: "white",
      border: "none",
      cursor: "pointer",
      opacity: "0",
      pointerEvents: "none",
      transition: "all 0.3s ease",
      zIndex: "1000",
      boxShadow: "0 4px 15px rgba(0, 0, 0, 0.2)",
    });

    scrollBtn.addEventListener("click", function () {
      window.scrollTo({
        top: 0,
        behavior: "smooth",
      });
    });

    document.body.appendChild(scrollBtn);
  }

  // Initialize scroll to top button
  addScrollToTop();

  // Handle print functionality
  function handlePrint() {
    window.print();
  }

  // Expose global functions
  window.UserDashboard = {
    showAlert,
    handlePrint,
    animateCounter,
  };

  console.log("User dashboard loaded successfully");
});
