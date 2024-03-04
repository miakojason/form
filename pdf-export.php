<?php
require_once 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

include "db_export.php";

$options = new Options();
$options->set('defaultFont', './fonts/DFBangShuStd-W8.otf'); // 設置默認字體，可以根據需要更改
$dompdf = new Dompdf($options);


if (!empty($_POST)) {
    $rows = all("20200706", " where  `投票所編號` in ('" . join("','", $_POST['select']) . "')");

    //$filename=date("Ymd").rand(100000000,999999999);
    $filename = date("Ymd") . rand(100000000, 999999999) . ".pdf";
    //$file=fopen("./doc/{$filename}.csv",'w+');
    //fwrite($file, "\xEF\xBB\xBF");
    $html = "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Document</title>
</head>
<body>";

    $html .= "<table>";
    $chk = false;
    foreach ($rows as $row) {
        if (!$chk) {
            $cols = array_keys($row);
            $html .= "<tr>";
            foreach ($cols as $col) {
                $html .= "<td>";
                $html .= $col;
                $html .= "</td>";
            }
            $html .= "</tr>";
            $chk = true;
        }

        //fwrite($file,join(",",$row)."\r\n");
        $html .= "<tr>";
        foreach ($row as $r) {
            $html .= "<td>";
            $html .= $r;
            $html .= "</td>";
        }
        $html .= "</tr>";
    }

    $html .= "</table></body>
</html>";


    // 將 HTML 載入 Dompdf
    $dompdf->loadHtml($html);

    // 渲染 PDF（可選）
    $dompdf->render();

    // 將 PDF 輸出到文件或直接輸出到瀏覽器
    $dompdf->stream("./doc/{$filename}", array('Attachment' => 0));
    //fclose($file);

    echo "<a href='./doc/{$filename}'  download>檔案已匯出，請點此連結下載</a>";
}


?>

<style>
    table {
        border-collapse: collapse;
    }

    td {
        border: 1px solid #666;
        padding: 5px 12px;
    }

    th {
        border: 1px solid #666;
        padding: 5px 12px;
        background-color: black;
        color: white;
    }
</style>
<script src="./jquery-3.4.1.min.js"></script>
<form action="?" method="post">
    <input type="submit" value="匯出選擇的資料">
    <table>
        <tr>
            <th>
                <input type="checkbox" name="" id="select">
                勾選
            </th>
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
        $rows = all('20200706');
        foreach ($rows as $row) {
            echo "<tr>";
            echo "<td>";
            echo "<input type='checkbox' name='select[]' value='{$row['投票所編號']}'>";
            echo "</td>";
            foreach ($row as $value) {
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
    $("#select").on("change", function() {
        if ($(this).prop('checked')) {
            $("input[name='select[]']").prop('checked', true);
        } else {
            $("input[name='select[]']").prop('checked', false);
        }
    })
</script>