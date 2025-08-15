document.addEventListener('DOMContentLoaded', () => {
  loadTheses();

  document.getElementById('thesisForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let formData = new FormData(this);

    fetch('themata.php?action=add', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      alert(data.message);
      if (data.success) {
        this.reset();
        loadTheses();
      }
    })
    .catch(err => console.error(err));
  });
});

function loadTheses() {
  fetch('themata.php?action=list')
    .then(res => res.json())
    .then(data => {
      let container = document.getElementById('thesesList');
      if (data.length === 0) {
        container.innerHTML = "<p>Δεν υπάρχουν θέματα.</p>";
        return;
      }

      let table = `<table>
        <tr>
          <th>ID</th>
          <th>Topic ID</th>
          <th>Student ID</th>
          <th>Supervisor ID</th>
          <th>Committee ID</th>
          <th>Status</th>
          <th>Grade</th>
          <th>Start Date</th>
          <th>Completion Date</th>
        </tr>`;

      data.forEach(row => {
        table += `<tr>
          <td>${row.id}</td>
          <td>${row.topic_id}</td>
          <td>${row.student_id}</td>
          <td>${row.supervisor_id}</td>
          <td>${row.committee_id}</td>
          <td>${row.status}</td>
          <td>${row.grade ?? ''}</td>
          <td>${row.start_date}</td>
          <td>${row.completion_date ?? ''}</td>
        </tr>`;
      });

      table += `</table>`;
      container.innerHTML = table;
    })
    .catch(err => console.error(err));
}
