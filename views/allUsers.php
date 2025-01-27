<?php
include __DIR__ . '/../models/User.php';

$user = new User();

// Navbar
include "./navbar.php";

$allUsers = $user->showAllUsers();

if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    $user->deleteUser($userId);

    header("Location: /task1/views/allUsers.php");
    exit;
}

if ($allUsers) {
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>All Users</title>
        <style>
            /* Switch style */
            .switch {
                position: relative;
                display: inline-block;
                width: 34px;
                height: 20px;
            }

            .switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }

            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                transition: 0.4s;
                border-radius: 50px;
            }

            .slider:before {
                position: absolute;
                content: "";
                height: 12px;
                width: 12px;
                border-radius: 50px;
                left: 4px;
                bottom: 4px;
                background-color: white;
                transition: 0.4s;
            }

            input:checked+.slider {
                background-color: #2196F3;
            }

            input:checked+.slider:before {
                transform: translateX(14px);
            }
        </style>
    </head>

    <body style="font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4;">
        <h1 style="text-align: center; color: #333;">All Users</h1>

        <table border="1" style="width: 75%; border-collapse: collapse; margin-top: 20px; margin:auto">
            <thead>
                <tr>
                    <th style="padding: 15px; font-size: 18px; text-align: left; background-color: #007bff; color: white;">ID</th>
                    <th style="padding: 15px; font-size: 18px; text-align: left; background-color: #007bff; color: white;">Name</th>
                    <th style="padding: 15px; font-size: 18px; text-align: left; background-color: #007bff; color: white;">Email</th>
                    <th style="padding: 15px; font-size: 18px; text-align: center; background-color: #007bff; color: white;">Status</th>
                    <th style="padding: 15px; font-size: 18px; text-align: center; background-color: #007bff; color: white;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allUsers as $user) : ?>
                    <tr style="background-color: #fff; text-align: left;">
                        <td style="padding: 15px; font-size: 16px;"><?php echo htmlspecialchars($user['id']); ?></td>
                        <td style="padding: 15px; font-size: 16px;"><?php echo htmlspecialchars($user['name']); ?></td>
                        <td style="padding: 15px; font-size: 16px;"><?php echo htmlspecialchars($user['email']); ?></td>
                        <td style="padding-left: 3px; font-size: 16px; display:flex; align-items:center; justify-content:center; gap: 15px; height: 60px;">
                            <span style="padding-left: 3px; font-size: 16px;">Inactive</span>
                            <label class="switch">
                                <input type="checkbox" class="toggle-status" data-id="<?php echo $user['id']; ?>" <?php echo $user['isactive'] ? 'checked' : ''; ?>>
                                <span class="slider"></span>
                            </label>
                            <span style="padding-left: 3px; font-size: 16px;">Active</span>
                        </td>

                        <td style="padding: 15px; font-size: 16px; text-align: center;">
                            <a href="/task1/views/showData.php?user_id=<?php echo htmlspecialchars($user['id']); ?>" style="color: #007bff; text-decoration: none; padding: 5px; border: 1px solid #007bff; margin-right:10px; border-radius: 4px; background-color: #f0f8ff;">View</a>
                            <a href="/task1/views/editData.php?user_id=<?php echo htmlspecialchars($user['id']); ?>" style="color: #ffc107; text-decoration: none; padding: 5px; border: 1px solid #ffc107; margin-right:10px; border-radius: 4px; background-color: #fff8e1;">Edit</a>
                            <a href="/task1/views/allUsers.php?user_id=<?php echo htmlspecialchars($user['id']); ?>" style="color: #dc3545; text-decoration: none; padding: 5px; border: 1px solid #dc3545; margin-right:10px; border-radius: 4px; background-color: #f8d7da;">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Event listener for the toggle switch
                const toggleSwitches = document.querySelectorAll('.toggle-status');

                toggleSwitches.forEach(function(toggle) {
                    toggle.addEventListener('click', function(e) {
                        e.preventDefault(); // Prevent immediate toggle behavior

                        const userId = this.getAttribute('data-id'); // Get the user ID from data-id
                        const isActive = this.checked ? 1 : 0; // Desired new state of the toggle

                        // Create a FormData object to send data
                        const formData = new FormData();
                        formData.append('id', userId);
                        formData.append('isactive', isActive);

                        // Create a new XMLHttpRequest (AJAX request)
                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', '/task1/controllers/update_status.php', true);

                        const toggleElement = this; // Save the current toggle element reference

                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                // On success, allow the toggle to reflect the new state
                                toggleElement.checked = isActive === 1;
                                console.log('Status updated successfully');
                            } else {
                                // On failure, show an error message
                                console.error('Error updating status:', xhr.statusText);
                                alert('Failed to update status. Please try again.');
                            }
                        };

                        xhr.onerror = function() {
                            console.error('Error updating status:', xhr.statusText);
                            alert('Failed to update status. Please check your connection.');
                        };

                        // Send the request with the form data
                        xhr.send(formData);
                    });
                });
            });
        </script>
    </body>

    </html>
<?php } ?>