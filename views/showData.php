<?php
include_once __DIR__ . '/../models/User.php';

$user = new User();

// Navbar
include "./navbar.php";

// Fetch the user ID from the query parameter
$userId = $_GET['user_id'] ?? null;

if ($userId) {
    // show user using controller
    $userData = $user->showUser($userId);

    if ($userData) {
        // Remove leading and trailing quotes, if present
        $mfilesString = trim($userData['mfile'], '"');

        // Split the string into an array of file names
        $mfiles = explode(',', $mfilesString);
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <title>User Data</title>
        </head>

        <body style="font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4;">
            <h1 style="text-align: center; color: #333;">User Details</h1>
            <div style="font-size: large; color: #333; padding: 20px; background-color: #fff; border-radius: 5px;">
                <p style="font-weight: bold;">Name: <span style="font-weight: normal;"><?= htmlspecialchars($userData['name']) ?></span></p>
                <p style="font-weight: bold;">Email: <span style="font-weight: normal;"><?= htmlspecialchars($userData['email']) ?></span></p>
                <p style="font-weight: bold;">Gender: <span style="font-weight: normal;"><?= htmlspecialchars($userData['gender']) ?></span></p>
                <p style="font-weight: bold;">Hobbies: <span style="font-weight: normal;"><?= htmlspecialchars($userData['hobbies']) ?></span></p>
                <p style="font-weight: bold;">Favorite Number: <span style="font-weight: normal;"><?= htmlspecialchars($userData['fav_number']) ?></span></p>
                <p style="font-weight: bold;">Favorite Color: <span style="font-weight: normal; background-color: <?= htmlspecialchars($userData['fav_color']) ?>; padding: 0 10px;">&nbsp;</span></p>
                <p style="font-weight: bold;">Date of Birth: <span style="font-weight: normal;"><?= htmlspecialchars($userData['dob']) ?></span></p>
                <p style="font-weight: bold;">Profile Picture:</p>
                <?php if ($userData['profile_pic']) { ?>
                    <img src="../uploads/<?= htmlspecialchars($userData['profile_pic']) ?>" alt="Profile Picture" style="max-width: 200px; display: block; margin-bottom: 20px;">
                <?php } else { ?>
                    <p>No profile picture uploaded.</p>
                <?php } ?>

                <!-- New fields -->
                <p style="font-weight: bold;">Date time-local: <span style="font-weight: normal;"><?= htmlspecialchars($userData['dtl']) ?></span></p>
                <p style="font-weight: bold;">Multiple Files:</p>
                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                    <?php foreach ($mfiles as $file): ?>
                        <?php if (file_exists("../uploads/" . htmlspecialchars($file))): ?>
                            <div style="text-align: center;">
                                <img src="../uploads/<?= htmlspecialchars($file) ?>" alt="<?= htmlspecialchars($file) ?>" style="max-width: 150px; max-height: 150px; object-fit: cover; border: 1px solid #ccc; margin-bottom: 10px;">
                            </div>
                        <?php else: ?>
                            <p>File not found: <?= htmlspecialchars($file) ?></p>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <p style="font-weight: bold;">Month: <span style="font-weight: normal;"><?= htmlspecialchars($userData['month']) ?></span></p>
                <p style="font-weight: bold;">Volume (Range 0 to 100): <span style="font-weight: normal;"><?= $userData['range'] ?></span></p>
                <p style="font-weight: bold;">Search Query: <span style="font-weight: normal;"><?= htmlspecialchars($userData['search']) ?></span></p>
                <p style="font-weight: bold;">Phone Number: <span style="font-weight: normal;"><?= htmlspecialchars($userData['pno']) ?></span></p>
                <p style="font-weight: bold;">Time: <span style="font-weight: normal;"><?= htmlspecialchars($userData['time']) ?></span></p>
                <p style="font-weight: bold;">Website: <a href="<?= $userData['website'] ?>" target="_blank" style="color: #007bff; text-decoration: none;"><?= $userData['website'] ?></a></p>
                <p style="font-weight: bold;">Week: <span style="font-weight: normal;"><?= htmlspecialchars($userData['week']) ?></span></p>
                <p style="font-weight: bold;">Country: <span style="font-weight: normal;"><?= htmlspecialchars($userData['country']) ?></span></p>
                <p style="font-weight: bold;">Content: <span style="font-weight: normal;"><?= htmlspecialchars_decode($userData['editorcontent']) ?></span></p>
                <p style="font-weight: bold;">Account status: <span style="font-weight: normal;"><?= $userData['isactive'] == 1 ? 'Active' : 'Inactive' ?></span></p>
                <p style="font-weight: bold;">Created at : <span style="font-weight: normal;"><?= htmlspecialchars($userData['createdat']) ?></span></p>
                <p style="font-weight: bold;">Updated at : <span style="font-weight: normal;"><?= htmlspecialchars($userData['updatedat']) ?></span></p>
                <p style="font-weight: bold;">Deleted at : <span style="font-weight: normal;"><?= ($userData['deletedat'] == null ? "null" : $userData['deletedat']) ?></span></p>
                <p style="font-weight: bold;">isDelete: <span style="font-weight: normal;"><?= $userData['isdelete'] == 1 ? 'true' : 'false' ?></span></p>
            </div>
        </body>

        </html>
<?php
    } else {
        echo "<h1>User not found.</h1>";
    }
} else {
    echo "<h1>Invalid user ID.</h1>";
}
