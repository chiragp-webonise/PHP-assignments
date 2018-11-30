<html>
<head>
<link rel="stylesheet" href="../css/style.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script>
    function validateTitle(articleTitle) {
    const TITLE_CHARACTER_LIMIT = 100;

    if (articleTitle.length > TITLE_CHARACTER_LIMIT) {
        alert("Title cannot be more than 100 letters long");
        return false;
    }
 
    return true;
}
// Return the first few bytes of the file as a hex string
function getBLOBFileHeader(url, blob,fileType, callback) {
    var fileReader = new FileReader();
    fileReader.onloadend = function(e) {
      var arr = (new Uint8Array(e.target.result)).subarray(0, 4);
      var header = "";
      for (var i = 0; i < arr.length; i++) {
        header += arr[i].toString(16);
      }
      callback(url,fileType, header);
    };
    fileReader.readAsArrayBuffer(blob);
  }
  
  function headerCallback(url,fileType, headerString) {
    printHeaderInfo(url,fileType, headerString,validateFileType);
  }
  
  function remoteCallback(url, blob,fileType) {
    getBLOBFileHeader(url, blob,fileType, headerCallback);
  }
  
  // Add more from http://en.wikipedia.org/wiki/List_of_file_signatures
  function mimeType(headerString) {
    switch (headerString) {
      case "89504e47":
        type = "image/png";
        break;
      case "504b34":
        type ="docx";
        break;
      case "ffd8ffe0":
      case "ffd8ffe1":
      case "ffd8ffe2":
        type = "image/jpeg";
        break;
      default:
        type = "unknown";
        break;
    }
    return type;
  }
  
  function printHeaderInfo(url,fileType,headerString,callback) {
    var allowedFiles = ["image/jpg", "image/jpeg", "image/png",];
    var allowedDocFile = ["docx","doc"];
     var mType=mimeType(headerString);
     if(fileType=="image"){
        callback(mType,allowedFiles,fileType);
    }
    if(fileType=="doc"){
        callback(mType,allowedDocFile,fileType);
    }
  }

$(document).ready(function(){
// Check for FileReader support
if (window.FileReader && window.Blob) {

/* Handle local files */
$("#image").on('change', function(event) {
    var file = event.target.files[0];
      if (file.size >= 3 * 1024 * 1024) {
        alert("File size must be at most 3MB");
        return;
      }
    remoteCallback(escape(file.name), file,"image");
});

$("#fileToUpload").on('change', function(event) {
    var file = event.target.files[0];
      if (file.size >= 10 * 1024 * 1024) {
        alert("File size must be at most 10MB");
        return;
      }
    remoteCallback(escape(file.name), file,"doc");
});

} else {
// File and Blob are not supported
alert("It seems your browser doesn't support FileReader");
} 
});
function validateFileType(mType, allowedFiles,fileType) {
    if(fileType=="image"){
        if ($.inArray(mType, allowedFiles) < 0) {
                alert("Invalid image type should be jpg,jpeg and png");
                $("#image").val("");
                return;
        }
    }
    if(fileType=="doc"){
        if ($.inArray(mType, allowedFiles) < 0) {
                alert("Invalid document type should be docx");
                $("#fileToUpload").val("");
                return;
        }
    }
}

function validateDocument(documentFile) {
    const FILE_SIZE_LIMIT = 10240;
    var allowedFiles = [".doc", ".docx"];
    try{
        var documentFileSize = (documentFile.files[0].size / 1024).toFixed(2);    
    }
    catch(err){
        alert(err);
    }

    if (documentFileSize > FILE_SIZE_LIMIT) {
        alert("Document cannot be greater than 10 MB");
        return false;
    }
    return true;
}

function validateFields() {
    var articleTitle = document.getElementById("title").value;
    var imageFile = document.getElementById("image");
    var documentFile = document.getElementById("fileToUpload");

    if (!validateTitle(articleTitle)) {
        return false;
    }

    return true;
}
    </script>
</head>
<body>
<meta name="viewport" content="width=device-width, initial-scale=1">
<div class="container">
  <form id="uploader" action="../file_validation.php" method="POST" onsubmit="return validateFields()" enctype="multipart/form-data">
    <div class="row">
      <div class="col-25">
        <label for="fname">Title</label>
      </div>
      <div class="col-75">
        <input type="text" id="title" name="title" placeholder="Your title.." required> 
      </div>
    </div>
    <div class="row">
      <div class="col-25">
        <label for="lname">Select image to upload</label>
      </div>
      <div class="col-75">
        <input type="file" name="image" id="image" required>
      </div>
    </div>
    <div class="row">
      <div class="col-25">
      <label for="lname">Select document to upload</label>
      </div>
      <div class="col-75">
        <input type="file" name="fileToUpload" id="fileToUpload" required>        
      </div>
    </div>
    <div class="row">
      <input name="btnSubmit" id="btnSubmit" type="submit" value="Submit"/>
    </div>
  </form>
</div>

</body>
</html>
