<?php

include("system_header.php");

admin_only();

// add new category
if(isset($_POST['category_title_box']) and $_POST['category_title_box']!=''){
	
	$new_category_title = string_to_file_name($_POST['category_title_box']);
	
	mkdir('files/'.$new_category_title);
	
	if(!is_dir('files/'.$new_category_title)){
		header("Location: ".$gallery_url."/admin-categories?message=cannnot create category, make sure the /files folder is writable on server&message_type=error");
		exit;
	}
	
	header("Location: ".$gallery_url."/admin-categories?message=category added&message_type=success");
	exit;
}

// !!! delete category and all photos inside !!!
if(isset($_GET['category_to_delete']) and $_GET['category_to_delete']!='' and $_GET['key']==md5($_SESSION['session_secret'].$_GET['category_to_delete'])){
	rmdir_r('files/'.strip_tags(trim($_GET['category_to_delete'])));
	
	header("Location: ".$gallery_url."/admin-categories?message=category ".$_GET['category_to_delete']." deleted&message_type=success");
	exit;
}

?>
<?php include("header.php");?>
<h1>Gestionar Categorias</h1>
<p class="breadcrumb"><a href="/">Inicio</a> <?php if($gallery_url!=''){ ?>&gt; <a href="<?php echo $gallery_url;?>">Productos</a> <?php } ?>&gt; <a href="<?php echo $gallery_url;?>/admin">admin</a> &gt; categories</p>

<?php if(count($categories_array)<=0){?>
<p>No hay categorías de fotos, crea una o más categorías antes de subir fotos</p>
<?php } ?>

<?php if(!is_writable('files/')){?>
<p class="message_error">error: el directorio que almacena todas sus categorías y fotos no se puede escribir, haga que se pueda escribir en esta carpeta en el servidor: <strong>/files </strong>
<br />En la mayoría de los servidores, esto se hace estableciendo el código de permiso 775 o 777 en la carpeta <strong>/files</strong>, usando un programa FTP o el administrador de archivos de su panel de alojamiento web..</p>
<?php } ?>

<form id="form1" name="form1" method="post" action="" style="margin:0px;">
        
    <label for="category_title_box">Nombre de la Categoria</label><br />
		  <input name="category_title_box" type="text" id="category_title_box" size="20" />
          <br />

          <input name="button" type="submit" class="button_190" id="button" value="Añadir categoria" style="margin-top:10px;" />
</form>

<script type="text/javascript">
document.getElementById('category_title_box').focus();
</script>

  
<?php if(count($categories_array)>0){?>

	<h2 style="margin-top:30px;">Categorias Existentes</h2>
	
    	
    <table width="100%" border="0" cellspacing="0" cellpadding="10">
	<?php foreach($categories_array as $photo_category=>$photos_array){?>
    
    <?php
	if(!isset($row_color) or $row_color=='#F7F7F7'){
		$row_color = '#F1F1F1';
	} else {
		$row_color = '#F7F7F7';
	}
	?>
	
	<tr>
  
    <td height="10" align="left" valign="middle" bgcolor="<?php echo $row_color;?>" style="text-align:left;"><a href="<?php echo $gallery_url;?>/<?php echo htmlentities($photo_category, ENT_QUOTES, "UTF-8");?>" style=" background-image:url('layout/folder_2.png'); background-repeat:no-repeat;   background-position:left center; padding:4px; padding-left:40px; text-transform:capitalize; text-decoration:none; font-size:14px;"><strong><?php echo htmlentities(str_replace('-',' ', $photo_category), ENT_QUOTES, "UTF-8");?> (<?php echo count($photos_array);?>)</strong></a></td>
    
    <td width="300" height="10" align="left" valign="middle" bgcolor="<?php echo $row_color;?>">
    
    
    
    <input name="button2" type="button" class="cancel_button_30" id="button2" style="float:right;" onmouseup="if(confirm('Delete category <?php echo htmlentities(addslashes($photo_category), ENT_QUOTES, "UTF-8");?> ? \nIt contains <?php echo count($photos_array);?> photos')){window.location='?key=<?php echo md5($_SESSION['session_secret'].$photo_category);?>&category_to_delete=<?php echo rawurlencode($photo_category);?>';}"  />
    
    <input name="button3" type="button" class="button_110" id="button3" value="Renombrar" style="float:right; margin-right:10px;" onmouseup="window.location='<?php echo $gallery_url;?>/admin-category-edit?category_title=<?php echo rawurlencode($photo_category);?>';"/>
    
    <input name="button3" type="button" class="button_110" id="button4" value="Fotos" style="float:right; margin-right:10px;" onmouseup="window.location='<?php echo $gallery_url;?>/<?php echo rawurlencode($photo_category);?>/edit-photos';"/>
    
    </td>
    
    </tr>

    <?php } ?>
    
    </table>
<?php } ?>

<?php include("footer.php");?>
