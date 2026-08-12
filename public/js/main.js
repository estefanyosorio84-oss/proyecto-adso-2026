document.addEventListener("DOMContentLoaded", () => {
  initCart();
  initAjaxSearch();
  initStoreAjaxFilter();
});

function initCart() {
  let cart = JSON.parse(localStorage.getItem("papeleria_cart")) || [];
  updateCartBadge(cart);

  document.querySelectorAll(".add-to-cart-btn").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      const qtyInput = document.getElementById("qty-input-" + btn.dataset.id);
      const product = {
        id: btn.dataset.id,
        name: btn.dataset.name,
        price: parseFloat(btn.dataset.price),
        sku: btn.dataset.sku,
        qty: qtyInput ? parseInt(qtyInput.value) : 1,
      };

      const existing = cart.find((i) => i.id === product.id);
      if (existing) {
        existing.qty += product.qty;
      } else {
        cart.push(product);
      }

      localStorage.setItem("papeleria_cart", JSON.stringify(cart));
      updateCartBadge(cart);

      Swal.fire({
        title: "¡Añadido!",
        text: "Producto agregado al carrito.",
        icon: "success",
        timer: 1500,
        showConfirmButton: false,
      });
    });
  });

  const checkoutContainer = document.getElementById("checkout-items");
  if (checkoutContainer) {
    let total = 0;
    let currentTotal = 0;
    let currentDiscount = 0;
    let currentCoupon = "";
    checkoutContainer.innerHTML = "";

    const discountEl = document.getElementById("checkout-discount");
    const finalTotalEl = document.getElementById("checkout-final-total");
    const couponFeedback = document.getElementById("coupon-feedback");
    const couponSummary = document.getElementById("coupon-summary");
    const couponInput = document.getElementById("coupon_code");
    const applyCouponBtn = document.getElementById("apply-coupon-btn");

    function renderTotals() {
      const totalEl = document.getElementById("checkout-total");
      if (totalEl) totalEl.textContent = currentTotal.toLocaleString("es-CO");
      if (discountEl)
        discountEl.textContent = currentDiscount.toLocaleString("es-CO");
      if (finalTotalEl)
        finalTotalEl.textContent = (
          currentTotal - currentDiscount
        ).toLocaleString("es-CO");
      if (couponSummary)
        couponSummary.classList.toggle("d-none", currentDiscount === 0);
    }

    if (cart.length === 0) {
      checkoutContainer.innerHTML = `<div class='text-center text-muted p-4'>Su carrito está vacío.<br><a href='${URLROOT}/product/store' class='btn btn-primary mt-3'>Ir a la tienda</a></div>`;
      const btnConfirm = document.getElementById("confirm-reservation-btn");
      if (btnConfirm) btnConfirm.style.display = "none";
      if (couponFeedback) couponFeedback.classList.add("d-none");
      if (couponSummary) couponSummary.classList.add("d-none");
    } else {
      cart.forEach((item, index) => {
        total += item.price * item.qty;
        checkoutContainer.innerHTML += `
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">${item.name}</h6>
                            <small class="text-muted">SKU: ${item.sku} | Cant.: ${item.qty}</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-primary fs-5">$${(item.price * item.qty).toLocaleString("es-CO")}</div>
                            <button class="btn btn-sm text-danger p-0 mt-1" onclick="removeItem(${index})"><i class="fas fa-trash"></i> Eliminar</button>
                        </div>
                    </div>`;
      });
      currentTotal = total;
      renderTotals();
    }

    const confirmBtn = document.getElementById("confirm-reservation-btn");
    if (applyCouponBtn && couponInput) {
      applyCouponBtn.addEventListener("click", () => {
        const code = couponInput.value.trim();
        if (!code) {
          Swal.fire("Atención", "Ingrese un código de cupón.", "warning");
          return;
        }

        applyCouponBtn.disabled = true;
        applyCouponBtn.innerHTML =
          '<i class="fas fa-spinner fa-spin"></i> Validando...';

        fetch(URLROOT + "/cart/applyCoupon", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ code, total: total }),
        })
          .then((res) => res.json())
          .then((data) => {
            applyCouponBtn.disabled = false;
            applyCouponBtn.innerHTML = "Aplicar";

            if (data.status === "success") {
              currentCoupon = data.coupon_code;
              currentDiscount = parseFloat(data.discount_amount);
              currentTotal = parseFloat(data.final_total) + currentDiscount;
              if (couponFeedback) {
                couponFeedback.textContent = data.message;
                couponFeedback.classList.remove("d-none");
                couponFeedback.classList.remove("text-danger");
                couponFeedback.classList.add("text-success");
              }
              renderTotals();
            } else {
              currentCoupon = "";
              currentDiscount = 0;
              renderTotals();
              if (couponFeedback) {
                couponFeedback.textContent = data.message;
                couponFeedback.classList.remove("d-none");
                couponFeedback.classList.remove("text-success");
                couponFeedback.classList.add("text-danger");
              }
            }
          })
          .catch(() => {
            applyCouponBtn.disabled = false;
            applyCouponBtn.innerHTML = "Aplicar";
            Swal.fire(
              "Error",
              "No se pudo validar el cupón. Intente de nuevo.",
              "error",
            );
          });
      });
    }

    if (confirmBtn) {
      confirmBtn.addEventListener("click", () => {
        const shippingAddress =
          document.getElementById("shipping_address").value;
        const billingData = document.getElementById("billing_data").value;

        if (!shippingAddress.trim()) {
          Swal.fire(
            "Atención",
            "La dirección de envío es obligatoria.",
            "warning",
          );
          return;
        }

        confirmBtn.disabled = true;
        confirmBtn.innerHTML =
          '<i class="fas fa-spinner fa-spin"></i> Procesando...';

        fetch(URLROOT + "/cart/process", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            items: cart,
            total_amount: parseFloat(
              (currentTotal - currentDiscount).toFixed(2),
            ),
            discount_amount: currentDiscount,
            coupon_code: currentCoupon,
            shipping_address: shippingAddress,
            billing_data: billingData,
          }),
        })
          .then((res) => res.json())
          .then((data) => {
            if (data.status === "success") {
              localStorage.removeItem("papeleria_cart");
              Swal.fire({
                title: "¡Reserva Exitosa!",
                html: `Su código de pago es: <strong class="fs-3 text-primary d-block my-3">${data.order_code}</strong>Acérquese a nuestro local físico con este código para pagar y retirar su compra.`,
                icon: "success",
                confirmButtonColor: "#1a73e8",
                confirmButtonText: "Entendido, volver al inicio",
                allowOutsideClick: false,
              }).then(() => {
                window.location.href = URLROOT + "/";
              });
            } else {
              Swal.fire("Error", data.message, "error");
              confirmBtn.disabled = false;
              confirmBtn.innerHTML =
                '<i class="fas fa-check-circle me-2"></i> Generar Código de Pedido';
            }
          })
          .catch((err) => {
            Swal.fire("Error", "Ocurrió un error en el servidor.", "error");
            confirmBtn.disabled = false;
            confirmBtn.innerHTML =
              '<i class="fas fa-check-circle me-2"></i> Generar Código de Pedido';
          });
      });
    }
  }
}

window.removeItem = function (index) {
  let cart = JSON.parse(localStorage.getItem("papeleria_cart")) || [];
  cart.splice(index, 1);
  localStorage.setItem("papeleria_cart", JSON.stringify(cart));
  initCart();
};

function updateCartBadge(cart) {
  const badge = document.getElementById("cart-counter");
  if (badge) badge.textContent = cart.reduce((sum, item) => sum + item.qty, 0);
}

function initAjaxSearch() {
  const input = document.getElementById("ajax-search-input");
  const results = document.getElementById("search-results");
  let timeout = null;

  if (!input || !results) return;

  input.addEventListener("input", (e) => {
    clearTimeout(timeout);
    const q = e.target.value.trim();

    if (q.length >= 2) {
      timeout = setTimeout(() => {
        fetch(URLROOT + "/ajax/search?q=" + encodeURIComponent(q))
          .then((res) => res.json())
          .then((data) => {
            results.innerHTML = "";
            results.classList.remove("hidden");
            if (data.length > 0) {
              data.forEach((item) => {
                const imgSrc = item.main_image.startsWith("http")
                  ? item.main_image
                  : URLROOT + "/public" + item.main_image;
                results.innerHTML += `
                                <a href="${URLROOT}/product/detail/${item.id}" class="search-item">
                                    <img src="${imgSrc}" width="50" height="50" class="object-fit-cover rounded me-3 border">
                                    <div>
                                        <div class="fw-bold fs-6 text-dark">${item.name}</div>
                                        <div class="text-primary fw-semibold">$${Number(item.price).toLocaleString("es-CO")}</div>
                                    </div>
                                </a>`;
              });
            } else {
              results.innerHTML =
                '<div class="p-3 text-muted text-center">No se encontraron productos.</div>';
            }
          });
      }, 300);
    } else {
      results.classList.add("hidden");
    }
  });

  document.addEventListener("click", (e) => {
    if (!e.target.closest(".search-container")) results.classList.add("hidden");
  });
}

// Filtro AJAX en la página de Tienda (Store)
function initStoreAjaxFilter() {
  const storeInput = document.getElementById("store-ajax-search");
  const storeGrid = document.getElementById("store-product-grid");
  if (!storeInput || !storeGrid) return;

  let timeout = null;
  storeInput.addEventListener("input", (e) => {
    clearTimeout(timeout);
    const q = e.target.value.trim();

    timeout = setTimeout(() => {
      const url = new URL(window.location.href);
      url.searchParams.set("q", q);
      // Hacer fetch a la misma página y extraer solo el grid (Evita recargar toda la página)
      fetch(url.toString())
        .then((res) => res.text())
        .then((html) => {
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, "text/html");
          const newGrid = doc.getElementById("store-product-grid");
          if (newGrid) {
            storeGrid.innerHTML = newGrid.innerHTML;
            initCart(); // Reiniciar listeners de botones
          }
        });
    }, 500);
  });
}
