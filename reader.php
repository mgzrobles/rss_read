<?php
function read_left() {
	print lectorRSS("http://feeds.feedburner.com/unblogenred");
}

function read_right() {
	print lectorRSS("http://feeds.feedburner.com/blogcmt/feed");
}


function lectorRSS($url,$elementos=6,$inicio=0) {
	$cache_version = "cache/" . basename($url);
	$archivo = fopen($url, 'r');
	stream_set_blocking($archivo,true);
	stream_set_timeout($archivo, 5);
	$datos = stream_get_contents($archivo);
	$status = stream_get_meta_data($archivo);
	fclose($archivo);
	if ($status['timed_out']) {
		$noticias = simplexml_load_file($cache_version);
	}
	else {
		$archivo_cache = fopen($cache_version, 'w');
		fwrite($archivo_cache, $datos);
		fclose($archivo_cache);
		$noticias = simplexml_load_string($datos);
	}
	$ContadorNoticias=1;
	$output = "<ul>";
	foreach ($noticias->channel->item as $noticia) {
		if($ContadorNoticias<$elementos){
			if($ContadorNoticias>$inicio){
				$output .= "<li><a href='".$noticia->link."' target='_blank' class='tooltip' title='".utf8_decode($noticia->title)."'>";
				$output .= utf8_decode($noticia->title);
				$output .= "</a></li>";
			}
			$ContadorNoticias = $ContadorNoticias + 1;
		}
	}
	$output .= "</ul>";
	
	return $output;
}