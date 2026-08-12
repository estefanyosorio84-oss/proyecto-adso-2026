document.addEventListener("DOMContentLoaded", () => {
  // Seguridad y validaciones extra en el panel de administrador
  const deleteLinks = document.querySelectorAll(".btn-red");
  deleteLinks.forEach((link) => {
    link.addEventListener("click", (e) => {
      if (
        !confirm(
          "🚨 ALERTA: ¿Está seguro que desea eliminar este registro permanentemente de la base de datos?",
        )
      ) {
        e.preventDefault();
      }
    });
  });
});
