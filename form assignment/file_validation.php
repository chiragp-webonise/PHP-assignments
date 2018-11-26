<?php
    $image_file_name ;
    $image_path;
    $doc_file_name;
    $doc_path;
   function imageUpload(){
    if(isset($_FILES['image'])){
       $errors= array();
       global$image_file_name;
       global $image_path;
       $image_file_name = $_FILES['image']['name'];
       $file_size =$_FILES['image']['size'];
       $file_tmp =$_FILES['image']['tmp_name'];
       $file_type=$_FILES['image']['type'];
       $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
       
       $expensions= array("jpeg","jpg","png");
       
       if(in_array($file_ext,$expensions)=== false){
          $errors[]="extension not allowed, please choose a JPEG or PNG file.";
       }
       
       if($file_size >= 3097152){
          $errors[]='File size must less then or equal to 3 MB';
       }
       
       if(empty($errors)==true){
          move_uploaded_file($file_tmp,"uploads/images/".$image_file_name);
          $image_path="uploads/images/".$image_file_name;
         documentUpload();
       }else{
           echo "error";
          print_r($errors);
       }
    }
   }

   function documentUpload(){
    if(isset($_FILES['fileToUpload'])){
        $errors= array();
        global $doc_path;
        global $doc_file_name;
        global $image_path;
        $doc_file_name = $_FILES['fileToUpload']['name'];
        $file_size =$_FILES['fileToUpload']['size'];
        $file_tmp =$_FILES['fileToUpload']['tmp_name'];
        $file_type=$_FILES['fileToUpload']['type'];
        $file_ext=strtolower(end(explode('.',$_FILES['fileToUpload']['name'])));
        
        $expensions= array("docx");
        
        if(in_array($file_ext,$expensions)=== false){
           $errors[]="extension not allowed, please choose a docx file.";
        }
        
        if($file_size >= 10097152){
           $errors[]='File size must less then or equal to 10 MB';
        }
        
        if(empty($errors)==true){
           move_uploaded_file($file_tmp,"uploads/documents/".$doc_file_name);
           $doc_path="uploads/documents/".$doc_file_name;
            // $myfile = fopen("uploads/documents/".$doc_file_name, "a") or die("Unable to open file!");
            // $txt = "Together we can change the world, just one random act of kindness at a time.";
            // fwrite($myfile, "\n". $txt);
            // fclose($myfile);
          writeIntoDoc($doc_path);
          insertIntoDb($image_path);
        }else{
            echo "error";
           print_r($errors);
        }
     }  
   }
   
function writeIntoDoc($doc_path){
    require_once 'vendor/autoload.php';
    $phpWord = \PhpOffice\PhpWord\IOFactory::load($doc_path);
    $sections = $phpWord->getSections();
    $section = $sections[0];

    $section->addText(
        '"Together we can change the world, just one random act of kindness at a time."'
    );

    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    $objWriter->save($doc_path);
}
function read_file_docx($filename){
    $striped_content = '';
    $content = '';
      if(!$filename || !file_exists($filename)) return false;
          $zip = zip_open($filename);

        if (!$zip || is_numeric($zip)) return false;

        while ($zip_entry = zip_read($zip)) {

            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

            if (zip_entry_name($zip_entry) != "word/document.xml") continue;

            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

            zip_entry_close($zip_entry);
        }// end while

        zip_close($zip);

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        $striped_content = strip_tags($content);

        return $striped_content;
}
function insertIntoDb($image_path){
    global $doc_path;
    $db_connection = pg_connect ("host=localhost dbname=file_details user=postgres password=postgres");
    if($db_connection) {
        $doc_content=read_file_docx($doc_path);
        $result = pg_Exec($db_connection,"INSERT INTO upload_detail(title,image_path,document_content) VALUES ('$_POST[title]','$image_path','$doc_content');"); 
        pg_Close($conn);
        header("Location:display_data.php");
    } else {
        echo 'there has been an error connecting';
    } 
}
function displayDataFromDb(){
    $db_connection = pg_connect ("host=localhost dbname=file_details user=postgres password=postgres");
    if($db_connection) {
    $query = 'select * from upload_detail';
  
    $result = pg_query($query);
  
    $i = 0;
    echo '<head><link rel="stylesheet" href="css/style.css"></head>';
     echo '<div style="overflow-x:auto;">';
    echo '<html><body><table><tr>';
    while ($i < pg_num_fields($result))
    {
        $fieldName = pg_field_name($result, $i);
        echo '<td>' . $fieldName . '</td>';
        $i = $i + 1;
    }
    echo '</tr>';
    $i = 0;
  
    while ($row = pg_fetch_row($result)) 
    {
        echo '<tr>';
        $count = count($row);
        $y = 0;
        while ($y < $count)
        {
            $c_row = current($row);
            echo '<td>' . $c_row . '</td>';
            next($row);
            $y = $y + 1;
        }
        echo '</tr>';
        $i = $i + 1;
    }
    pg_free_result($result);
  
    echo '</table></body></html>';
    echo '</div>';
    pg_Close($conn);
    } else {
         echo 'there has been an error connecting';
    } 
  }
imageUpload();
?>