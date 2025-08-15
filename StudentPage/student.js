function confirmLogout() {
  return confirm("Είσαι σίγουρος/η ότι θέλεις να αποσυνδεθείς;");
}

// Όταν κλείνει το tab, αυτόματα κάνει logout τον χρήστη
window.addEventListener("beforeunload", function () {
  navigator.sendBeacon("../Login/logout.php");
});
