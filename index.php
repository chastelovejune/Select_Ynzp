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
//收集云南招聘网的信息
session_start();
set_time_limit(0);
error_reporting(E_ALL^E_NOTICE);
echo "<form action='' method='post'>
   请输入要搜索的职业： <input type='text' name='query'  />
   <input type='submit'>
   </br>  </br>
</form>
";
//从云南招聘网收集信息
//$_SESSION['query']=$query;
if($_POST){
    $query=$_POST['query'];
    $_SESSION['query']=$query;
  }else{
      if(!empty($_SESSION['query'])){
          $query=$_SESSION['query'];
       }
    }
 $page=$_GET['page'];
 $path=parse_url($_SERVER['REQUEST_URI']);
echo   "你正在搜索的关键词:".$_SESSION['query']."</br></br>";
echo   "当前页:第".$_GET['page']."页</br></br>";
echo   "<a href=".$path['path']."?page=".($page-1).">上一页</a>&nbsp;&nbsp";
echo   "<a href=".$path['path']."?page=".($page+1).">下一页</a>";
collect($query,$page);




function collect($query="市场",$page=1){
    //  $url="http://m.ynzp.com/cms/personsearch.html?query=".$query."&region=530100";
 $url="http://m.ynzp.com/cms/personsearch.html?jobPosCat=&sex=0&jobExp=0&edu=0&refreshTime=0&region=530100&age_lower=0&age_upper=0&param=&query=".$query."&qtype=Q&rp=20&fullTime=0&regionIndex=0%2C0&disp=&sortname=UpdateTime&page=".$page;
      $data = QueryList::Query($url,array(
      	//'content' => array('.dw_table .el','html'),
          'href' => array('.plst a','href'),
          ))->getData(function($item){
            //拼接完整URL
            $item['href']="http://m.ynzp.com".$item['href'];
            //再次读取详细资料
            $data2=QueryList::Query($item['href'],array(
              'content'=>array('.box','html'),
              'title'=>array('.qy_zlxx_list_img a','title'),
              'img'=>array('.qy_zlxx_list_img a','href'),
              'phone'=>array('#btnTel2Person','href'),
              ))->getData(function($item2){
                $item2['imghref']="<img src=http://m.ynzp.com".$item2['img']." height=200;width:400>";
                   if($item2['imghref']=='<img src=http://m.ynzp.com height=200;width:400>'){
                  unset($item2['imghref']);
                }
              $item2['content']= preg_replace('/(.*)<img (.*)>(.*)/','',$item2['content']);
              $item2['content']= preg_replace('/(.*)<a (.*)>(.*)/','',$item2['content']);
              $item2['content']= preg_replace('/(.*)<div class="qy_zlxx_list" (.*)<\/div>(.*)/','',$item2['content']);
                echo "<center>".$item2['title']."</center>";
                echo "<center>".$item2['imghref']."</center>";
                echo "<center><h2 style='color:red;'>".$item2['phone']."</h2></center>";
                echo "<div style='padding:10px'>".$item2['content']."</div>";
                echo "<hr>";                
            });

          
                    
      });    
   
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
