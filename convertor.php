<?php
// if(isset($_POST['upload'])) {
//     echo 'true';
// } else {
//     echo 'false';
// }
// echo '<br>';
if(isset($_POST['upload'])) {

//Primul pas: verificam daca este introdusa imaginea
if(!isset($_FILES['image']) || $_FILES['image']['error'] == UPLOAD_ERR_NO_FILE) {
    echo 'Nu ati selectat nicio imagine';
} else {
    $errors = array();

    //Salvarea fisierului introdus intr-o variabila
    $file = $_FILES['image'];

    //Salvarea informatiilor despre poza in variabile
    $file_name = $file['name'];
    $file_full_path = $file['full_path'];
    $file_type = $file['type'];
    $file_tmp_name = $file['tmp_name'];
    $file_size = $file['size'];

    // Extragerea extensiei fisierului si salvarea acestuia intr o variabila
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

    //Trecerea extensiilor/ formatelor de poza care sunt acceptate
    $extensions = ["jpg", "jpeg", "png"];

    if(!in_array($file_ext, $extensions)) {
        $errors[] = 'Fisierul introdus trebuie sa fie JPG, PNG sau JPEG';
    }

    // if($file_size > 97152 ) {
    //     echo 'Fisierele mai mari de 2mb nu sunt suportate.';
    // }

    if(!empty($errors)) {
        echo implode(',', $errors);
    } else {
        echo "<p style='color: green; font-size: 20px;'>Imaginea a fost convertita cu succes!!!</p>";
        move_uploaded_file($file_tmp_name, "uploads/".$file_name);
        // Crearea unei imagini din imaginea originala
        $original_image = imagecreatefromjpeg("uploads/".$file_name);

        // Salvarea imaginii in format webp
        imagewebp($original_image, "uploads/".pathinfo($file_name, PATHINFO_FILENAME).".webp");

        // Distrugerea imaginii din memorie
        imagedestroy($original_image);
    }



} 
}


?>


<!-- Formularul de upload -->
<form method='POST' action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
<input type='file' name='image' required/>
<input type='submit' name="upload" value='Upload' />
</form>


<?php
// genereaza calea catre imaginea webp
if(empty($errors)) : ?>
<?php
$webp_path = 'uploads/' . pathinfo($file_name, PATHINFO_FILENAME) . '.webp';
$original_path = 'uploads/'.$file_name;
?>
<picture>
  <source srcset="<?php echo $webp_path;?>" type="image/webp">
  <source srcset="<?php echo $original_path;?>" type="<?php echo $file_type;?>">
  <img style="width: 500px; height: auto;" src="<?php echo $original_path;?>" alt="image">
</picture>

<?php
// genereaza calea catre imaginea webp
$webp_path = 'uploads/' . pathinfo($file_name, PATHINFO_FILENAME) . '.webp';
?>

<a href="<?php echo $webp_path; ?>" download>Download image</a>

<?php endif; ?>
