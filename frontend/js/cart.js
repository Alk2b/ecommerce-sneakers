// Récupération du panier depuis le localStorage
let cart = JSON.parse(localStorage.getItem("cart") || "[]");

// Mise à jour de l'affichage du panier
function updateCartDisplay() {
  const cartItems = document.getElementById("cartItems");
  const emptyCart = document.getElementById("emptyCart");
  const subtotalElement = document.getElementById("cartSubtotal");
  const totalElement = document.getElementById("cartTotal");

  // Afficher/masquer le message de panier vide
  if (cart.length === 0) {
    if (cartItems) cartItems.classList.add("hidden");
    if (emptyCart) emptyCart.classList.remove("hidden");
    return;
  }

  if (cartItems) {
    cartItems.classList.remove("hidden");
    if (emptyCart) emptyCart.classList.add("hidden");

    // Génération du HTML pour chaque article
    cartItems.innerHTML = cart
      .map(
        (item) => `
            <div class="bg-white p-6 rounded-lg shadow-md flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <img src="${item.image}" alt="${item.nom}" class="w-24 h-24 object-cover rounded">
                    <div>
                        <h3 class="font-bold text-lg">${item.nom}</h3>
                        <p class="text-gray-600">${item.prix} € x ${item.quantity}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center border rounded">
                        <button onclick="updateQuantity(${item.id}, -1)" class="px-3 py-1 hover:bg-gray-100">-</button>
                        <span class="px-4 py-1 border-x">${item.quantity}</span>
                        <button onclick="updateQuantity(${item.id}, 1)" class="px-3 py-1 hover:bg-gray-100">+</button>
                    </div>
                    <button onclick="removeFromCart(${item.id})" class="text-red-500 hover:text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        `
      )
      .join("");

    // Calcul et affichage des totaux
    const subtotal = cart.reduce(
      (sum, item) => sum + item.prix * item.quantity,
      0
    );
    if (subtotalElement)
      subtotalElement.textContent = `${subtotal.toFixed(2)} €`;
    if (totalElement) totalElement.textContent = `${subtotal.toFixed(2)} €`;
  }

  // Mise à jour du compteur dans la navigation
  const cartCount = document.getElementById("cartCount");
  if (cartCount) {
    cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
  }
}

function updateCartTotal() {
  const total = cart.reduce((sum, item) => sum + item.prix * item.quantity, 0);
  document.getElementById("cartTotal").textContent = `${total.toFixed(2)} €`;
}

// Ajouter au panier
function addToCart(product) {
  const existingItem = cart.find((item) => item.id === product.id);

  if (existingItem) {
    existingItem.quantity += 1;
  } else {
    cart.push({ ...product, quantity: 1 });
  }

  localStorage.setItem("cart", JSON.stringify(cart));
  updateCartDisplay();
}

// Mettre à jour la quantité
function updateQuantity(productId, change) {
  const item = cart.find((item) => item.id === productId);
  if (item) {
    item.quantity += change;
    if (item.quantity <= 0) {
      removeFromCart(productId);
      return;
    }
    localStorage.setItem("cart", JSON.stringify(cart));
    updateCartDisplay();
  }
}

// Supprimer du panier
function removeFromCart(productId) {
  cart = cart.filter((item) => item.id !== productId);
  localStorage.setItem("cart", JSON.stringify(cart));
  updateCartDisplay();
}

// Passer la commande
function checkout() {
  const user = JSON.parse(localStorage.getItem("user"));

  if (!user) {
    window.location.href = "login.html";
    return;
  }

  if (!cart.length) {
    alert("Votre panier est vide");
    return;
  }

  // URL modifiée pour pointer vers index.php au lieu de api.php
  fetch("http://localhost/ecommerce-sneakers/backend/index.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      client_id: user.id,
      produits: cart.map((item) => ({
        produit_id: item.id,
        quantite: item.quantity,
        prix: item.prix,
      })),
    }),
  })
    .then(async (response) => {
      const text = await response.text();

      try {
        const data = JSON.parse(text);
        if (data.success) {
          localStorage.removeItem("cart");
          cart = [];
          updateCartDisplay();
          alert("Commande validée avec succès !");
          window.location.href = "profile.html";
        } else {
          throw new Error(data.error || "Erreur lors de la commande");
        }
      } catch (e) {
        console.error("Erreur parsing JSON:", e);
        throw new Error("Réponse serveur invalide");
      }
    })
    .catch((error) => {
      console.error("Erreur détaillée:", error);
      alert("Une erreur est survenue lors de la validation de la commande");
    });
}

// Initialisation au chargement de la page
document.addEventListener("DOMContentLoaded", updateCartDisplay);
