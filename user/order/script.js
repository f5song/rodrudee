
function filterItems() {
    var regionFilter = document.querySelector('input[name="region"]:checked');
    var typeCheckboxes = document.querySelectorAll('#foodTypes input[name="foodType"]');
    var checkedTypeValues = [];

    typeCheckboxes.forEach((checkbox) => {
        if (checkbox.checked) {
            checkedTypeValues.push(checkbox.value);
        }
    });

    var items = document.querySelectorAll('.food-menu-container');
    items.forEach(function(item) {
        var shouldDisplay = true;

        if (regionFilter) {
            shouldDisplay = item.dataset.region === regionFilter.value;
        }

        if (checkedTypeValues.length > 0 && shouldDisplay) {
            shouldDisplay = checkedTypeValues.includes(item.dataset.type);
        }

        item.style.display = shouldDisplay ? 'block' : 'none';
    });

    typeCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', function() {
            checkedTypeValues = [];
            typeCheckboxes.forEach((currentCheckbox) => {
                ห
                if (currentCheckbox.checked) {
                    checkedTypeValues.push(currentCheckbox.value);
                }
            });

            items.forEach((item) => {
                var shouldDisplay = true;

                if (regionFilter) {
                    shouldDisplay = item.dataset.region === regionFilter.value;
                }

                if (checkedTypeValues.length > 0 && shouldDisplay) {
                    shouldDisplay = checkedTypeValues.includes(item.dataset.type);
                }

                item.style.display = shouldDisplay ? 'block' : 'none';
            });
        });
    });
}


function updateSession() {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_session.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    var data = 'selectedMenuIds=' + JSON.stringify(selectedMenuIds);
    xhr.send(data);
}





function goToAnotherPage() {
    var selectedTable = sessionStorage.getItem('selectedTable');
    if (selectedTable) {
        window.location.href = "../cart/cart.php?table=" + selectedTable;
    } else {
        alert('กรุณาเลือกโต๊ะก่อนทำการสั่งอาหาร');
    }
}



window.addEventListener('beforeunload', function() {
    // ทำการร้องขอแบบไม่สะดวกต่อเซิร์ฟเวอร์เพื่อยกเลิก session
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'unset_session.php', true);
    xhr.send();
});
