// Wrapping dans un DOMContentLoaded pour s'assurer que le DOM est chargé
document.addEventListener("DOMContentLoaded", () => {
  fetch("http://localhost/ecommerce-sneakers/backend/index.php")
    .then((response) => {
      console.log("Réponse brute:", response);
      if (!response.ok) {
        throw new Error("Erreur réseau");
      }
      return response.text().then((text) => {
        console.log("Texte reçu:", text);
        if (!text) {
          return [];
        }
        return JSON.parse(text);
      });
    })
    .then((data) => {
      console.log("Données parsées:", data);
      // Vérifie si on a une erreur de la BDD
      if (data.error) {
        throw new Error(data.error);
      }

      const produitsContainer = document.getElementById("products");
      if (!Array.isArray(data)) {
        throw new Error("Format de données invalide");
      }

      produitsContainer.innerHTML = data
        .map(
          (produit) => `
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <img src="${
                  produit.image_url ||
                  "https://images.unsplash.com/photo-1552346154-21d32810aba3"
                }" 
                     alt="${produit.nom}" 
                     class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold">${produit.nom}</h3>
                    <p class="text-gray-600 mt-2">${produit.description}</p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-2xl font-bold">${
                          produit.prix
                        } €</span>
                        <button onclick="addToCart({
                            id: ${produit.produit_id},
                            nom: '${produit.nom}',
                            prix: ${produit.prix},
                            image: '${produit.image_url}'
                        })" 
                        class="bg-black text-white px-6 py-2 rounded-full hover:bg-gray-800">
                            Ajouter au panier
                        </button>
                    </div>
                </div>
            </div>
          `
        )
        .join("");
    })
    .catch((error) => {
      console.error("Erreur détaillée:", error);
      document.getElementById("products").innerHTML = `
        <div class="text-center text-red-600 p-4">
          Erreur lors du chargement des produits: ${error.message}
        </div>
      `;
    });
});
