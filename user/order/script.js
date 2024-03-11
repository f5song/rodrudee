
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
