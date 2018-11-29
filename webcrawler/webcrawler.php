<?php
    static $timeout = 2;
    static $agent   = 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)';
    $content;
 
    function http_request($url,$agent,$timeout) {
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL,            $url);
       curl_setopt($ch, CURLOPT_USERAGENT,      $agent);
       curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
       curl_setopt($ch, CURLOPT_TIMEOUT,        $timeout);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       $response = curl_exec($ch);
       curl_close($ch);
       return $response;
    }
 
 
    function strip_whitespace($data) {
       $data = preg_replace('/\s+/', ' ', $data);
       return trim($data);
    }
 
 
    function extract_elements($tag, $data,$depth) {
      $response = array();
      if ($depth==0){
        return $response;
      }
       $dom      = new DOMDocument;
       @$dom->loadHTML($data);

       foreach ( $dom->getElementsByTagName($tag) as $index => $element ) {
          global $content;
          $href = $element->getAttribute('href');
          $content = file_get_contents($href);
          $res = preg_match("/<title>(.*)<\/title>/siU", $content, $title_matches);
          if($res) {
              $title = preg_replace('/\s+/', ' ', $title_matches[1]);
              $title = trim($title);
            }
            $xpath = new DOMXPath($dom);
            $src = $xpath->evaluate("string(//img/@src)");
              $response[]=array(
                "Title"=>$title,
                "Image"=>$src,
                "Sublink"=>extract_elements('a', $content,$depth-1)
              );
        }
       return $response;
    }

 $data  = http_request('https://www.google.com/',$agent,$timeout);
 $linksData = extract_elements('a', $data,2);
 if ( count($links) > 0 ) {
    file_put_contents('links.json', json_encode($linksData, JSON_PRETTY_PRINT));
 }
?>