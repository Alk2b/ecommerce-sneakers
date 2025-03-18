function updateNavigation() {
  const user = JSON.parse(localStorage.getItem("user"));
  const loginLink = document.querySelector('a[href="login.html"]');

  if (user && loginLink) {
    loginLink.href = "profile.html";
    loginLink.textContent = "Mon compte";
  }
}

document.addEventListener("DOMContentLoaded", updateNavigation);
