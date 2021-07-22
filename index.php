<?php include 'function.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <title>PDO</title>
        <meta name="Description" content=""/>
        <meta name="Keywords" content=""/>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="author" content="fadilxcoder" />
        <meta name="copyright" content="" />
        <meta name="robots" content="index,follow"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover,  shrink-to-fit=no">
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:700,300,600,400' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <style>
            body{
                font-family: Open sans !important
            }
            header{
                padding: 25px 0px;
            }
            /* Select a 'td' that contain a 'img' */
            td > img{
                width: 100px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <header>
                <h1 class="text-center">PDO BLOG <small class="text-muted">- <?= $status ?></small></h1>
            </header>
            <main>
                <?php if (isset($_GET['add']) && $_GET['add'] == true) : ?>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="text-left pb-3">
                            <a href="index.php" class="btn btn-lg btn-primary">BACK</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <form action="index.php" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="data">Texts:</label>
                                <input type="text" class="form-control" id="data" placeholder="Enter string ..." name="data" required>
                            </div>
                            <div class="form-group">
                                <label for="intergers">Integers:</label>
                                <input type="text" class="form-control" id="intergers" placeholder="Enter integers ..." name="intergers" value="<?php echo rand(0, 999999999) ?>" required readonly>
                            </div>
                            <div class="form-group">
                                <label for="img">Image:</label>
                                <input type="file" class="form-control-file" id="img" placeholder="Insert image" name="img" accept="image/*" required>
                            </div>
                            <button type="submit" name="btnInsert" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
                <?php elseif (isset($_GET['edit']) && $_GET['edit'] == true && isset($_GET['id']) ) : ?>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="text-left pb-3">
                            <a href="index.php" class="btn btn-lg btn-primary">BACK</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <form action="index.php?id=<?php echo $_GET['id']; ?>" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="data">Texts:</label>
                                <input type="text" class="form-control" id="data" placeholder="Enter string ..." name="data" value="<?php echo displayOne()->data ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="intergers">Integers:</label>
                                <input type="text" class="form-control" id="intergers" placeholder="Enter integers ..." name="intergers" value="<?php echo displayOne()->ref_number ?>" required readonly>
                            </div>
                            <div class="form-group">
                                <label for="img">Image:</label>
                                <input type="file" class="form-control-file" id="img" placeholder="Insert image" name="img" accept="image/*">
                                <br>
                                <img src="<?php echo 'data:image/jpeg;base64,'.base64_encode(displayOne()->image_blob)?>" class="img-fluid">
                            </div>
                            <button type="submit" name="btnUpdate" class="btn btn-primary mb-5">Submit</button>
                        </form>
                    </div>
                </div>
                <?php else : ?>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="text-right pb-3">
                            <a href="index.php?add=true" class="btn btn-lg btn-primary">Add new</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <table class="table">
                            <thead>
                                <th scope="col">#</th>
                                <th scope="col">Text</th>
                                <th scope="col">Integer</th>
                                <?php if($dbType !== 'pgsql') : ?>
                                    <th scope="col">BLOB</th>
                                <?php endif; ?>
                                <th scope="col" colspan="2">ACTION</th>
                            </thead>
                            <tbody>
                                <?php foreach(display() as $_display) : ?>
                                <tr>
                                    <th scope="row"><?php echo $_display->id ?></td>
                                    <td><?php echo $_display->data ?></td>
                                    <td><?php echo $_display->ref_number ?></td>
                                    <?php if($dbType !== 'pgsql') : ?>
                                        <td><img src="<?php echo 'data:image/jpeg;base64,'.base64_encode($_display->image_blob)?>" class="img-fluid"></td>
                                    <?php endif; ?>
                                    <td><a href="index.php?edit=true&id=<?php echo $_display->id ?>" class="text-primary">EDIT</a></td>
                                    <td><a href="index.php?delete=true&id=<?php echo $_display->id ?>" class="text-danger" onclick="return confirm('Are you sure you want to delete - ID #<?php echo $_display->id ?>?')">DELETE</a></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>
                    </div>
                </div>
                <?php endif ?>
            </main>
        </div>
    </body>
</html>