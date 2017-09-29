<?php
/**
 *  QueryList使用示例
 *  
 * 入门教程:http://doc.querylist.cc/site/index/doc/4
 * 
 * QueryList::Query(采集的目标页面,采集规则[,区域选择器][，输出编码][，输入编码][，是否移除头部])
* //采集规则
* $rules = array(
*   '规则名' => array('jQuery选择器','要采集的属性'[,"标签过滤列表"][,"回调函数"]),
*   '规则名2' => array('jQuery选择器','要采集的属性'[,"标签过滤列表"][,"回调函数"]),
*    ..........
*    [,"callback"=>"全局回调函数"]
* );
 */
require 'vendor/autoload.php';

use QL\QueryList;
//采集某页面所有的图片\
session_start();
$p=6;
$arr=array();
set_time_limit(0);
for($i=1;$i<=$p;$i++){
  collect($i);
}
function collect($p){
      $url="http://search.51job.com/list/250200,000000,0000,00,9,99,PHP,2,".$p.".html?lang=c&stype=1&postchannel=0000&workyear=99&cotype=99&degreefrom=99&jobterm=99&companysize=99&lonlat=0%2C0&radius=-1&ord_field=0&confirmdate=9&fromType=1&dibiaoid=0&address=&line=&specialarea=00&from=&welfare=";
      $data = QueryList::Query($url,array(
      	//'content' => array('.dw_table .el','html'),
          'zhiyan' => array('.dw_table .t1','text'),
            'company' => array('.dw_table .t2','text'),
              'didian' => array('.dw_table .t3','text'),
                'xinzi' => array('.dw_table .t4','text'),
                'time' => array('.dw_table .t5','text')

          ))->getData(function($item){
              $item['zhiyan']="<td>".$item['zhiyan']."</td>";
               $item['company']="<td>".$item['company']."</td>";
                $item['didian']="<td>".$item['didian']."</td>";
                 $item['xinzi']="<td>".$item['xinzi']."</td>";
                  $item['time']="<td>".$item['time']."</td>";
                  $arr="<tr>". $item['zhiyan']. $item['company'].$item['didian'].$item['xinzi']. $item['time']."</tr>";
                   return $arr;
      });
      $msg='';
      foreach ($data as $k => $v) {
        $msg=$msg.$v;
      }
      $res="<table>".$msg."</table>";
      if(file_put_contents('./php.xls',$res,FILE_APPEND)){
        echo "ok";
      }
}
    //拼接表格
   // $data
//打印结果
//$data[0]['info']='<table>'.$data[0]['info'].'</table>';
//$data[0]['biaoti']='<h1>'.$data[0]['biaoti'].'</h1>';
//$href='<a href="http://www.qtbclub.com/'.$data[0]['href'].'"target=_blank>download</a>';
//$res=$data[0]['biaoti'].$data[0]['info'].$href;
//if($data[0]['href']!="")
//if(file_put_contents('./caiji.html',$res,FILE_APPEND)){
	//echo "ok";
//}
//}
/*************************************************/

//使用插件
/*
$urls = QueryList::run('Request',array(
        'target' => 'http://cms.querylist.cc/news/list_2.html',
        'referrer'=>'http://cms.querylist.cc',
        'method' => 'GET',
        'params' => ['var1' => 'testvalue', 'var2' => 'somevalue'],
        'user_agent'=>'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:21.0) Gecko/20100101 Firefox/21.0',
        'cookiePath' => './cookie.txt',
        'timeout' =>'30'
    ))->setQuery(array('link' => array('h2>a','href','',function($content){
    //利用回调函数补全相对链接
    $baseUrl = 'http://cms.querylist.cc';
    return $baseUrl.$content;
})),'.cate_list li')->getData(function($item){
    return $item['link'];
});

print_r($urls);*/
