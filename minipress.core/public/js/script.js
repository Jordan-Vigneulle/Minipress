function setRulesNeutral() {
  ["taille", "lettre", "nombre", "chara_sp"].forEach((id) => {
    const el = document.getElementById(id);
    if (el) el.style.color = "grey";
  });
}

// On passe l'événement 'e' en paramètre pour savoir quel champ a déclenché l'action
function checkForm(e) {
  const passwordValue = e.target.value; // Cible dynamiquement password_reg OU new_password

  // Si le champ est vide, on remet tout en neutre
  if (passwordValue.length === 0) {
    setRulesNeutral();
    return;
  }

  const hasLength = passwordValue.length >= 8;
  const hasLetter = /[A-Za-z]/.test(passwordValue);
  const hasNumber = /\d/.test(passwordValue);
  const hasCharaSP = /[!@#$%^&*()\-+]/.test(passwordValue);

  // Petite fonction pour mettre à jour la couleur seulement si l'élément existe sur la page
  const updateColor = (id, isValid) => {
    const el = document.getElementById(id);
    if (el) el.style.color = isValid ? "green" : "red";
  };

  updateColor("taille", hasLength);
  updateColor("lettre", hasLetter);
  updateColor("nombre", hasNumber);
  updateColor("chara_sp", hasCharaSP);
}

document.addEventListener("DOMContentLoaded", () => {
  // On récupère soit le champ d'inscription, soit celui du profil
  const input =
    document.getElementById("password_reg") ||
    document.getElementById("new_password");

  if (input) {
    setRulesNeutral();
    input.addEventListener("input", checkForm);
    input.addEventListener("focus", checkForm);
  }

  // Dropdown avatar (toutes les pages)
  const profileBtn = document.getElementById("profileBtn");
  const dropdownMenu = document.getElementById("dropdownMenu");

  if (profileBtn && dropdownMenu) {
    profileBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      dropdownMenu.classList.toggle("show");
    });

    document.addEventListener("click", (e) => {
      if (!dropdownMenu.contains(e.target) && e.target !== profileBtn) {
        dropdownMenu.classList.remove("show");
      }
    });
  }
});

// --- Gestion du Modal Avatar (Page Profil) ---
const avatarModal = document.getElementById("avatarModal");
if (avatarModal) {
  avatarModal.addEventListener("click", function (e) {
    if (e.target === this) {
      this.classList.remove("show");
    }
  });
}
