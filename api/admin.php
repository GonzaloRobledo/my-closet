<?php

include("system_header.php");

admin_only();

//

?>
<?php include("header.php");?>
<h1>Admin</h1>
<p class="breadcrumb"><a href="/">Inicio</a> <?php if($gallery_url!=''){ ?>&gt; <a href="<?php echo $gallery_url;?>">Productos</a> <?php } ?>&gt; Administracion</p>

<p>
<strong><a href="<?php echo $gallery_url;?>/admin-categories">Gestionar categorias (<?php echo count($categories_array);?>)</a></strong><br />
Crear nuevas categorías, eliminar o renombrar las existentes, etc.
</p>

<p>
<strong><a href="<?php echo $gallery_url;?>/admin-regenerate-images">Regenerar Imagenes </a></strong> <br />
Utilice esta función cuando desee volver a generar miniaturas basadas en el archivo original de cada imagen. Cada imagen se almacena en el servidor en 3 archivos (archivo original.jpg, archivo_pequeño.jpg y archivo_miniatura.jpg).<br />
También puede ejecutar este script si cambió el ancho/alto de sus imágenes dentro de settings.php<br />

<span style="color:#EA0000;">Si el script de regeneración de fotos nunca se completa o si recibe un error del servidor, intente aumentar la limitación del tiempo de ejecución del script PHP o aumente la limitación de memoria de PHP.</span>
</p>

<?php include("footer.php");?>
