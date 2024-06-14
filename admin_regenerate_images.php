<?php

include("system_header.php");

admin_only();

$regenerate_thumbnails_log = '';
$total_files_generated = 0;

if(isset($_GET['category'])){
	
	foreach($categories_array as $category_title=>$images_array){
		
		if($_GET['category']=="all" or $_GET['category']==$category_title){
		
			$working_directory = "files/".$category_title;
			
			// remove thumbnail of category
			@unlink($working_directory."/thumbnail.jpg");
			
			foreach($categories_array[$category_title] as $image_file){
				
				$regenerate_thumbnails_log .= "\nLoading ".$working_directory."/".$image_file.".jpg";
				
				if(!file_exists($working_directory."/".$image_file.".jpg") and file_exists($working_directory."/".$image_file.".JPG")){
					$regenerate_thumbnails_log .= "\nNOTE: renamed this file's extension from .JPG to .jpg for a better manipulation of files: ".$working_directory."/".$image_file.".JPG";
					
					rename($working_directory."/".$image_file.".JPG", $working_directory."/".$image_file.".jpg");
				}
				
				// scale down the "display" image
				@unlink($working_directory."/".$image_file."_small.jpg");
				if($imagemagick_installed){
					resize_in_limits($working_directory."/".$image_file.".jpg", $working_directory."/".$image_file."_small.jpg", $settings_photo_width, $settings_photo_height);
				} else {
					gd_resize_in_limits($working_directory."/".$image_file.".jpg", $working_directory."/".$image_file."_small.jpg", $settings_photo_width, $settings_photo_height);
				}
				
				// save the thumb image
				@unlink($working_directory."/".$image_file."_thumb.jpg");
				if($imagemagick_installed){
					crop_image($working_directory."/".$image_file.".jpg", $working_directory."/".$image_file."_thumb.jpg", $settings_thumbnail_width, $settings_thumbnail_height);
				} else {
					gd_crop_image($working_directory."/".$image_file.".jpg", $working_directory."/".$image_file."_thumb.jpg", $settings_thumbnail_width, $settings_thumbnail_height);
				}
				
				// make this image category thumbnail unless one already exists
				if(!file_exists($working_directory."/thumbnail.jpg")){
					copy($working_directory."/".$image_file."_thumb.jpg", $working_directory."/thumbnail.jpg");
				}
				
				$total_files_generated++;
				
			} // <<< loop over all images in this category
			
		} // <<< test if we re-generate this cat
		
	} // <<< loop over all categories 

} // <<< if category to generate is given in url

$regenerate_thumbnails_log = trim($regenerate_thumbnails_log);


// header("Location: ".$gallery_url."/admin-categories?message=category added&message_type=success");
// exit;


?>
<?php include("header.php");?>

<h1>Regenerar miniaturas</h1>

<p class="breadcrumb"><a href="/">Inicio</a> <?php if($gallery_url!=''){ ?>&gt; <a href="<?php echo $gallery_url;?>">Prodcutos</a> <?php } ?>&gt; <a href="<?php echo $gallery_url;?>/admin">administracion</a> &gt; Regenerar miniaturas</p>



<?php if(!function_exists('imagecreatetruecolor')){?>

	<p class="message_error">ERROR: missing PHP function imagecreatetruecolor(); this means that <strong>PHP GD image library</strong> is not installed on your system, ask your host how to install it.
    </p>

<?php } ?>


<?php if($regenerate_thumbnails_log!=""){ ?>

    <h2>Log</h2>
    <p><?php echo nl2br(htmlentities($regenerate_thumbnails_log, ENT_QUOTES, "UTF-8"));?></p>
    <p style="color:#EA0000;">Miniaturas generadas de imágenes para visualización de <?php echo $total_files_generated;?> imagenes.</p>
    
    <p style="margin-bottom:0px;"><a class="liquid_button" style="padding-left:2em; padding-right:2em;" href="<?php echo $gallery_url;?>/admin-regenerate-images">Ir atras</a></p>
    
<?php } ?>





<?php if(!isset($_GET['category'])){?>

    <p>Esta página recreará todas las imágenes en miniatura de los archivos originales almacenados en el servidor.</p>
    
    <p>Utilice también esta página para generar miniaturas de imágenes cargadas por FTP.</p>
    
    <p>Puede tardar unos minutos dependiendo de la cantidad de fotos y la velocidad de su servidor, por lo que si nota tiempos de espera o errores de memoria, intente generar una categoría a la vez.</p>
    
    <form method="get" action="" style="display:block; margin-top:1em; margin-bottom:0px;">
    
    <select name="category" style="padding:0.5em;">
    
    <option value="all">Todas</option>
    <?php foreach($categories_array as $category_title=>$images_array){ ?>
      <option value="<?php echo htmlentities($category_title, ENT_QUOTES, "utf-8");?>"><?php echo htmlentities($category_title, ENT_QUOTES, "utf-8");?></option>
    <?php } ?>
    
    </select>
    
    <br />
    
    <input type="submit" class="button_190" value="Regenerar" style="margin-top:1em;" />
    
    </form>

<?php } ?>



<?php include("footer.php");?>