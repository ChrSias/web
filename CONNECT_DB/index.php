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
 
<label for="role">Επιλογή ρόλου:</label>
<select name="role" id="role" required>
  <option value="φοιτητής">Φοιτητής</option>
  <option value="διδάσκων">Διδάσκων</option>
  <option value="γραμματεία">Γραμματεία</option>
</select>


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
        fetchUsers();
      } else {
        alert('Error: ' + (data.error || 'Unknown error'));
      }
    })
    .catch(err => alert('Request failed: ' + err));
  });

  function fetchUsers() {
  fetch('fetch_users.php')
    .then(res => res.text())   
    .then(html => {
      document.getElementById('usersList').innerHTML = html; 
    })
    .catch(() => {
      document.getElementById('usersList').innerHTML = '<p>Failed to load users.</p>';
    });
}


  fetchUsers(); 
</script>

