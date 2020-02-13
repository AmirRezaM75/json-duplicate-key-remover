<?php

	$stdin = fopen('php://stdin', 'r');

	$stdout = fopen('php://stdout', 'w');

	fwrite($stdout, "Which folder you kept your JSON files? \n");

	fwrite($stdout, "> ");

	$inputDirectory = trim(fgets($stdin));

	if (! is_dir($inputDirectory)) {
		fwrite($stdout, "$inputDirectory is not a directory \n");
		return;
	}

	fwrite($stdout, "Where should I store your final output? (default: output)\n");

	fwrite($stdout, "> ");

	$outputDirectory = strlen(trim(fgets($stdin))) > 0 ? trim(fgets($stdin)) : 'output';
	
	$files = array_map(function($file) use ($inputDirectory) {
		return str_replace("$inputDirectory/", '', $file);
	}, glob("$inputDirectory/*.json"));

	foreach ($files as $file) {

		$array = json_decode(file_get_contents("$inputDirectory/$file"), TRUE);

		// It only works for indexed arrays
		// $array = array_unique($array, SORT_REGULAR);

		$result = json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

		if (! is_dir($outputDirectory))
			mkdir($outputDirectory);

		file_put_contents("$outputDirectory/$file", $result);
	}