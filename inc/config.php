<?php
	function read_cfgFile($file){
		//read a cfg file and ignore lines with #s at the start

		$path = dirname(__FILE__) . "/../config/" . $file; 

		$lines = array();

		if(is_file($path)){
			foreach(file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line){
			   if(substr($line, 0, 1) === '#'){} else {
			   		if(!empty($line)){
			   			array_push($lines, $line);
			   		}
			   }
			}
		}

		return $lines; 
	}

	function cfg_contains($file, $line){
		//check if a line is contained in a cfg file as above
		return in_array($line, read_cfgFile($file)); 
	}

	function redirect_to($to, $code = "HTTP/1.1 303 See Other"){
		//redirects to a relative url
		$uri = rel2abs($to, "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); 
		if($code){
			header($code);
		}
		header("Location: $uri");
		die("Please redirect your browser to $uri"); 
	}

	//from http://stackoverflow.com/questions/1243418/php-how-to-resolve-a-relative-url
	function rel2abs($rel, $base)
    {
        /* return if already absolute URL */
        if (parse_url($rel, PHP_URL_SCHEME) != '') return $rel;

        /* queries and anchors */
        if ($rel[0]=='#' || $rel[0]=='?') return $base.$rel;

        /* parse base URL and convert to local variables:
         $scheme, $host, $path */
        extract(parse_url($base));

        /* remove non-directory element from path */
        $path = preg_replace('#/[^/]*$#', '', $path);

        /* destroy path if relative url points to root */
        if ($rel[0] == '/') $path = '';

        /* dirty absolute URL */
        $abs = "$host$path/$rel";

        /* replace '//' or '/./' or '/foo/../' with '/' */
        $re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
        for($n=1; $n>0; $abs=preg_replace($re, '/', $abs, -1, $n)) {}

        /* absolute URL is ready! */
        return $scheme.'://'.$abs;
    }
?>