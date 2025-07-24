<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
</head>
<body>

<h2>Add User</h2>
<form id="userForm">
  <input type="text" name="first_name" placeholder="First Name" required>
  <input type="text" name="last_name" placeholder="Last Name" required>
  <input type="email" name="email" placeholder="Email" required>
  <input type="password" name="password" placeholder="Password" required>
  <button type="submit">Add User</button>
</form>

<h2>Users List</h2>
<div id="usersList"></div>

<script>
  document.getElementById('userForm').addEventListener('submit', function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    fetch('add_user.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert(data.success);
        this.reset();
        fetchUsers(); // ✅ correct case
      } else {
        alert('Error: ' + (data.error || 'Unknown error'));
      }
    })
    .catch(err => alert('Request failed: ' + err));
  });

  function fetchUsers() {
  fetch('fetch_users.php')
    .then(res => res.text())   // Use text() because PHP outputs HTML
    .then(html => {
      document.getElementById('usersList').innerHTML = html; // Inject raw HTML
    })
    .catch(() => {
      document.getElementById('usersList').innerHTML = '<p>Failed to load users.</p>';
    });
}


  fetchUsers(); // ✅ correct case
</script>

