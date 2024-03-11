document.addEventListener("DOMContentLoaded", function () {
  const quantityContainers = document.querySelectorAll(".quantity");

  quantityContainers.forEach((quantityContainer) => {
    const minusBtn = quantityContainer.querySelector(".minus");
    const plusBtn = quantityContainer.querySelector(".plus");
    const inputBox = quantityContainer.querySelector(".input-box");

    updateButtonStates();

    quantityContainer.addEventListener("click", handleContainerClick);
    inputBox.addEventListener("input", handleQuantityChange);

    function updateButtonStates() {
      const value = parseInt(inputBox.value);
      minusBtn.disabled = value <= 1;
      plusBtn.disabled = value >= parseInt(inputBox.max);
    }

    function handleContainerClick(event) {
      const menuId = event.target.dataset.menuId;
      if (event.target.classList.contains("minus")) {
        decreaseValue(menuId);
      } else if (event.target.classList.contains("plus")) {
        increaseValue(menuId);
      }
    }

    function increaseValue(menuId) {
      let inputBox = document.querySelector(
        'input[name="quantity_' + menuId + '"]'
      );
      if (inputBox) {
        let value = parseInt(inputBox.value);
        value = isNaN(value) ? 1 : Math.min(value + 1, parseInt(inputBox.max));
        inputBox.value = value;
        updateButtonStates();
        handleQuantityChange(menuId);
        updateLocalStorage(menuId, value);
      }
    }

    function decreaseValue(menuId) {
      let inputBox = document.querySelector(
        'input[name="quantity_' + menuId + '"]'
      );
      if (inputBox) {
        let value = parseInt(inputBox.value);
        value = isNaN(value) ? 1 : Math.max(value - 1, 1);
        inputBox.value = value;
        updateButtonStates();
        handleQuantityChange(menuId);
        updateLocalStorage(menuId, value);
      }
    }

    function handleQuantityChange(menuId) {
      updateTotalPrice();
      updateSession(
        menuId,
        parseInt(
          document.querySelector('input[name="quantity_' + menuId + '"]').value
        )
      );
    }

    function updateLocalStorage(menuId, quantity) {
      let storedMenuIds =
        JSON.parse(localStorage.getItem("selectedMenuIds")) || {};
      storedMenuIds[menuId] = quantity;
      localStorage.setItem("selectedMenuIds", JSON.stringify(storedMenuIds));
    }
  });

  calculateTotalPrice();
});

function calculateTotalPrice() {
  const totalItems = document.querySelectorAll(".cart-items");
  let total = 0;
  let orderCount = 0;

  totalItems.forEach((item) => {
    const price = parseFloat(item.querySelector(".price").textContent.substr(1));
    const quantity = parseInt(item.querySelector(".input-box").value);
    total += price * quantity;
    orderCount += quantity;
  });

  const totalPriceElement = document.getElementById("total-price");
  totalPriceElement.textContent = total.toFixed(2) + " ฿";

  // อัพเดท orderCount
  document.getElementById("orderCount").value = orderCount;

  updateTotalPrice();
  return total;
}


function removeCartItem(menuId) {
  var cartItem = document.getElementById("cart-item-" + menuId);
  if (cartItem) {
    cartItem.remove();
    updateTotalPrice();
  }
}

function updateTotalPrice() {
  var totalPrice = calculateTotalPrice();
  var totalPriceElement = document.getElementById("total-price");

  if (totalPriceElement) {
    var currentTotalPrice = parseFloat(
      totalPriceElement.textContent.replace("฿", "").trim()
    );

    if (isNaN(currentTotalPrice)) {
      currentTotalPrice = 0;
    }

    var newTotalPrice = currentTotalPrice - totalPrice;
    totalPriceElement.textContent = "฿" + newTotalPrice.toFixed(2);
    document.getElementById("total-price-input").value = newTotalPrice;
  }
}

function submitOrder() {
  document.getElementById("order-form").submit();
}

function updateSession(menuId, quantity) {
  var selectedMenuIds = document.querySelector('input[name="selectedMenuIds"]');
  var currentMenuIds = JSON.parse(selectedMenuIds.value);

  currentMenuIds[menuId.toString()] = quantity;
  selectedMenuIds.value = JSON.stringify(currentMenuIds);

  fetch("../order/update_session.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "selectedMenuIds=" + encodeURIComponent(selectedMenuIds.value),
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.text();
    })
    .then((data) => {
      console.log("Session updated successfully:", data);
    })
    .catch((error) => {
      console.error("Error updating session:", error);
    });
}

function updateDisplayValue() {
  const quantityContainers = document.querySelectorAll(".quantity");

  quantityContainers.forEach((quantityContainer) => {
    const inputBox = quantityContainer.querySelector(".input-box");
    const displayValue = quantityContainer.querySelector(".input-box-value");

    if (displayValue) {
      displayValue.innerText = inputBox.value;
    }
  });

  calculateTotalPrice();
}
