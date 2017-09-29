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
set_time_limit(0);
collect();
function collect(){      
  $url="http://mp.weixin.qq.com/s?__biz=MzAwNTEwMzYwMA==&mid=2651317804&idx=1&sn=1a58110731f6d649345aec5777172d0b&chksm=80d28cb3b7a505a59a8ea422f336cad493e5c7efd79ba7ac90e7902785dcca5b28e41d9fe771&mpshare=1&scene=23&srcid=0510LOvHCEcOIYSsuHA6ksTL#rd";
      $data = QueryList::Query($url,array(
      	//'content' => array('.dw_table .el','html'),
          'img' => array('img','data-src'),

          ))->getData(function($item){
        //实现批量图片下在
        echo $item['img']."</br>";
       // dlfile($item['img'],time().'.jpeg');
          
      });
   
    
     
}
/*function dlfile($file_url, $save_to)
{
  $content = file_get_contents($file_url);
  file_put_contents($save_to, $content);
}*/

function dlfile($file_url, $save_to)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_POST, 0); 
  curl_setopt($ch,CURLOPT_URL,$file_url); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  $file_content = curl_exec($ch);
  curl_close($ch);
  $downloaded_file = fopen($save_to, 'w');
  fwrite($downloaded_file, $file_content);
  fclose($downloaded_file);
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
