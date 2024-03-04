<?php
require_once 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

include "db_export.php";

$options = new Options();
$options->set('defaultFont', 'GJGL'); // 設置默認字體，可以根據需要更改
$dompdf = new Dompdf($options);


if(!empty($_POST)){
$rows=all("20200706"," where  `投票所編號` in ('".join("','",$_POST['select'])."')");

//$filename=date("Ymd").rand(100000000,999999999);
$filename=date("Ymd").rand(100000000,999999999).".pdf";



// 圖片路徑
$imagePath = './icon/wda.png'; // 將 "path_to_your_image.png" 替換為你的圖片路徑

// 讀取圖片
$imageData = file_get_contents($imagePath);

// 將圖片轉換為 BASE64 字符串
$base64Image = base64_encode($imageData);

// 將 BASE64 字符串嵌入到 Data URI 中
$dataUri = 'data:image/png;base64,' . $base64Image;

//$file=fopen("./doc/{$filename}.csv",'w+');
//fwrite($file, "\xEF\xBB\xBF");
$html="<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Document</title>
    <style>
        table{
            border-collapse:collapse;
            padding:2px;
            border:1px solid #999;
            font-size:12px;
        }
        td{
            border:1px solid #666;
            padding:5px;
        }
        tr:nth-child(odd){
            background:lightgreen;
        }
        tr:nth-child(1){
            background:black;
            color:white;
            font-weight:bold;
        }
    </style>
</head>
<body>
<img src='{$dataUri}' style='width:150px;height:150px'>
";
    
$html .="<table>";
$chk=false;
foreach($rows as $row){
    if(!$chk){
        $cols=array_keys($row);
        $html .="<tr>";
        foreach($cols as $idx => $col){

            $html .="<td>";
            $html .=$col;
            $html .="</td>";
            if($idx==7){

                $html .="</tr>";
                $html .="<tr>";
            }
        }
        $html .="</tr>";
        $chk=true;
    }

    //fwrite($file,join(",",$row)."\r\n");
    $html .="<tr>";
    foreach($row as $i => $r){
        $html .="<td>";
        $html .=$r;
        $html .="</td>";
        if($i=='候選人3票數'){

            $html .="</tr>";
            $html .="<tr>";
        }
    }
    $html .="</tr>";
}

$html .="</table></body>
</html>";


// 將 HTML 載入 Dompdf
$dompdf->loadHtml($html);

// 設置紙張方向為橫式
$dompdf->setPaper('A4', 'landscape'); 

// 渲染 PDF（可選）
$dompdf->render();

// 將 PDF 輸出到文件或直接輸出到瀏覽器
$dompdf->stream("./doc/{$filename}", array('Attachment' => 1));
//fclose($file);

echo "<a href='./doc/{$filename}'  download>檔案已匯出，請點此連結下載</a>";
}


?>

<style>
    table{
        border-collapse: collapse;
    }
    td{
        border:1px solid #666;
        padding:5px 12px;
    }
    th{
        border:1px solid #666;
        padding:5px 12px;
        background-color: black;
        color:white;
    }
</style>
<script src="./jquery-3.4.1.min.js"></script>

<form action="?" method="post">
    <input type="submit" value="匯出選擇的資料">
<table>
    <tr>
        <th>
            <input type="checkbox" name="" id="select">
            勾選</th>
        <th>投票所編號</th>
        <th>投票所</th>
        <th>候選人1</th>
        <th>候選人1票數</th>
        <th>候選人2</th>
        <th>候選人2票數</th>
        <th>候選人3</th>
        <th>候選人3票數</th>
        <th>有效票數</th>
        <th>無效票數</th>
        <th>投票數</th>
        <th>已領未投票數</th>
        <th>發出票數</th>
        <th>用餘票數</th>
        <th>選舉人數</th>
        <th>投票率</th>
    </tr>
<?php
$rows=all('20200706');
foreach($rows as $row){
    echo "<tr>";
    echo "<td>";
    echo "<input type='checkbox' name='select[]' value='{$row['投票所編號']}'>";
    echo "</td>";
    foreach($row as $value){
        echo "<td>";
        echo $value;
        echo "</td>";
    }
    echo "</tr>";
}


?>
</table>
</form>


<script>
$("#select").on("change",function(){
    if($(this).prop('checked')){
        $("input[name='select[]']").prop('checked',true);
    }else{
        $("input[name='select[]']").prop('checked',false);
    }
})
</script>