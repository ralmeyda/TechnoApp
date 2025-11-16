document.addEventListener("DOMContentLoaded", () => {
  const cartIcon = document.querySelector("#cart-icon");
  const cart = document.querySelector(".cart");
  const cartClose = document.querySelector("#cart-close");
  const cartContent = document.querySelector(".cart-content");
  const notification = document.getElementById("cart-notification");
  const buyNowButton = document.querySelector(".btn-buy");
  let cartItemCount = 0;

  // Open/close cart
  cartIcon.addEventListener("click", () => cart.classList.add("active"));
  cartClose.addEventListener("click", () => cart.classList.remove("active"));

  // Add to cart
  document.querySelectorAll(".add-cart").forEach(button => {
    button.addEventListener("click", e => {
      const isLoggedIn = <?php echo json_encode(isLoggedIn()); ?>;
      if (!isLoggedIn) {
        alert("Please log in before adding items to your cart.");
        window.location.href = "login.php";
        return;
      }

      const btn = e.currentTarget;
      const productBox = btn.closest(".product-box");
      const title = productBox.querySelector(".product-title").textContent;
      const price = parseFloat(productBox.querySelector(".price").textContent);
      const imgSrc = productBox.querySelector("img").src;

      // Prevent duplicate
      if ([...cartContent.querySelectorAll(".cart-product-title")].some(el => el.textContent === title)) {
        alert("This item is already in the cart.");
        return;
      }

      // Create cart item
      const cartBox = document.createElement("div");
      cartBox.classList.add("cart-box");
      cartBox.innerHTML = `
        <img src="${imgSrc}" class="cart-img">
        <div class="cart-detail">
          <h2 class="cart-product-title">${title}</h2>
          <span class="cart-price">${price}</span>
          <div class="cart-quantity">
            <button class="decrement">-</button>
            <span class="number">1</span>
            <button class="increment">+</button>
          </div>
        </div>
        <i class="ri-delete-bin-line cart-remove"></i>
      `;
      cartContent.appendChild(cartBox);

      // Quantity buttons
      const numberEl = cartBox.querySelector(".number");
      cartBox.querySelector(".decrement").addEventListener("click", () => {
        let qty = parseInt(numberEl.textContent);
        if (qty > 1) {
          numberEl.textContent = --qty;
          updateTotal();
        }
      });
      cartBox.querySelector(".increment").addEventListener("click", () => {
        let qty = parseInt(numberEl.textContent);
        numberEl.textContent = ++qty;
        updateTotal();
      });

      // Remove button
      cartBox.querySelector(".cart-remove").addEventListener("click", () => {
        cartBox.remove();
        updateCount(-1);
        updateTotal();
      });

      // Show notification
      notification.classList.add("show");
      setTimeout(() => notification.classList.remove("show"), 1500);

      updateCount(1);
      updateTotal();
    });
  });

  // Update cart count
  function updateCount(change) {
    const badge = document.querySelector(".cart-item-count");
    cartItemCount += change;
    if (cartItemCount > 0) {
      badge.style.visibility = "visible";
      badge.textContent = cartItemCount;
    } else {
      badge.style.visibility = "hidden";
      badge.textContent = "";
    }
  }

  // Update total price
  function updateTotal() {
    let total = 0;
    cartContent.querySelectorAll(".cart-box").forEach(box => {
      const price = parseFloat(box.querySelector(".cart-price").textContent);
      const qty = parseInt(box.querySelector(".number").textContent);
      total += price * qty;
    });
    document.querySelector(".total-price").textContent = `PHP${total.toFixed(2)}`;
  }

  // Buy now
  buyNowButton.addEventListener("click", () => {
    if (cartContent.children.length === 0) {
      alert("Your cart is empty!");
      return;
    }
    cartContent.innerHTML = "";
    cartItemCount = 0;
    updateCount(0);
    updateTotal();
    alert("Thank you for your purchase!");
  });
});
