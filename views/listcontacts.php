<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "contacts_db";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve and display contacts using ListAllContacts stored procedure
$result = $conn->query("CALL ListAllContacts()");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Contact List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
            text-align: center;
        }
        h2 {
            color: #333;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
           
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            white-space: nowrap;
        }
        th {
            background-color: #007BFF;
            color: white;
        }

        .action-buttons {
            white-space: nowrap;
            text-align: left;
            border-right: 20px;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .popup, .edit-modal {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 500px;
        }
        .popup input, .edit-modal input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .popup button, .edit-modal button {
            width: 100%;
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 4px;
            margin-top: 10px;
        }
        .popup button:hover, .edit-modal button:hover {
            background-color: #0056b3;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }
        .add-contact {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF; 
            color: white;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }

        .add-contact:hover {
            background-color: #0056b3; 
        }

        .action-buttons button {
            margin-right: 5px;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            border: none;
            color: white;
        }
        .edit-btn {
            background-color: #28a745;
        }
        .delete-btn {
            background-color: #dc3545;
        }
    </style>
    <script>
        function openPopup() {
            document.getElementById("popup").style.display = "block";
            document.getElementById("overlay").style.display = "block";
        }
        function openEditPopup(id, firstname, lastname, birthdate, workphone, homephone, email) {
            document.getElementById("edit_id").value = id;
            document.getElementById("edit_firstname").value = firstname;
            document.getElementById("edit_lastname").value = lastname;
            document.getElementById("edit_birthdate").value = birthdate;
            document.getElementById("edit_workphone").value = workphone;
            document.getElementById("edit_homephone").value = homephone;
            document.getElementById("edit_email").value = email;
            document.getElementById("edit-modal").style.display = "block";
            document.getElementById("overlay").style.display = "block";
        }
        function closePopup() {
            document.getElementById("edit-modal").style.display = "none";
            document.getElementById("overlay").style.display = "none";
        }

        document.addEventListener("DOMContentLoaded", function () {
         document.getElementById("editForm").addEventListener("submit", function (event) { 
          event.preventDefault();

          let formData = new FormData(this);

          fetch("editcontact.php", {
              method: "POST",
              body: formData
          })
          .then(response => response.json()) 
          .then(data => {
              if (data.status === "success") {
                  alert("Contact updated successfully.");
                  closePopup(); 
                  location.reload(); 
              } else {
                  alert("Failed to update contact: " + data.message);
              }
          });
      });
  });

  function updateTableRow(formData) {
      let id = formData.get("id");
      let row = document.getElementById("row-" + id);

      if (row) {
          row.cells[1].textContent = formData.get("firstname");
          row.cells[2].textContent = formData.get("lastname");
          row.cells[3].textContent = formData.get("birthdate");
          row.cells[4].textContent = formData.get("workphone");
          row.cells[5].textContent = formData.get("homephone");
          row.cells[6].textContent = formData.get("email");

          let editButton = row.querySelector(".edit-btn");
        editButton.setAttribute("onclick", `openEditPopup(
            '${data.id}', '${data.firstname}', '${data.lastname}', 
            '${data.birthdate}', '${data.workphone}', '${data.homephone}', 
            '${data.email}'
        )`);
      }
  }
  

    function deleteContact(id, rowId) {
        if (confirm("Are you sure you want to delete this contact?")) {
            fetch("deletecontact.php?id=" + id, { method: "GET" })
            .then(response => response.text())
            .then(result => {
                if (result.trim() === "success") {
                    document.getElementById("row-" + rowId).remove();
                    alert("Contact deleted successfully.");
                } else {
                    alert("Failed to delete contact.");
                }
            });
        }
    }

    </script>
</head>
<body>
  
    <h1>Contact List</h1>
    <button class="add-contact" onclick="openPopup()">Add New Contact</button>
    <table>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Birthdate</th>
            <th>Work Phone</th>
            <th>Home Phone</th>
            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Email</th>
            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr id="row-<?php echo $row['id']; ?>">
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['firstname']); ?></td>
                <td><?php echo htmlspecialchars($row['lastname']); ?></td>
                <td><?php echo htmlspecialchars($row['birthdate']); ?></td>
                <td><?php echo htmlspecialchars($row['workphone']); ?></td>
                <td><?php echo htmlspecialchars($row['homephone']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td class="action-buttons">
                    <button class="edit-btn" onclick="openEditPopup('<?php echo $row['id']; ?>', '<?php echo $row['firstname']; ?>', '<?php echo $row['lastname']; ?>', '<?php echo $row['birthdate']; ?>', '<?php echo $row['workphone']; ?>', '<?php echo $row['homephone']; ?>', '<?php echo $row['email']; ?>')">Edit</button>
                    <button class="delete-btn" onclick="deleteContact('<?php echo $row['id']; ?>', '<?php echo $row['id']; ?>')">Delete</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <div id="overlay" class="overlay" onclick="closePopup()"></div>

    <div id="popup" class="popup">
        <h2>Add New Contact</h2>
        <form action="process_contact.php" method="POST">
            <input type="text" name="firstname" placeholder="First Name" required>
            <input type="text" name="lastname" placeholder="Last Name" required>
            <input type="date" name="birthdate" required>
            <input type="text" name="workphone" placeholder="Work Phone">
            <input type="text" name="homephone" placeholder="Home Phone">
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit">Add Contact</button>
            <button type="button" onclick="closePopup()">Cancel</button>
        </form>
    </div>

    <div id="edit-modal" class="edit-modal">
    <h2>Edit Contact</h2>
    <form id="editForm" action="editcontact.php" method="POST">
        <input type="hidden" id="edit_id" name="id">
        <input type="text" id="edit_firstname" name="firstname" required>
        <input type="text" id="edit_lastname" name="lastname" required>
        <input type="date" id="edit_birthdate" name="birthdate" required>
        <input type="text" id="edit_workphone" name="workphone">
        <input type="text" id="edit_homephone" name="homephone">
        <input type="email" id="edit_email" name="email" required>
        <button type="submit">Save Changes</button>
        <button type="button" onclick="closePopup()">Cancel</button>
    </form>
</div>
    </div>
</body>
</html>
<?php
$conn->close();
?>
