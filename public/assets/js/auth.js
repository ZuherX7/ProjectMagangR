document.addEventListener("DOMContentLoaded", function () {
  // Tab switching functionality
  const tabButtons = document.querySelectorAll(".tab-btn");
  const tabContents = document.querySelectorAll(".tab-content");

  tabButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const targetTab = this.getAttribute("data-tab");

      // Remove active class from all buttons and contents
      tabButtons.forEach((btn) => btn.classList.remove("active"));
      tabContents.forEach((content) => content.classList.remove("active"));

      // Add active class to clicked button and corresponding content
      this.classList.add("active");
      document.getElementById(targetTab + "-tab").classList.add("active");
    });
  });

  // Form validation
  const forms = document.querySelectorAll(".login-form");

  forms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      const inputs = form.querySelectorAll("input[required]");
      let isValid = true;

      inputs.forEach((input) => {
        if (!input.value.trim()) {
          isValid = false;
          input.classList.add("is-invalid");
        } else {
          input.classList.remove("is-invalid");
        }
      });

      // Special validation for NIP (should be numeric and at least 10 chars)
      const nipInput = form.querySelector('input[name="nip"]');
      if (nipInput && nipInput.value) {
        const nipValue = nipInput.value.trim();
        if (!/^\d{10,}$/.test(nipValue)) {
          isValid = false;
          nipInput.classList.add("is-invalid");

          // Show error message
          let errorDiv = nipInput.nextElementSibling;
          if (!errorDiv || !errorDiv.classList.contains("invalid-feedback")) {
            errorDiv = document.createElement("div");
            errorDiv.className = "invalid-feedback";
            nipInput.parentNode.insertBefore(errorDiv, nipInput.nextSibling);
          }
          errorDiv.textContent = "NIP harus berupa angka minimal 10 digit";
        }
      }

      if (!isValid) {
        e.preventDefault();
        showAlert("Mohon lengkapi semua field dengan benar", "error");
      }
    });
  });

  // Real-time input validation
  const allInputs = document.querySelectorAll("input");
  allInputs.forEach((input) => {
    input.addEventListener("blur", function () {
      if (this.hasAttribute("required") && !this.value.trim()) {
        this.classList.add("is-invalid");
      } else {
        this.classList.remove("is-invalid");

        // Remove custom error message if exists
        const errorDiv = this.nextElementSibling;
        if (
          errorDiv &&
          errorDiv.classList.contains("invalid-feedback") &&
          errorDiv.textContent.includes("NIP harus berupa angka")
        ) {
          errorDiv.remove();
        }
      }
    });

    input.addEventListener("input", function () {
      if (this.classList.contains("is-invalid") && this.value.trim()) {
        this.classList.remove("is-invalid");
      }
    });
  });

  // Auto-hide alerts after 5 seconds
  const alerts = document.querySelectorAll(".alert");
  alerts.forEach((alert) => {
    setTimeout(() => {
      alert.style.transition = "opacity 0.5s ease";
      alert.style.opacity = "0";
      setTimeout(() => {
        alert.remove();
      }, 500);
    }, 5000);
  });

  // Loading state for submit buttons
  const submitButtons = document.querySelectorAll(".btn-submit");
  submitButtons.forEach((button) => {
    const form = button.closest("form");
    form.addEventListener("submit", function () {
      button.textContent = "Loading...";
      button.disabled = true;

      // Re-enable after 3 seconds (in case of error)
      setTimeout(() => {
        button.textContent = "Submit";
        button.disabled = false;
      }, 3000);
    });
  });

  // Show alert function
  function showAlert(message, type) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll(".alert");
    existingAlerts.forEach((alert) => alert.remove());

    // Create new alert
    const alert = document.createElement("div");
    alert.className = `alert alert-${type === "error" ? "danger" : "success"}`;
    alert.textContent = message;

    // Insert alert
    const wrapper = document.querySelector(".login-wrapper");
    const tabNav = document.querySelector(".tab-nav");
    wrapper.insertBefore(alert, tabNav.nextSibling);

    // Auto-hide after 5 seconds
    setTimeout(() => {
      alert.style.transition = "opacity 0.5s ease";
      alert.style.opacity = "0";
      setTimeout(() => {
        alert.remove();
      }, 500);
    }, 5000);
  }

  // Keyboard navigation
  document.addEventListener("keydown", function (e) {
    if (e.key === "Tab") {
      // Let default tab behavior work
      return;
    }

    if (e.key === "Enter") {
      const activeForm = document.querySelector(
        ".tab-content.active .login-form"
      );
      if (activeForm) {
        const submitBtn = activeForm.querySelector(".btn-submit");
        if (submitBtn && !submitBtn.disabled) {
          submitBtn.click();
        }
      }
    }
  });

  // Switch to user tab if there's a NIP error
  const nipError = document.querySelector('input[name="nip"]');
  if (nipError && nipError.classList.contains("is-invalid")) {
    document.querySelector('[data-tab="user"]').click();
  }

  // Switch to admin tab if there's a username error
  const usernameError = document.querySelector('input[name="username"]');
  if (usernameError && usernameError.classList.contains("is-invalid")) {
    document.querySelector('[data-tab="admin"]').click();
  }
});
