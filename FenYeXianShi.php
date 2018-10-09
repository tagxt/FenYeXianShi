<?php
/**
 * Motto: Live and learn!
 * Created by Sublime Text! .
 * Author: 葛绪涛   Nickname: wordGe   QQ:690815818
 * Date: 2018/10/9 * Time: 21:08
 * Filename: FenYeXianShi.php
 */
header("content-type:text/html;charset=utf-8");
//禁用错误报告
//error_reporting(0);
//报告运行时错误
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
//报告所有错误
error_reporting(E_ALL);
//连接数据库
$conn = mysqli_connect("localhost", "root", "GE@126.com", "test") or die("数据库连接错误！");
date_default_timezone_set("PRC");
mysqli_query($conn, "set names utf8");

$sql = "SELECT count(*) as 'count' from user";//查询记录的sql语句
$result = mysqli_query($conn, $sql);
$arr = mysqli_fetch_array($result);
$count = $arr['count'];
//echo $count;
//计算机出所有的记录数
$totalNumber = $count;
//每页的记录数 默认是
$pageSize = 2;

//防止SQL注入
$ruler = '/\'|\#|\\|"|(|)|\?|\*|\.|,|>|<|\/|;/';
//重新进行赋值
if (isset($_GET['size'])){
    $pageSize = $_GET['size'];
    $pageSize = preg_replace($ruler, "", $pageSize);
//    处理每页中的记录数如果大于 最大记录数，按每页显示最大记录数
    if ($pageSize > $totalNumber) {
        $pageSize = $totalNumber;
    }
}

//计算出分页的数目
$pageNumber = ceil($totalNumber / $pageSize);
//当前的开始记录数
$currentPage = 1;
if (isset($_GET['page'])){
    $currentPage = $_GET['page'];
    $currentPage = preg_replace($ruler, "", $currentPage);
}

$prePage = $currentPage - 1;
if ($prePage <= 0)
    $prePage = 1;

$nextPage = $currentPage + 1;
if ($nextPage >= $pageNumber) {
    $nextPage = $pageNumber;
}
//echo $nextPage . '<br>';
//echo $prePage . '<br>';
//echo $totalNumber;
$start = ($currentPage - 1) * $pageSize;//起始位置
if($start>=$totalNumber){
//    处理页数大于实际的页数，从0开始，每页显示 pageSize 条记录
    $start=0;
    $sql = "select * from user limit {$start},{$pageSize} ";
}else{
//    每页记录数大于全部记录，直接显示全部记录
    if ($pageSize >= $totalNumber) {
        $sql = "select * from user ";
    }else{
        $sql = "select * from user limit {$start},{$pageSize}";
    }
}
if (isset($_GET['last'])){
    $lastPage = $_GET['last'];
//处理尾页的情况
    $start=$totalNumber-$pageSize;
    $sql = "select * from user limit {$start},{$pageSize}";
}
$queryResult = mysqli_query($conn, $sql);


unset($result);
while ($thread = mysqli_fetch_assoc($queryResult)) {
    $result[] = $thread;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>分页显示记录</title>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $("#btnNext").click(function () {
                // alert("OK=btnNext");
                var pageSize = $("#pageSize").val();
                var nextPage = $("#iptNextPage").val();
                // alert(nextPage + '--' + pageSize);
                $("#btnNext").attr('href', "FenYeXianShi.php?page=" + nextPage + "&size=" + pageSize);
            })
            $("#btnPre").click(function () {
                // alert("OK=btnNext");
                var pageSize = $("#pageSize").val();
                var nextPage = $("#iptPrePage").val();
                // alert(nextPage + '--' + pageSize);
                $("#btnPre").attr('href', "FenYeXianShi.php?page=" + nextPage + "&size=" + pageSize);
            })
            $("#btnCurrent").click(function () {
                // alert("OK=btnNext");
                var pageSize = $("#pageSize").val();
                var nextPage = $("#iptCurrentPage").val();
                // alert(nextPage + '--' + pageSize);
                $("#btnCurrent").attr('href', "FenYeXianShi.php?page=" + nextPage + "&size=" + pageSize);
            })

            $("#btnFirst").click(function () {
                // alert("OK=btnNext");
                var pageSize = $("#pageSize").val();
                var nextPage =1;
                // alert(nextPage + '--' + pageSize);
                $("#btnFirst").attr('href', "FenYeXianShi.php?page=" + nextPage + "&size=" + pageSize);
            })
            $("#btnLast").click(function () {
                // alert("OK=btnNext");
                var pageSize = $("#pageSize").val();
                var nextPage = $("#iptLastPage").val();
                // alert(nextPage + '--' + pageSize);
                $("#btnLast").attr('href', "FenYeXianShi.php?last=1&page=" + nextPage + "&size=" + pageSize);
            })
        })
    </script>
</head>
<body>

</body>
</html>
<form name="form1" id="form1" method="post" action="FenYeXianShi.php">
    <label for="pageSize">每页的记录数：</label>
    <!--    <input type="text" id="pageSize" value="5">-->
    <select id="pageSize">
        <?php
        if (isset($_GET['size'])){
           echo "<option value='".$pageSize."'>".$pageSize."</option>";
        }
         for ($i=1;$i<=32;$i=$i+2){
             echo "<option value='".$i."'>".$i."</option>";
         }

        ?>

    </select>
    <table border="1">
        <tr>
            <td>id</td>
            <td>name</td>
            <td>userid</td>
            <td>password</td>
        </tr>
        <?php
        foreach ($result as $item) {
            echo '<tr>';
            echo '<td>' . $item['ID'] . '</td>';
            echo '<td>' . $item['name'] . '</td>';
            echo '<td>' . $item['userid'] . '</td>';
            echo '<td>' . $item['password'] . '</td>';
            echo '<tr>';
        }
        ?>
    </table>

    <a href="<?php echo $_SERVER['PHP_SELF'] . '?size=2&page=1' ?>">首页</a> &nbsp;
    <a href="<?php echo $_SERVER['PHP_SELF'] . '?size=2&page=' . $prePage; ?>">上一页</a>&nbsp;
    <?php echo '当前是第-' . $currentPage . '-页' ?>&nbsp;

    <a href="<?php echo $_SERVER['PHP_SELF'] . '?size=2&page=' . $nextPage; ?>">下一页</a>&nbsp;
    <a href="<?php echo $_SERVER['PHP_SELF'] . '?size=2&page=' . $pageNumber; ?>">尾页</a>&nbsp;
    <?php echo '共' . $pageNumber . '页，' . $totalNumber . '条记录' ?>
    <br>
    <hr>


    <a href="" id="btnFirst">首页</a>&nbsp; &nbsp;
    <!--    以下是调试时使用  真正在用 改为 hidden -->
    <input type="hidden" id="iptPrePage" size="1" value="<?php echo $prePage; ?>">
    <a href="" id="btnPre">上一页</a>&nbsp;
    当前是第-<?php echo $currentPage; ?>-页&nbsp;&nbsp;
    <a href="" id="btnCurrent">
    跳转
    </a>
    到第 <input type="text" id="iptCurrentPage" size="3" value="<?php echo $currentPage; ?>">页
        &nbsp;
    <!--    以下是调试时使用  真正在用 改为 hidden -->
    <input type="hidden" id="iptNextPage" size="1" value="<?php echo $nextPage; ?>">
    <a href="" id="btnNext">下一页</a>&nbsp;
    <!--    以下是调试时使用  真正在用 改为 hidden -->
    <input type="hidden" id="iptLastPage" size="3" value="<?php echo $totalNumber; ?>">
    <a href="" id="btnLast">尾页</a>&nbsp;
    <?php echo '共' . $pageNumber . '页，' . $totalNumber . '条记录' ?>

</form>