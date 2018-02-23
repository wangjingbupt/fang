<?php

include_once("./curl.class.php");
error_reporting(1);


$ch = new Curl();

$date =date("Ymd");

$id = '106100597929';

$url = "https://cq.lianjia.com/ershoufang/{$id}.html";


//$ret = file_get_contents('./1');
$ret = $ch->get($url);
preg_match('/class="price "><span class="total">([0-9\.]+)<\/span><span class="unit"><span>(.*?)<\/span.*?<span class="unitPriceValue">([0-9]+)<i>(.*?)<\/i>.*?<div class="mainInfo">.*?<div class="mainInfo">.*?<div class="subInfo">([0-9]+).*?\/.*?小区名称<\/span><a.*?>(.*?)<\/a>.*?所在区域<\/span><span.*?><a.*?>(.*?)<\/a>.*?<a.*?>(.*?)<\/a>.*?<div class="name">基本属性<\/div>(.*?)<\/div>.*?<div class="name">交易属性<\/div>(.*?)<\/div>/s',$ret,$m);
if($m)
{
	$fang['priceAll'] = $m[1];
	$fang['price'] = $m[3];
        $fang['year'] = $m[5];	
	$fang['xiaoqu'] =$m[6];
	$fang['qu'] =$m[7];
	$fang['jiedao'] =$m[8];
        preg_match_all('/<li><span.*?>(.*?)<\/span>(.*?)</s',$m[9],$m2);
	foreach($m2[1] as $k =>$v)
	{
		if($v == '房屋户型')
		{
			preg_match('/([0-9]+)室/',$m2[2][$k],$ma);
			if($ma)
			{
				$fang['shi'] = $ma[1];
			}
			preg_match('/([0-9]+)厅/',$m2[2][$k],$ma);
			if($ma)
			{
				$fang['ting'] = $ma[1];
			}
			preg_match('/([0-9]+)厨/',$m2[2][$k],$ma);
			if($ma)
			{
				$fang['chu'] = $ma[1];
			}
			preg_match('/([0-9]+)卫/',$m2[2][$k],$ma);
			if($ma)
			{
				$fang['wei'] = $ma[1];
			}
		}
		else if($v=='所在楼层')
		{
			preg_match('/(.*?)\(.*?([0-9]+).*?\)/',$m2[2][$k],$ma);
			if($ma)
			{
				$fang['ceng'] = $ma[1];
				$fang['cengAll']= $ma[2];
			}
		}
		else if($v=='建筑面积')
		{
			preg_match('/([0-9\.]+)/',$m2[2][$k],$ma);
			if($ma)
			{
				$fang['jianmian'] = $ma[1];
			}
			
						
		}
		else if($v=='户型结构')
		{
			$fang['jiegou'] = $m2[2][$k];
		}
		else if($v=='套内面积')
		{
			preg_match('/([0-9\.]+)/',$m2[2][$k],$ma);
			if($ma)
			{
				$fang['taonei'] = $ma[1];
			}
		}
		else if($v=='建筑类型')
		{
			$fang['jianzhu'] = $m2[2][$k];
		}
		else if($v=='房屋朝向')
		{
			$fang['chaoxiang'] = $m2[2][$k];
		}
		else if($v=='装修情况')
		{
			$fang['zhuangxiu'] = $m2[2][$k];
		}
		else if($v=='梯户比例')
		{
			$fang['tihu'] = $m2[2][$k];
		}
		else if($v=='产权年限')
		{
			preg_match('/([0-9]+)/',$m2[2][$k],$ma);
			if($ma)
			{
				$fang['chanquan'] =$ma[1];
			}
		}

	}
        preg_match_all('/<li>.*?<span.*?>(.*?)<\/span>.*?<span>(.*?)</s',$m[10],$m3);
	foreach($m3[1] as $k =>$v)
	{
		if($v == '挂牌时间')
		{
			$fang['gtime'] =$m3[2][$k]; 
		}
		else if ($v=='交易权属')
		{
			$fang['quanshu']  =$m3[2][$k];
		}
		else if ($v=='房屋用途')
		{
			$fang['yongtu'] = $m3[2][$k];
		}
		else if ($v=='房屋年限')
		{
			$fang['nianxian'] = $m3[2][$k];
		}
	}
	$daikanUrl = "https://cq.lianjia.com/ershoufang/houseseerecord?id={$id}";
	$ret = $ch->get($daikanUrl);
	$daikan = json_decode($ret,true);
	$fang['totalCnt'] = $daikan['data']['totalCnt'];
	$fang['thisWeek'] = $daikan['data']['thisWeek'];
	$seeRecord = array();
	foreach($daikan['data']['seeRecord'] as $v)
	{
		$seeRecord[$v['seeTime']] +=1;
	}
	$fang['seeRecord'] = $seeRecord;

	
        print_r($fang);exit();
}
