import "./bootstrap.js";
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import "./styles/app.css";

console.log("This log comes from assets/app.js - welcome to AssetMapper! 🎉");

document.addEventListener("turbo:load", function () {
  // Sélectionner tous les boutons de fermeture des toasts
  const closeButtons = document.querySelectorAll('[aria-label="Close"]');

  if (closeButtons) {
    closeButtons.forEach((button) => {
      button.addEventListener("click", function () {
        // Trouver le parent le plus proche qui est le toast
        const toast = this.closest('[role="alert"]');
        if (toast) {
          // Ajouter une classe pour l'animation de fermeture
          toast.classList.add(
            "opacity-0",
            "transition-opacity",
            "duration-300"
          );
          // Supprimer le toast après l'animation
          setTimeout(() => {
            toast.remove();
          }, 300);
        }
      });
    });

    // Optionnel : Fermeture automatique après un certain temps
    setTimeout(() => {
      const toasts = document.querySelectorAll('[role="alert"]');
      toasts.forEach((toast) => {
        toast.classList.add("opacity-0", "transition-opacity", "duration-300");
        setTimeout(() => {
          toast.remove();
        }, 300);
      });
    }, 5000); // Ferme automatiquement après 5 secondes
  }

  // Complete competence
  let container = document.getElementById("languages");
  const addButton = document.getElementById("add-language");

  if (container && addButton) {
    function addLanguage() {
      let index = container.children.length;

      addButton.addEventListener("click", function () {
        let prototype = container.dataset.prototype;
        let newForm = prototype.replace(/__name__/g, index);

        let newItem = document.createElement("div");
        newItem.classList.add(
          "collection-item",
          "bg-gray-50",
          "p-4",
          "rounded-md",
          "relative"
        );
        newItem.innerHTML = `
            <button type="button" class="remove-button absolute top-2 right-2 text-red-500 hover:text-red-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            ${newForm}
        `;

        container.appendChild(newItem);
        index++;

        newItem
          .querySelector(".remove-button")
          .addEventListener("click", function () {
            confirmRemoval(newItem);
          });
      });
    }

    function confirmRemoval(element) {
      if (confirm("Êtes-vous sûr de vouloir supprimer cet élément ?")) {
        element.remove();
      }
    }

    addLanguage();

    // Style des checkboxes pour les permis
    styleCheckboxes();

    function styleCheckboxes() {
      document
        .querySelectorAll(".license-checkbox")
        .forEach(function (container) {
          const checkbox = container.querySelector('input[type="checkbox"]');
          const label = container.querySelector("label");

          if (checkbox && label) {
            checkbox.addEventListener("change", function () {
              if (this.checked) {
                container.classList.add("bg-blue-50");
              } else {
                container.classList.remove("bg-blue-50");
              }
            });

            if (checkbox.checked) {
              container.classList.add("bg-blue-50");
            }
          }
        });
    }
  }

  // Languages edit
  const collectionsHolder = document.getElementById("languages-collection");

  if (collectionsHolder && addButton) {
    let index = parseInt(collectionsHolder.dataset.index || 0);

    // Fonction pour ajouter un formulaire de langue
    function addLanguageForm() {
      // Récupérer et préparer le prototype
      const prototype = collectionsHolder.dataset.prototype;
      const newForm = prototype.replace(/__name__/g, index);

      // Créer le conteneur de l'élément
      const item = document.createElement("div");
      item.classList.add(
        "language-item",
        "bg-gray-50",
        "p-4",
        "rounded-md",
        "relative"
      );
      item.innerHTML = newForm;

      // Ajouter des classes aux champs pour le style
      const inputs = item.querySelectorAll("input, select");
      inputs.forEach((input) => {
        input.classList.add("mb-2", "w-full", "p-2", "border", "rounded");
      });

      // Créer le bouton de suppression
      const removeButton = document.createElement("button");
      removeButton.type = "button";
      removeButton.classList.add(
        "remove-language",
        "absolute",
        "top-1",
        "right-2",
        "text-red-500",
        "hover:text-red-700"
      );
      removeButton.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
      <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
      </svg>
      `;

      // Ajouter l'écouteur pour la suppression
      removeButton.addEventListener("click", function () {
        if (confirm("Êtes-vous sûr de vouloir supprimer cette langue ?")) {
          item.remove();
        }
      });

      // Ajouter le bouton et l'élément complet au DOM
      item.appendChild(removeButton);
      collectionsHolder.appendChild(item);

      // Incrémenter l'index pour le prochain élément
      index++;
      collectionsHolder.dataset.index = index;
    }

    // Ajouter l'écouteur au bouton d'ajout
    addButton.addEventListener("click", addLanguageForm);

    // Ajouter les écouteurs aux boutons de suppression existants
    document.querySelectorAll(".remove-language").forEach((button) => {
      button.addEventListener("click", function () {
        if (confirm("Êtes-vous sûr de vouloir supprimer cette langue ?")) {
          this.closest(".language-item").remove();
        }
      });
    });
  }
});
