<?php
    include_once("connect.php");
    $statement = $conn->prepare("SELECT * FROM studentinfo");
    $statement->execute();
    $student = $statement->fetchAll(PDO::FETCH_ASSOC);

?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel= "stylesheet" href="app.css">

    <title>Library System!</title>
  </head>
  <body>
    <h1>Library, CRUD!</h1>

    <a href ="create.php" class="btn btn-success">Add Student</a>

    <table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Student ID</th>
      <th scope="col">Image</th>
      <th scope="col">Student Firstname</th>
      <th scope="col">Student Lastname</th>
      <th scope="col">Student Gender</th>
      <th scope="col">Student Contact</th>
      <th scope="col">Student Course</th>
      <th scope="col">Actions</th>

    </tr>
  </thead>
  <tbody>
          <?php foreach ($student as $i => $student): ?>
            <tr>
            <th scope="row"><?php echo $i + 1 ?></th>
            <td><?php echo $student['s_id']?></td>
            <td><img src= <?php echo $student['s_image_dir'] ?> class= "thumbnail"></td>
            <td><?php echo $student['s_firstname']?></td>
            <td><?php echo $student['s_lastname']?></td>
            <td><?php echo $student['s_gender']?></td>
            <td><?php echo $student['s_contact']?></td>
            <td><?php echo $student['s_course']?></td>
            <td> 
            <a href="update.php?id=<?php echo $student['s_id']?>"type="button" class="btn btn-sm btn-primary">Edit</a>
            <!-- <a href="delete.php?id=<?php echo $student['s_id']?>"type="button" class="btn btn-sm btn-danger">Delete</a> -->
            <form style="display: inline-block" method= "POST" action= "delete.php">
              <input type = "hidden" name ="id" value= "<?php echo $student ['s_id']?>">

              <button type="submit" class="btn btn-sm btn-danger">Delete</button>
            </form>

            </td>
            
          </tr>

            <?php endforeach; ?>
    
  </tbody>
</table>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>