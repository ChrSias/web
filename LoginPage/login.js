function openpopup() {
    document.getElementById("loginpopup").style.display = "block";
}

function closepopup() {
    document.getElementById("loginpopup").style.display = "none";
    // Σβήνει τα data από το popup όταν πατάς exit
    const form = document.querySelector('#loginpopup form');
    if (form) form.reset();
}

document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('login.php', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            document.getElementById('errorMsg').innerText = data.message;
        }
    })
    .catch(() => {
        document.getElementById('errorMsg').innerText = 'Κάτι πήγε στραβά. Προσπαθήστε ξανά.';
    });
});

// Κλείνει το popup όταν κάνω click εκτός
window.onclick = function(event) {
    const popup = document.getElementById("loginpopup");
    if (event.target == popup) {
        popup.style.display = "none";
    }
};
