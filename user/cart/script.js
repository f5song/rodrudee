document.addEventListener("DOMContentLoaded", function () {
  const quantityContainers = document.querySelectorAll(".quantity");

  quantityContainers.forEach((quantityContainer) => {
    const minusBtn = quantityContainer.querySelector(".minus");
    const plusBtn = quantityContainer.querySelector(".plus");
    const inputBox = quantityContainer.querySelector(".input-box");
    const displayValue = quantityContainer.querySelector(".input-box-value");

    updateButtonStates();

    quantityContainer.addEventListener("click", handleContainerClick);
    inputBox.addEventListener("input", handleQuantityChange);

    function updateButtonStates(menuId) {
      var inputBox = document.querySelector('input[name="quantity_' + menuId + '"]');
      var minusBtn = inputBox.parentNode.querySelector(".minus");
      var plusBtn = inputBox.parentNode.querySelector(".plus");
  
      var value = parseInt(inputBox.value);
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
  var inputBox = document.querySelector('input[name="quantity_' + menuId + '"]');
  if (inputBox) {
      var value = parseInt(inputBox.value);
      value = isNaN(value) ? 1 : Math.min(value + 1, parseInt(inputBox.max));
      inputBox.value = value;
      console.log("Increased value:", value);
      updateButtonStates(menuId);
      handleQuantityChange(menuId);
  } else {
      console.error("inputBox is null for menuId:", menuId);
  }
}

function decreaseValue(menuId) {
  var inputBox = document.querySelector('input[name="quantity_' + menuId + '"]');
  if (inputBox) {
      var value = parseInt(inputBox.value);
      value = isNaN(value) ? 1 : Math.max(value - 1, 1);
      inputBox.value = value;
      console.log("Decreased value:", value);
      updateButtonStates(menuId);
      handleQuantityChange(menuId);
  } else {
      console.error("inputBox is null for menuId:", menuId);
  }
}

function handleQuantityChange(menuId) {
  let inputBox = document.querySelector('input[name="quantity_' + menuId + '"]');
  let value = parseInt(inputBox.value);
  value = isNaN(value) ? 1 : value;
  console.log("Menu ID:", menuId, "Quantity changed:", value);

  updateDisplayValue(menuId);
  updateSession(menuId, value); // เรียกฟังก์ชันเพื่ออัปเดตที่ server-side
}

    function updateDisplayValue() {
      const quantityContainers = document.querySelectorAll(".quantity");

      quantityContainers.forEach((quantityContainer) => {
        const inputBox = quantityContainer.querySelector(".input-box");
        const displayValue =
          quantityContainer.querySelector(".input-box-value");

        if (displayValue) {
          // ตรวจสอบว่า displayValue ไม่เป็น null ก่อนที่จะใช้
          displayValue.innerText = inputBox.value;
        }
      });
    }
  });

  calculateTotalPrice();
});

function calculateTotalPrice() {
  const totalItems = document.querySelectorAll(".cart-items");
  let total = 0;

  totalItems.forEach((item) => {
    const price = parseFloat(
      item.querySelector(".price").textContent.substr(1)
    );
    const quantity = parseInt(item.querySelector(".input-box").value);
    total += price * quantity;
  });

  const totalPriceElement = document.getElementById("total-price");
  totalPriceElement.textContent = total.toFixed(2) + " ฿";

  return total;
}

function removeCartItem(menuId) {
  var cartItem = document.getElementById("cart-item-" + menuId);
  if (cartItem) {
    cartItem.remove();
    console.log("Item found and removed:", cartItem);
    updateTotalPrice();
  } else {
    console.log("Item not found with menuId:", menuId);
  }
}

function updateTotalPrice() {
  var totalPrice = calculateTotalPrice();
  var totalPriceElement = document.getElementById("total-price");

  if (totalPriceElement) {
    // ตรวจสอบว่า totalPriceElement ไม่เป็น null
    var currentTotalPrice = parseFloat(
      totalPriceElement.textContent.replace("฿", "").trim()
    );

    if (isNaN(currentTotalPrice)) {
      currentTotalPrice = 0;
    }

    var newTotalPrice = currentTotalPrice - totalPrice;
    totalPriceElement.textContent = "฿" + newTotalPrice.toFixed(2);
    document.getElementById("total-price-input").value = newTotalPrice;
  } else {
    console.error("totalPriceElement is null"); // ใส่การแจ้งเตือนหรือจัดการตามที่ต้องการ
  }
}

function submitOrder() {
  document.getElementById("order-form").submit();
}

function updateSession(menuId, quantity) {
  // ดึงข้อมูลจาก input ที่มีชื่อเป็น selectedMenuIds
  var selectedMenuIds = document.querySelector('input[name="selectedMenuIds"]');
  var currentMenuIds = JSON.parse(selectedMenuIds.value);

  // อัปเดตจำนวนรายการของเมนู
  currentMenuIds[menuId.toString()] = quantity;

  // อัปเดตค่าใน input
  selectedMenuIds.value = JSON.stringify(currentMenuIds);

  // ส่ง request โดยใช้ Fetch API
  fetch("update_session.php", {
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
