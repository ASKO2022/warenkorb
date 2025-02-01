function openEditModal(id, name, description, price) {
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-description').value = description;
    document.getElementById('edit-price').value = price;
    document.getElementById('edit-modal').style.display = "flex";
}

function closeEditModal() {
    document.getElementById('edit-modal').style.display = "none";
}

window.onclick = function(event) {
    if (event.target === document.getElementById('edit-modal')) {
        closeEditModal();
    }
}

window.addEventListener("keydown", function(event) {
    if (event.key === "Escape") {
        closeEditModal();
    }
});

document.getElementById('edit-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('/products/update', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            closeEditModal();
            window.location.href = '/products';
        } else {
            alert('Fehler beim Aktualisieren des Produkts.');
        }
    })
    .catch(error => {
        console.error('Fehler:', error);
    });
});
