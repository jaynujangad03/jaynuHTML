<?php
include_once("connect.php");

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

$statement = $conn->prepare('SELECT * FROM studentinfo WHERE s_id = :id');
$statement->bindValue(':id', $id);
$statement->execute();
$student = $statement->fetch(PDO::FETCH_ASSOC); // Fetch a single record

if (!$student) {
    header('Location: index.php');
    exit;
}

$errors = [];
$result = false; // Initialize result as false
$message = 'Added Successfully';
$sname = $student['s_firstname'];
$slname = $student['s_lastname'];
$sgender = $student['s_gender'];
$scontact = $student['s_contact'];
$scourse = $student['s_course'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sname = $_POST['sname'];
    $slname = $_POST['slname'];
    $sgender = $_POST['sgender'];
    $scontact = $_POST['scontact'];
    $scourse = $_POST['scourse'];

    if (!$sname) {
        $errors[] = 'Student Name Required';
    }

    if (!is_dir('images')) {
        mkdir('images');
    }

    if (empty($errors)) {
        $image = $_FILES['image'] ?? null;
        $imagePath = $student['s_image_dir'];

        if ($student['s_image_dir']) {
            unlink($student['s_image_dir']);
        }

        if ($image && $image['tmp_name']) {
            $imagePath = 'images/' . randomString(8) . '/' . $image['name'];
            mkdir(dirname($imagePath));
            move_uploaded_file($image['tmp_name'], $imagePath);
        }

        $statement = $conn->prepare("UPDATE studentinfo SET s_firstname = :sname, s_image_dir = :simage, s_lastname = :slname, s_gender = :sgender, s_contact = :scontact, s_course = :scourse WHERE s_id = :id");

        $statement->bindValue(':sname', $sname);
        $statement->bindValue(':simage', $imagePath);
        $statement->bindValue(':slname', $slname);
        $statement->bindValue(':sgender', $sgender);
        $statement->bindValue(':scontact', $scontact);
        $statement->bindValue(':scourse', $scourse);
        $statement->bindValue(':id', $id);

        $result = $statement->execute();
    }
}

function randomString($n) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    for ($i = 0; $i < $n; $i++) {
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
    <h1>Update Student!</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <div><?php echo $error ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($result): ?>
        <div class="alert alert-success"><?php echo 'Added Successfully' ?></div>
    <?php endif; ?>

    <form action="update.php?id=<?php echo $id ?>" method="post" enctype="multipart/form-data">
        <?php if ($student['s_image_dir']): ?>
            <img src="<?php echo $student['s_image_dir'] ?>" class="image-update">
        <?php endif; ?>

        <div class="mb-3">
            <label class="form-label">Student Image</label>
            <input type="file" name="image" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Student Name</label>
            <input type="text" name="sname" value="<?php echo $student['s_firstname'] ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Student Lastname</label>
            <input type="text" name="slname" value="<?php echo $student['s_lastname'] ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Student Gender</label>
            <input type="text" name="sgender" value="<?php echo $student['s_gender'] ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Student Contact</label>
            <input type="text" name="scontact" value="<?php echo $student['s_contact'] ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Student Course</label>
            <input type="text" name="scourse" value="<?php echo $student['s_course'] ?>" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="index.php" class="btn btn-primary">Go Back</a>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
