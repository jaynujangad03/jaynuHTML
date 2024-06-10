<?php
include_once("connect.php");

// echo '<pre>';  
// var_dump($_FILES);
// echo '</pre>';  

$errors = [];
$result = [];
$message = 'Added Successfully';
$sname = '';
$slname = '';
$sgender = '';
$scontact = '';
$scourse = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $sname = $_POST['sname'];
    $slname = $_POST['slname'];
    $sgender = $_POST['sgender'];
    $scontact = $_POST['scontact'];
    $scourse = $_POST['scourse'];

    if(!$sname){
        $errors[] = 'Student Name Required';
    }

    if(!is_dir('images')){
        mkdir('images');
    }

    if(empty($errors)){
        $image = $_FILES['image'] ?? null;
        $imagePath = '';
        if($image && $image['tmp_name']){
            $imagePath = 'images/' . randomString(8) . '/' . $image['name'];
            mkdir(dirname($imagePath));
            move_uploaded_file($image['tmp_name'], $imagePath);
        }
       
        $statement = $conn->prepare("INSERT INTO studentinfo (s_firstname, s_image_dir, s_lastname, s_gender, s_contact, s_course) VALUES (:sname, :simage, :slname, :sgender, :scontact, :scourse)");

        $statement->bindValue(':sname', $sname);
        $statement->bindValue(':simage', $imagePath);
        $statement->bindValue(':slname', $slname);
        $statement->bindValue(':sgender', $sgender);
        $statement->bindValue(':scontact', $scontact);
        $statement->bindValue(':scourse', $scourse);

        $result = $statement->execute();
        if ($result) {
            $sname = '';
            $slname = '';
            $sgender = '';
            $scontact = '';
            $scourse = '';
        }
    }
}   

function randomString($n){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    for($i = 0; $i < $n; $i++){
        $index = rand(0, strlen($characters) - 1);
        $str .= $characters[$index];
    }
    return $str;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="app.css">
    <title>Library System!</title>
</head>
<body>
    <h1>Library, CRUD!</h1>

    <?php if(!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach($errors as $error): ?>
                <div><?php echo $error ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if($result): ?>
        <div class="alert alert-success">
            <?php echo 'Added Successfully' ?>
        </div>
    <?php endif; ?>
    
    <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Student Image</label>
            <input type="file" name="image" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Student Name</label>
            <input type="text" name="sname" value="<?php echo $sname ?>" class="form-control">
        </div>
        
        <div class="mb-3">
            <label class="form-label">Student Lastname</label>
            <input type="text" name="slname" value="<?php echo $slname ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Student Gender</label>
            <input type="text" name="sgender" value="<?php echo $sgender ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Student Contact</label>
            <input type="text" name="scontact" value="<?php echo $scontact ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Student Course</label>
            <input type="text" name="scourse" value="<?php echo $scourse ?>" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="index.php" class="btn btn-primary">Go Back</a>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
