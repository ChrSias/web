document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("profileForm");

  // Φόρτώνει τα στοιχείων με AJAX
  fetch('editprofile.php', { credentials: 'same-origin' })  // <<< προστέθηκε
    .then(res => res.json())
    .then(data => {
      if (!data.error) {
        document.getElementById("address").value = data.address || "";
        document.getElementById("email").value = data.email || "";
        document.getElementById("mobile").value = data.mobile || "";
        document.getElementById("phone").value = data.phone || "";
      } else {
        console.error("Σφάλμα:", data.error);
      }
    })
    .catch(err => console.error("Σφάλμα φόρτωσης προφίλ:", err));

  //  Υποβλαλήει νέα στοιχείων με AJAX
  form.addEventListener("submit", e => {
    e.preventDefault();

    const formData = new FormData(form);

    fetch('editprofile.php', {
      method: 'POST',
      credentials: 'same-origin',  // ***
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert("Τα στοιχεία σας ενημερώθηκαν!");
      } else {
        alert("Σφάλμα: " + (data.error || "Δεν ήταν δυνατή η ενημέρωση."));
      }
    })
    .catch(err => console.error("Σφάλμα αποθήκευσης:", err));
  });
});
