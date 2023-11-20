<?php

echo $_POST['name'];
echo "<br>";
if(!empty($_FILES['img']['tmp_name'])){
    echo $_FILES['img']['tmp_name'];
    echo "<br>";
    echo $_FILES['img']['name'];
    echo "<br>";
    echo $_FILES['img']['type'];
    echo "<br>";
    echo $_FILES['img']['size'];
    $tmp=explode(".",$_FILES['img']['name']);
    $subname=".".end($tmp);
    $filename=date("YmdHis").rand(10000,99999).$subname;
    move_uploaded_file($_FILES['img']['tmp_name'],"../imgs/".$filename);
    header("location:../upload.php?img=".$filename);
}else{
    header("location:../upload.php?err=上傳失敗");
}

?>