<?php
  // sólo los usuarios registrados pueden adicionar una entrada
  gatekeeper();
 
  // leer los campos de entrada del formulario
  $title = get_input('title');
  $body = get_input('body');
  $tags = string_to_tag_array(get_input('etiquetas'));
 
  // crear un nuevo objeto blog
  $blti_consumer = new ElggObject();
  $blti_consumer->title = $title;
  $blti_consumer->description = $body;
  $blti_consumer->subtype = "consumer_blti";
 
  // por ahora todas las publicaciones son públicas
  $blti_consumer->access_id = ACCESS_PUBLIC;
 
  // el propietario es el usuario registrado
  $blti_consumer->owner_guid = get_loggedin_userid();
 
  // guardar las etiqutas como metadatos
  $blti_consumer->tags = $tags;
 
  // guardar en la base de datos
  $blti_consumer->save();
 
  // forward user to a page that displays the post
  forward($blti_consumer->getURL());
?>
