document.addEventListener("DOMContentLoaded", () => {
  const loginForm = document.getElementById("loginForm");
  const registerForm = document.getElementById("registerForm");

  loginForm?.addEventListener("submit", async (e) => {
    e.preventDefault();

    try {
      const response = await fetch(
        "http://localhost/ecommerce-sneakers/backend/auth.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            action: "login",
            email: e.target.email.value,
            password: e.target.password.value,
          }),
        }
      );

      const data = await response.json();

      if (data.success) {
        // Stockage des infos utilisateur
        localStorage.setItem("user", JSON.stringify(data.user));
        // Redirection vers la page d'accueil
        window.location.href = "index.html";
      } else {
        alert(data.error || "Erreur de connexion");
      }
    } catch (error) {
      console.error("Erreur:", error);
      alert("Erreur de connexion au serveur");
    }
  });

  registerForm?.addEventListener("submit", async (e) => {
    e.preventDefault();

    try {
      const response = await fetch(
        "http://localhost/ecommerce-sneakers/backend/auth.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            action: "register",
            nom: e.target.name.value,
            email: e.target.email.value,
            password: e.target.password.value,
          }),
        }
      );

      const data = await response.json();

      if (data.success) {
        alert("Inscription réussie !");
        window.location.reload();
      } else {
        alert(data.error || "Erreur lors de l'inscription");
      }
    } catch (error) {
      console.error("Erreur:", error);
      alert("Erreur de connexion au serveur");
    }
  });
});
