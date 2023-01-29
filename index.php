<?php
if(isset($_POST['convert'])) {
    if(!empty($_FILES['images']['name'][0])) {
        $images = $_FILES['images'];
        $allowed = array('jpg', 'jpeg', 'png');
        $errors = array();
     
        // parcurgem fiecare imagine
        for($i = 0; $i < count($images['name']); $i++ ) {
            $ext = strtolower(pathinfo($images['name'][$i], PATHINFO_EXTENSION));

            if(!in_array($ext, $allowed)) {
                $errors[] = $images['name'][$i] . "nu este un format suportat.";
                continue;
            }

            $image = $images['tmp_name'][$i];
            $new_name = pathinfo($images['name'][$i], PATHINFO_FILENAME) . ".webp";
            $destination = "newconv/" . $new_name;
            

            // Convertim imaginea
            if($ext == 'jpg' || $ext == 'jpeg'){
                $img = imagecreatefromjpeg($image);
            }else{
                $img = imagecreatefrompng($image);
            }

            if(imagewebp($img, $destination)) {
                echo $images['name'][$i] . " a fost convertit cu succes la " . $new_name . "<br>";
                echo "<img style='width: 100px;' src='newconv/".$new_name."' alt='".$images['name'][$i]."'><br>";
                echo "<a href='newconv/".$new_name."' download='".$new_name."'>Download</a><br>";

            } else {
                $errors[] = "Eroare la convertirea imaginii " . $images['name'][$i];
            }

        }
    }
                imagedestroy($img);


    // Afisam eventualele erori
    if(count($errors) > 0) {
        foreach($errors as $error) {
            echo $error . "<br>";
        }
    }
    
}


?>
<?php


?>
<form action="index.php" method="post" enctype="multipart/form-data">
    <input type="file" name="images[]" multiple required>
    <input type="submit" name="convert" value="Convert to WEBP">
</form>
<a href="index.php">Reseteaza</a>


<?php
if(isset($_POST['convert'])) {
    if(!empty($_FILES['images']['name'][0])) {
$zip = new ZipArchive();
$zip_name = "images.zip";
if ($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE) {
    exit("cannot open <$zip_name>\n");
}
for($i = 0; $i < count($images['name']); $i++) {
    $zip->addFile("newconv/".pathinfo($images['name'][$i], PATHINFO_FILENAME) . ".webp",pathinfo($images['name'][$i], PATHINFO_FILENAME) . ".webp");
}
$zip->close();

echo "<a href='".$zip_name."' download='".$zip_name."'>Download all images</a>";
// for($i = 0; $i < count($images['name']); $i++) {
//     unlink("newconv/".pathinfo($images['name'][$i], PATHINFO_FILENAME) . ".webp");
// }


    }
}

?>
