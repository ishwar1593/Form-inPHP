<?php
// Assuming you have a database connection file
include_once __DIR__ . '/../models/User.php';
include_once __DIR__ . '/../controllers/userControllers.php';
// Navbar
include "./navbar.php";

$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user_id'] ?? null;
    $userController->handleEditFormSubmit($userId, $_POST, $_FILES);
}
$errors = $userController->errors;
$formData = $userController->formData;

$user = new User();

// Fetch user data based on user ID
if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    $userData = $user->showUser($userId);

    if ($userData) {
        // set data in user field}
        $userData['hobbies'] = explode(',', $userData['hobbies']);

?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit User Data</title>
            <link rel="stylesheet" href="/task1/assets/style.css">
        </head>

        <body>
            <h2>Edit User Data</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($userId) ?>">
                <label for="name">Name :</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($userData['name'] ?? "") ?>">
                <span class="error">* <?php echo $errors["nameErr"] ?? ''; ?></span>
                <br><br>

                <label for="email">Email :</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($userData['email'] ?? "") ?>" required>
                <span class="error">* <?php echo $errors["nameErr"] ?? ''; ?></span>
                <br><br>


                <label>Gender :</label>
                <input type="radio" name="gender" id="male" value="male" <?= (isset($userData['gender']) && $userData['gender'] == "male") ? "checked" : "" ?>><label for="male">Male</label>
                <input type="radio" name="gender" id="female" value="female" <?= (isset($userData['gender']) && $userData['gender'] == "female") ? "checked" : "" ?>><label for="female">Female</label>
                <input type="radio" name="gender" id="other" value="other" <?= (isset($userData['gender']) && $userData['gender'] == "other") ? "checked" : "" ?>><label for="other">Other</label>
                <span class="error">* <?php echo $errors["nameErr"] ?? ''; ?></span>
                <br><br>

                <label for="hobbies">Hobbies :</label>
                <input type="checkbox" name="hobbies[]" id="Reading" value="Reading" <?= (isset($userData['hobbies']) && in_array('Reading', $userData['hobbies'])) ? "checked" : "" ?>><label for="Reading">Reading</label>
                <input type="checkbox" name="hobbies[]" id="Traveling" value="Traveling" <?= (isset($userData['hobbies']) && in_array('Traveling', $userData['hobbies'])) ? "checked" : "" ?>><label for="Traveling">Traveling</label>
                <input type="checkbox" name="hobbies[]" id="Cooking" value="Cooking" <?= (isset($userData['hobbies']) && in_array('Cooking', $userData['hobbies'])) ? "checked" : "" ?>><label for="Cooking">Cooking</label>
                <span class="error">* <?php echo $errors["nameErr"] ?? ''; ?></span>
                <br><br>

                <label for="password">Password :</label>
                <input type="password" name="password" id="password" value="<?= htmlspecialchars($userData['password'] ?? "") ?>" disabled>
                <br><br>

                <label for="fav_number">Favorite Number :</label>
                <input type="number" name="fav_number" id="fav_number" value="<?= htmlspecialchars($userData['fav_number'] ?? 0) ?>" required>
                <span class="error">* <?php echo $errors["nameErr"] ?? ''; ?></span>
                <br><br>

                <label for="fav_color">Favorite Color :</label>
                <input type="color" name="fav_color" id="fav_color" value="<?= htmlspecialchars($userData['fav_color'] ?? "#000000") ?>" required>
                <span class="error">* <?php echo $errors["nameErr"] ?? ''; ?></span>
                <br><br>

                <label for="profile_pic">Profile Picture :</label>
                <input type="file" name="profile_pic" id="profile_pic">
                <img src="../uploads/<?= htmlspecialchars($userData['profile_pic'] ?? "default.png") ?>" alt="profile pic">
                <span class="error">* <?php echo $errors["nameErr"] ?? ''; ?></span>
                <br><br>

                <label for="dob">Date of Birth :</label>
                <input type="date" name="dob" id="dob" value="<?= htmlspecialchars($userData['dob']) ?>" required>
                <span class="error">* <?php echo $errors["nameErr"] ?? ''; ?></span>
                <br><br>

                <!-- extra fields -->
                <label for="dtl">Date time-local :</label>
                <input type="datetime-local" name="dtl" id="dtl" value="<?= $userData['dtl'] ?>">
                <span class="error">* <?php echo $errors["dtlErr"] ?? ''; ?></span>
                <br><br>

                <label for="mfile">Multiple Files :</label>
                <input type="file" multiple name="mfile[]" id="mfile">
                <span class="error">* <?php echo $errors["mfileErr"] ?? ''; ?></span>
                <br><br>

                <label for="month">Month :</label>
                <input type="month" name="month" id="month" value="<?= $userData['month'] ?>">
                <span class=" error">* <?php echo $errors["monthErr"] ?? ''; ?></span>
                <br><br>

                <label for="vol">Volume (0 to 100):</label>
                <input type="range" name="range" id="vol" min="0" max="100" value="<?= $userData['range'] ?>">
                <br><br>

                <label for="search">Search :</label>
                <input type="search" name="search" id="search" placeholder="Search something" value="<?= htmlspecialchars($userData['search']) ?>">
                <span class="error">* <?php echo $errors["searchErr"] ?? ''; ?></span>
                <br><br>

                <label for="pno">Phone Number :</label>
                <input type="tel" name="pno" id="pno" value="<?= htmlspecialchars($userData['pno']) ?>">
                <span class="error">* <?php echo $errors["pnoErr"] ?? ''; ?></span>
                <br><br>

                <label for="time">Time :</label>
                <input type="time" name="time" id="time" value="<?= $userData['time'] ?>">
                <span class="error">* <?php echo $errors["timeErr"] ?? ''; ?></span>
                <br><br>

                <label for="website">Your website url :</label>
                <input type="url" name="website" id="website" value="<?= htmlspecialchars($userData['website']) ?>">
                <span class="error">* <?php echo $errors["websiteErr"] ?? ''; ?></span>
                <br><br>

                <label for="">Week :</label>
                <input type="week" name="week" id="week" value="<?= htmlspecialchars($userData['week']) ?>">
                <span class="error">* <?php echo $errors["weekErr"] ?? ''; ?></span>
                <br><br>

                <label for="country">Country :</label>
                <select name="country" id="country">
                    <option value="India" <?= isset($userData['country']) && $userData['country'] === 'India' ? 'selected' : ''; ?>>India</option>
                    <option value="USA" <?= isset($userData['country']) && $userData['country'] === 'USA' ? 'selected' : ''; ?>>USA</option>
                    <option value="Dubai" <?= isset($userData['country']) && $userData['country'] === 'Dubai' ? 'selected' : ''; ?>>Dubai</option>
                </select>
                <br><br>

                <input type="submit" value="Update">
            </form>
        </body>

        </html>
<?php }
} ?>