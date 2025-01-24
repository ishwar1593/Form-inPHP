<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <link rel="stylesheet" href="/task1/assets/style.css">
</head>

<body>
    <h1>User Registration</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="name">Name :</label>
        <input type="text" name="name" id="name">
        <span class="error">* <?php echo $errors["nameErr"] ?? ''; ?></span>
        <br><br>

        <label for="email">Email :</label>
        <input type="email" name="email" id="email">
        <span class="error">* <?php echo $errors["emailErr"] ?? ''; ?></span>
        <br><br>

        <label>Gender :</label>
        <input type="radio" name="gender" id="male" value="male"><label for="male">Male</label>
        <input type="radio" name="gender" id="female" value="female"><label for="female">Female</label>
        <input type="radio" name="gender" id="other" value="other"><label for="other">Other</label>
        <span class="error">* <?php echo $errors["genderErr"] ?? ''; ?></span>
        <br><br>

        <label for="hobbies">Hobbies :</label>
        <input type="checkbox" name="hobbies[]" id="Reading" value="Reading"><label for="Reading">Reading</label>
        <input type="checkbox" name="hobbies[]" id="Traveling" value="Traveling"><label for="Traveling">Traveling</label>
        <input type="checkbox" name="hobbies[]" id="Cooking" value="Cooking"><label for="Cooking">Cooking</label>
        <span class="error">* <?php echo $errors["hobbiesErr"] ?? ''; ?></span>
        <br><br>


        <label for="password">Password :</label>
        <input type="password" name="password" id="password">
        <span class="error">* <?php echo $errors["passwordErr"] ?? '' ?></span>
        <br><br>

        <label for="fav_number">Favorite Number :</label>
        <input type="number" name="fav_number" id="fav_number">
        <span class="error">* <?php echo $errors["fav_numberErr"] ?? ''; ?></span>
        <br><br>

        <label for="fav_color">Favorite Color :</label>
        <input type="color" name="fav_color" id="fav_color">
        <span class="error">* <?php echo $errors["fav_colorErr"] ?? ''; ?></span>
        <br><br>

        <label for="profile_pic">Profile Picture :</label>
        <input type="file" name="profile_pic" id="profile_pic">
        <span class="error">* <?php echo $errors["nameErr"] ?? ''; ?></span>
        <br><br>

        <label for="dob">Date of Birth :</label>
        <input type="date" name="dob" id="dob">
        <span class="error">* <?php echo $errors["dobErr"] ?? ''; ?></span>
        <br><br>

        <!-- extra fields -->
        <label for="dtl">Date time-local :</label>
        <input type="datetime-local" name="dtl" id="dtl">
        <span class="error">* <?php echo $errors["dtlErr"] ?? ''; ?></span>
        <br><br>

        <label for="mfile">Multiple Files :</label>
        <input type="file" multiple name="mfile[]" id="mfile">
        <span class="error">* <?php echo $errors["mfileErr"] ?? ''; ?></span>
        <br><br>

        <label for="month">Month :</label>
        <input type="month" name="month" id="month">
        <span class="error">* <?php echo $errors["monthErr"] ?? ''; ?></span>
        <br><br>

        <label for="vol">Volume (0 to 100):</label>
        <input type="range" name="range" id="vol" min="0" max="100" value="10">
        <br><br>

        <label for="search">Search :</label>
        <input type="search" name="search" id="search" placeholder="Search something">
        <span class="error">* <?php echo $errors["searchErr"] ?? ''; ?></span>
        <br><br>

        <label for="pno">Phone Number :</label>
        <input type="tel" name="pno" id="pno" value="">
        <span class="error">* <?php echo $errors["pnoErr"] ?? ''; ?></span>
        <br><br>

        <label for="time">Time :</label>
        <input type="time" name="time" id="time">
        <span class="error">* <?php echo $errors["timeErr"] ?? ''; ?></span>
        <br><br>

        <label for="website">Your website url :</label>
        <input type="url" name="website" id="website">
        <span class="error">* <?php echo $errors["websiteErr"] ?? ''; ?></span>
        <br><br>

        <label for="">Week :</label>
        <input type="week" name="week" id="week">
        <span class="error">* <?php echo $errors["weekErr"] ?? ''; ?></span>
        <br><br>

        <label for="country">Country :</label>
        <select name="country" id="country">
            <option value="India" default>India</option>
            <option value="USA">USA</option>
            <option value="Dubai">Dubai</option>
        </select>
        <br><br>

        <input type="submit" value="Submit">
    </form>
</body>

</html>