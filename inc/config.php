<?php
	//Configuration

	function read_cfgFile($file){
		//read a cfg file and ignore lines with #s at the start

		//resolve path to the file
		$path = dirname(__FILE__) . "/../config/" . $file; 

		$lines = array();

		if(is_file($path)){
			//only iterate if we are a file

			foreach(file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line){
				//iterate over all the lines if they are nonempty and not start with a #
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

		//resolve the url
		$uri = rel2abs($to, "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); 
		
		//set additional header code
		if($code){
			header($code);
		}

		//do the rewrite and die()
		header("Location: $uri");
		die("Please redirect your browser to $uri"); 
	}

	//from http://stackoverflow.com/questions/1243418/php-how-to-resolve-a-relative-url
	//resolves a relative url relative to a base. 
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

    function ptable($uid, $data, $colors){
    //prints a nice table
    //and a diagram using raphael

      $res = ""; 

    $total = 0;
    $percents = array();

    $labels_js = array(); 
    $data_js = array();
    $percents_js = array();  
    
    foreach($data as $key => $value){
      $total += $value; 
      array_push($labels_js, $key); 
      array_push($data_js, $value);
    }

    foreach($data as $key => $value){
      if($total > 0){
        $percents[$key] = 100 * ($value / $total);
      } else {
        $percents[$key] = 0; 
      }
       
      array_push($percents_js, $percents[$key]);
    }
    $res = $res . '
<div class="row">
  <div class="col-md-6"><div id="' . $uid . '"></div></div>
  <div class="clearfix visible-xs"></div>
  <div class="col-md-6" style="display:table; ">
    <div style="display: table-cell; vertical-align: middle;">
      <table class="table table-striped">
        <tr>
          <th>
            
          </th>
          <th>
            Absolute
          </th>
          <th>
            Relative
          </th>
        </tr>
'; 
        foreach($data as $key => $count){
$res = $res . '
        <tr>
          <td>
            '.$key.'
          </td>
          <td>
            '.$count.'
          </td>
          <td>
            '.$percents[$key].' %
          </td>
        </tr>
        '; }
$res = $res . '

      <tr>
          <td>
            Total
          </td>
          <td>
            '.$total.'
          </td>
          <td>
            100 %
          </td>
        </tr>
      </table>
    </div>
  </div>
</div>


<script type="text/javascript">
  $(function(){
    var uid = '.json_encode($uid).'; 
    var labels = '.json_encode($labels_js).'; 
    var counts = '.json_encode($data_js).';
    var colors = '.json_encode($colors).'; 
    var percents = '.json_encode($percents_js).'; 
    var total = '.json_encode($total).'; 

    for(var i=0;i<counts.length;i++){
      if(counts[i] == 0){
        counts.splice(i, 1);
        percents.splice(i, 1);
        labels.splice(i, 1); 
        if(colors !== 0){
          colors.splice(i, 1);
        }
        i--; 
      }
    }

    for(var i=0;i<labels.length;i++){
      labels[i] += "\\n("+counts[i]+")"; 
    }

    var hsize = $("#"+uid).parent().width(); 

    Raphael(uid, hsize, hsize).pieChart(hsize / 2, hsize / 2, hsize / 4, percents, labels, "#fff", colors);
    $("#"+uid).parent().parent().find("table").parent().parent().height($("#"+uid).height());
  });
  </script>
'; 
  return $res; 
  }
?>