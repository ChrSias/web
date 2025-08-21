function confirmLogout() {
  if (confirm("Είσαι σίγουρος/η ότι θέλεις να αποσυνδεθείς;")) {
    window.location.href = '../Login/logout.php';
  }
}

document.getElementById('logoutBtn').addEventListener('click', confirmLogout);

//Otan kleinei to tab, aftomata kanei logout
window.addEventListener("beforeunload", function () {
  navigator.sendBeacon("../Login/logout.php");
});
