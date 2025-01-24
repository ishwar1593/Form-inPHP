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
    </head>

    <body style="font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4;">
        <h1 style="text-align: center; color: #333;">All Users</h1>
        
        <table border="1" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 15px; font-size: 18px; text-align: left; background-color: #007bff; color: white;">ID</th>
                    <th style="padding: 15px; font-size: 18px; text-align: left; background-color: #007bff; color: white;">Name</th>
                    <th style="padding: 15px; font-size: 18px; text-align: left; background-color: #007bff; color: white;">Email</th>
                    <th style="padding: 15px; font-size: 18px; text-align: center; background-color: #007bff; color: white;" colspan="3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allUsers as $user) : ?>
                    <tr style="background-color: #fff; text-align: left;">
                        <td style="padding: 15px; font-size: 16px;"><?php echo htmlspecialchars($user['id']); ?></td>
                        <td style="padding: 15px; font-size: 16px;"><?php echo htmlspecialchars($user['name']); ?></td>
                        <td style="padding: 15px; font-size: 16px;"><?php echo htmlspecialchars($user['email']); ?></td>
                        <td style="padding: 15px; font-size: 16px; text-align: center;">
                            <a href="/task1/views/showData.php?user_id=<?php echo htmlspecialchars($user['id']); ?>" style="color: #007bff; text-decoration: none; padding: 5px; border: 1px solid #007bff; border-radius: 4px; background-color: #f0f8ff;">View</a>
                        </td>
                        <td style="padding: 15px; font-size: 16px; text-align: center;">
                            <a href="/task1/views/editData.php?user_id=<?php echo htmlspecialchars($user['id']); ?>" style="color: #ffc107; text-decoration: none; padding: 5px; border: 1px solid #ffc107; border-radius: 4px; background-color: #fff8e1;">Edit</a>
                        </td>
                        <td style="padding: 15px; font-size: 16px; text-align: center;">
                            <a href="/task1/views/allUsers.php?user_id=<?php echo htmlspecialchars($user['id']); ?>" style="color: #dc3545; text-decoration: none; padding: 5px; border: 1px solid #dc3545; border-radius: 4px; background-color: #f8d7da;">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </body>

    </html>
<?php } ?>
