<?php
session_start();
include("../config/connection.php");

$message = "";

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $price = mysqli_real_escape_string($con, $_POST['price']);
    $category = mysqli_real_escape_string($con, $_POST['category']);

    // Handle image upload
    $image_name = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = time() . "_" . basename($_FILES['image']['name']);
        $image_tmp = $_FILES['image']['tmp_name'];

        // Make sure the directory exists
        if (!is_dir("../assets/images")) {
            mkdir("../assets/images", 0777, true);
        }

        move_uploaded_file($image_tmp, "../assets/images/" . $image_name);
    }

    // ✅ USE $image_name, not $image
    $sql = "INSERT INTO products (name, price, category, image) VALUES ('$name', '$price', '$category', '$image_name')";
    
    if (mysqli_query($con, $sql)) {
        $message = "Product added successfully!";
        header("Location: product.php?msg=Product added successfully");
        exit();
    } else {
        $message = "Error: " . mysqli_error($con);
    }
}
?>


<html>
<head>
    <title>Add Books Name</title>
    <style>
        body { font-family: Arial; background:#f4f4f4; padding:20px; }
        form { background:#fff; padding:20px; border-radius:8px; width:400px; margin:auto; }
        input, select { width:100%; padding:8px; margin:10px 0; }
        input[type="submit"] { background:#28a745; color:#fff; border:none; cursor:pointer; }
        input[type="submit"]:hover { background:#218838; }
           .message { 
    color: green; 
    text-align: center; 
    font-weight: bold; 
    margin: 15px 0; 
}
    </style>
</head>
<body>
<h2 align="center">Add Product</h2>
<div style="text-align:left; margin: 10px;">
    <a href="product.php" style="text-decoration:none; padding:8px 12px; background:#6c757d; color:#fff; border-radius:5px;">← Back</a>
</div>
<?php if ($message != "") echo "<div class='message'>$message</div>"; ?>

<form method="post" enctype="multipart/form-data">
    <label>Name:</label>
    <input type="text" name="name" required>

    <label>Price:</label>
    <input type="number" name="price" step="0.01" required>

    <label>Category:</label>
    <select name="category" required>
    <option value="" disabled selected>-- Select Category --</option>
    <option value="Comics">Comics</option>
    <option value="History">History</option>
    <option value="GeneralKnowledge">GeneralKnowledge</option>
</select>


    <label>Image:</label>
    <input type="file" name="image" accept="image/*">

    <input type="submit" name="submit" value="Add Product">
</form>
</body>
</html>
