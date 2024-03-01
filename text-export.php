<?php
include "./db_export.php";
if (!empty($_POST)) {
    $rows = all("20200706", " where `投票所編號` in ('" . join("','", $_POST['select']) . "')");

    $filename = date("Ymd") . rand(100000000, 999999999);
    $file = fopen("./doc/{$filename}.csv", 'w+');
    fwrite($file, "\xEF\xBB\xBF");
    $chk = false;
    foreach ($rows as $row) {
        if (!$chk) {
            $cols = array_keys($row);
            fwrite($file, join(",", $cols) . "\r\n");
            $chk = true;
        }
        fwrite($file, join(",", $row) . "\r\n");
    }
    fclose($file);

    echo "<a href='./doc/{$filename}.csv'  download>檔案已匯出，請點此連結下載</a>";
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
            <th>勾選<input type="checkbox" name="select[]" id="select"></th>
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
    <script>
        $("#select").on("change", function() {
            if ($(this).prop('checked')) {
                $("input[name='select[]']").prop('checked', true);
            } else {
                $("input[name='select[]']").prop('checked', false);
            }
        })
    </script>