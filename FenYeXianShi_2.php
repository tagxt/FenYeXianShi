<?php
/**
 * Motto: Live and learn!
 * Created by Sublime Text! .
 * Author: 葛绪涛   Nickname: wordGe   QQ:690815818
 * Date: 2018/10/8 * Time: 20:14
 * Filename: FenYeXianShi_2.php
 */
// 建立数据库连接

$link = mysqli_connect("localhost", "root", "GE@126.com", "test")
or die("Could not connect: " . mysqli_error());
// 获取当前页数
if (isset($_GET['page'])) {
    $page = intval($_GET['page']);
} else {
    $page = 1;
}
// 每页数量,每页显示的记录数
$page_size = 10;

// 获取总数据量
$sql = "select count(*) as 'amount' from user";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
$amount = $row['amount'];

// 记算总共有多少页
if ($amount) {
    if ($amount < $page_size) {
        $page_count = 1;
    }        //如果总数据量小于$PageSize，那么只有一页
    if ($amount % $page_size) {                 //取总数据量除以每页数的余数
        $page_count = (int)($amount / $page_size) + 1;      //如果有余数，则页数等于总数据量除以每页数的结果取整再加一
    } else {
        $page_count = $amount / $page_size;           //如果没有余数，则页数等于总数据量除以每页数的结果
    }
} else {
    $page_count = 0;
}

// 翻页链接
$page_string = '';
if ($page == 1) {
    $page_string .= '第一页|上一页|';
} else {
    $page_string .= '第一页|' . ($page - 1) . ' >> 上一页 | ';
}

if (($page == $page_count) || ($page_count == 0)) {
    $page_string = $page_string . "下一页|尾页";
} else {
    $page_string .=''. ($page + 1) . ' >> 下一页 |'.$page_count . ' > 尾页';
}

// 获取数据，以二维数组格式返回结果
if ($amount) {
    $sql = "select * from user order by id desc limit " . ($page - 1) * $page_size . ", ".$page_size;
    $result = mysqli_query($link,$sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $rowset[] = $row;
    }
} else {
    $rowset = array();
}

// 没有包含显示结果的代码，那不在讨论范围，只要用foreach就可以很简单的用得到的二维数组来显示结果


