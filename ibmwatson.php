<?php

/*
	Función básica de traducción con IBM Watson en PHP
	Autor: Ángel Cano Idáñez
	Twitter: @equisele
	Objetivo: Aprendizaje
	Thanks: @thisjrodriguez for discovering this Language translator
*/

function ibmWatson($text,$model)
{
	/* 
		Implementación básica de IBM Watson sobre php
		
		1. Date de alta en https://www.ibm.com/watson/services/language-translator/
		y crea una cuenta gratuita.
		
		2. Deja los valores por defecto y avanza; te devolverá un api Key y una
		url base depende de la zona elegida. 
		
		3. Introduce los valores a continuación..
	*/
	
	// Api Key de IBM Watson
	$apiKey = 'XXXXXXXXXXXXXXX';
	// Url base de IBM Watson
	$baseUrl = 'https://gateway-lon.watsonplatform.net/language-translator/api';
	// No modificar el siguiente valor. Sólo vamos a utilizar la traducción de texto en linea
	// Puedes incluir html también.
	$query = '/v3/translate?version=2018-05-01';
	// Construímos la url de petición
	$requestUrl = $baseUrl . $query;

	// Forzamos la conversión de caracteres a utf-8
	$myText = mb_convert_encoding($text, "UTF-8");

	// El "modelo" es obligatorio. Corresponde a los pares de
	// idiomas origen y destino disponibles según idioma origen y destino.
	// Ej. "es-en" que significa: traducir del español al inglés.
	// Los modelos, por defecto, desde español a otro idioma son: es-en | es-ca | es-fr
	// Sin embargo desde inglés hay traducción hacia 21 idiomas diferentes.
	// Más información aquí: https://cloud.ibm.com/apidocs/language-translator
	$myModel = $model;

	// Añadimos los parámetros a enviar a la url de petición a continuación
	
	$options = array(
		"text" => $myText,
		"model_id" => $myModel
	);
	
	// Convertimos la matriz en un objeto json
	
	$jsonOptions = json_encode($options);
	
	// Inicializamos la sesión cURL
	
	$ch = curl_init();

	// Definimos los parámetros de petición como son:
	// la url, variables POST y el envío del apiKey para la autenticación

	curl_setopt($ch, CURLOPT_URL, $requestUrl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonOptions);
	curl_setopt($ch, CURLOPT_USERPWD, 'apikey' . ':' . $apiKey);

	// Configuramos la cabecera de petición en formato json 

	$headers = array();
	$headers[] = 'Content-Type: application/json';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	// Enviamos la petición completa y la almacenamos en la variable $result

	$result = curl_exec($ch);
	
	// Si se produce un error en la petición...
	
	if (curl_errno($ch)) {
		
		// imprimimos el error. Que además, es devuelto en json
		echo 'Error:' . curl_error($ch);
		
		// Puedes consultar aquí los errores devueltos por IBM Watson
		// https://cloud.ibm.com/apidocs/language-translator
	}
	
	// Cerramos la conexión cURL
	
	curl_close($ch);

	// devolvemos el resultado de la petición, sea cual sea el resultado

	return $result;

}

/*
	============ demostración ============
*/
	// Establecemos la cabecera al tipo de contenido json
	header('Content-Type: application/json');
	// Texto de ejemplo con html.
	$demo = '<p>Aquí tienes una demostración de <a href="#">traducción de textos</a> con <strong>IBM Watson</strong>. Una de la grandes ventajas: es capaz de procesar <em>texto con html</em>.</p>'; 
	// Ejecutamos la función con un texto de ejemplo e imprimios el resultado
	echo ibmWatson($demo,"es-en");
	
?>
