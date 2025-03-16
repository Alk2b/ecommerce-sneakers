// Récupérer les produits via l'API
fetch('http://localhost/backend/api/produits')
    .then(response => response.json())
    .then(produits => {
        const produitsContainer = document.getElementById('produits');
        produits.forEach(produit => {
            const produitDiv = document.createElement('div');
            produitDiv.className = 'bg-white p-6 rounded-lg shadow-md';
            produitDiv.innerHTML = `
                <h2 class="text-xl font-semibold">${produit.nom}</h2>
                <p class="text-gray-600 mt-2">${produit.description}</p>
                <p class="text-lg font-bold mt-4">${produit.prix} €</p>
                <button onclick="ajouterAuPanier(${produit.produit_id})" class="bg-blue-500 text-white px-4 py-2 rounded mt-4 w-full">
                    Ajouter au panier
                </button>
            `;
            produitsContainer.appendChild(produitDiv);
        });
    });

function ajouterAuPanier(produit_id) {
    // Ajouter le produit au panier via l'API
    fetch('http://localhost/backend/api/panier', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ produit_id: produit_id, quantite: 1 })
    }).then(response => response.json())
      .then(data => alert('Produit ajouté au panier'));
}