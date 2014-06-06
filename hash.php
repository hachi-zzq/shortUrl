<?php
//id 自增序列/自增编号 
//url 目标链接 
//*suffix* 短网址后缀 （并不需要存储在数据库内）

/*
 *

+------------+-----------------------+---------+ 
|id          | url                   | *suffix*| 
+------------+-----------------------+---------+ 
|123456      | http://zoeey.com/     | w7e     | 
+------------+-----------------------+---------+ 
|123457      | http://www.zoeey.com/ | w7f     | 
+------------+-----------------------+---------+ 
|56800235582 | http://zoeey.org/     | ZZZZZY  | 
+------------+-----------------------+---------+ 
|56800235583 | http://www.zoeey.org/ | ZZZZZZ  | 
+------------+-----------------------+---------+ 

短网址使用流程：
提交网址存储后获取其编号 如：123456
用dec2Any将编号转换为62进制，并拼接网址 如：http://go.to/w7e
用户访问到 http://go.to/w7e 时，提取短网址后缀 w7e
用any2Dec将短网址后缀转换为10进制，得到链接编号 如：123456
使用编号查询链接，并进行跳转[/list]


下面是进制转换所需要的源码：
 */
/* 
* MoXie (SysTem128@GMail.Com) 2010-6-30 17:53:57 
*  
* Copyright &copy; 2008-2010 Zoeey.Org . All rights are reserved. 
* Code license: Apache License  Version 2.0 
* http://www.apache.org/licenses/LICENSE-2.0.txt 
*/ 
error_reporting(E_ALL); 

/**
 * 返回一字符串，十进制 number 以 radix 进制的表示。
 * @param dec       需要转换的数字
 * @param toRadix    输出进制。当不在转换范围内时，此参数会被设定为 2，以便及时发现。
 * @return    指定输出进制的数字
 */
function dec2Any($dec, $toRadix) {
    $MIN_RADIX = 2;
    $MAX_RADIX = 62;
    $num62 = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if ($toRadix < $MIN_RADIX || $toRadix > $MAX_RADIX) {
        $toRadix = 2;
    }
    if ($toRadix == 10) {
        return $dec;
    }
    // -Long.MIN_VALUE 转换为 2 进制时长度为65 
    $buf = array();
    $charPos = 64;
    $isNegative = $dec < 0; //(bccomp($dec, 0) < 0); 
    if (!$isNegative) {
        $dec = -$dec; // bcsub(0, $dec); 
    }

    while (bccomp($dec, -$toRadix) <= 0) {
        $buf[$charPos--] = $num62[-bcmod($dec, $toRadix)];
        $dec = bcdiv($dec, $toRadix);
    }
    $buf[$charPos] = $num62[-$dec];
    if ($isNegative) {
        $buf[--$charPos] = '-';
    }
    $_any = '';
    for ($i = $charPos; $i < 65; $i++) {
        $_any .= $buf[$i];
    }
    return $_any;
} 

/**
 * 返回一字符串，包含 number 以 10 进制的表示。<br />
 * fromBase 只能在 2 和 62 之间（包括 2 和 62）。
 * @param number    输入数字
 * @param fromRadix    输入进制
 * @return  十进制数字
 */
function any2Dec($number, $fromRadix) {
    $num62 = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $dec = 0;
    $digitValue = 0;
    $len = strlen($number) - 1;
    for ($t = 0; $t <= $len; $t++) {
        $digitValue = strpos($num62, $number[$t]);
        $dec = bcadd(bcmul($dec, $fromRadix), $digitValue);
    }
    return $dec;
} 


?>