function openpopup() {
    const popup = document.getElementById("loginpopup");
    popup.style.display = "block";
    popup.style.opacity = "0";
    setTimeout(() => { popup.style.opacity = "1"; }, 10);
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

        console.log("Sending:", Object.fromEntries(formData));

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

function loadAnnouncements() {
    const startDate = document.getElementById('startDate').value;
    const endDate   = document.getElementById('endDate').value;
    const format    = document.getElementById('formatSelect').value;

    // Δημιουργούμε query string για GET request
    let url = `announcements.php?format=${encodeURIComponent(format)}`;
    if (startDate) url += `&start_date=${encodeURIComponent(startDate)}`;
    if (endDate)   url += `&end_date=${encodeURIComponent(endDate)}`;

    fetch(url)
        .then(res => {
            if (format === 'json') return res.json();
            else return res.text(); // XML το χειριζόμαστε ως text
        })
        .then(data => {
            const container = document.getElementById('announcements-list');
            container.innerHTML = ''; // καθαρίζουμε πριν βάλουμε νέα

            if (format === 'json') {
                // Εμφάνιση JSON σε λίστα
                if (data.length === 0) {
                    container.innerHTML = '<p>Δεν βρέθηκαν ανακοινώσεις.</p>';
                    return;
                }
                const ul = document.createElement('ul');
                data.forEach(item => {
                    const li = document.createElement('li');
                    li.textContent = `${item.title} - ${item.date}`;
                    ul.appendChild(li);
                });
                container.appendChild(ul);
            } else {
                // Αν XML, απλά το εμφανίζουμε ως raw text
                container.textContent = data;
            }
        })
        .catch(err => {
            document.getElementById('announcements-list').innerHTML =
                '<p style="color:red">Σφάλμα φόρτωσης ανακοινώσεων.</p>';
            console.error(err);
        });
}

// Κλείνει το popup όταν κάνω click εκτός
window.onclick = function(event) {
    const popup = document.getElementById("loginpopup");
    if (event.target == popup) {
        popup.style.display = "none";
    }
};
