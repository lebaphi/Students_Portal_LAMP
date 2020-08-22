<?php
	/**
	 * Created by PhpStorm.
	 * User: lebaphi
	 * Date: 2019-05-25
	 * Time: 15:45
	 */
	
	error_reporting(E_ALL);
	
	$directory = new \RecursiveDirectoryIterator('.');
	$iterator = new \RecursiveIteratorIterator($directory);
	
	foreach ($iterator as $info) {
		$pathName = $info->getPathname();
		$fileName = $info->getFilename();
		
		if (strpos($pathName, '.git') !== false
			|| strpos($pathName, '.idea') !== false
			|| strpos($pathName, '/.') !== false
			|| strpos($pathName, '/..') !== false
			|| strpos($pathName, 'sql-scripts') !== false) {
			continue;
		}
		$fileContent = file_get_contents($pathName);
		$lastIndex = strripos($pathName, '/');
		
		$pathDir = substr($pathName, 0, $lastIndex + 1);
		$pathDir = str_replace('./', './production/', $pathDir);
		if (!file_exists($pathDir)) {
			mkdir($pathDir, 0777, true);
		}
		$finalFile = $pathDir . $fileName;
		if ((strpos($pathDir, '/authenticate/') !== false
				|| strpos($pathDir, '/manage/') !== false
				|| strpos($pathDir, '/common-scripts') !== false
				|| strpos($pathDir, '/components-scripts') !== false)
			&& strpos($fileName, '.js') !== false) {
			$jsContent = '';
			for ($i = 0; $i < 200; $i++) {
				$jsContent .= 'var _a = 1;';
			}
			$jsContent .= $fileContent;
			$tmpJsFile = $pathDir . $fileName . 'clone.js';
			copy($pathName, $tmpJsFile);
			file_put_contents($tmpJsFile, $jsContent);
			exec('javascript-obfuscator ' . $tmpJsFile . ' --output ' . $finalFile . ' --compact true --identifier-names-generator mangled --disable-console-output true --debug-protection true --identifiers-prefix ' . str_replace('.', 'x805', $fileName) . '');
			unlink($tmpJsFile);
		} else if (strpos($finalFile, '/production.build.') === false) {
			file_put_contents($finalFile, $fileContent);
		}
	}
	echo 'Build successfully' . PHP_EOL;