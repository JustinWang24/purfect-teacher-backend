<?php
$resunt = [
	array('companyName'=>'新疆新业能源化工有限责任公司','companyCode'=>'263540','systemName'=>'氨压机','systemCode'=>'1001','createTime'=>'2019-10-16 10:11:13'),
	array('companyName'=>'新疆新业能源化工有限责任公司','companyCode'=>'263540','systemName'=>'合成与净化SIS','systemCode'=>'1002','createTime'=>'2019-10-16 10:11:13'),
	array('companyName'=>'重庆万盛煤焦化燃气有限公司','companyCode'=>'722287','systemName'=>'丙烯装置SIS','systemCode'=>'2001','createTime'=>'2019-10-16 10:11:13'),
	array('companyName'=>'重庆万盛煤焦化燃气有限公司','companyCode'=>'722287','systemName'=>'空分装置ITCC','systemCode'=>'2002','createTime'=>'2019-10-16 10:11:13'),
];
//echo json_encode($resunt);


$resunt = [
	array(
	'companyCode'=>'263540',
	'systemCode'=>'1001',
	'class_value'=>'ETSX', // 类型
	'error_code'=>'230', // 错误码
	'leg'=>'A', // leg
	'board_code'=>'1.MPB', // 板卡编号
	'board_location'=>'', // 板卡位置
	'severity_level'=>'3=information', // 报警级别
	'message_describe'=>'Network Clock Increment changed from 11081 to 21081', // 日志描述
	'logTime'=>'2019-10-16 10:11:13',), // 日志添加时间
	array(
	'companyCode'=>'263540',
	'systemCode'=>'1001',
	'class_value'=>'ETSX', // 类型
	'error_code'=>'230', // 错误码
	'leg'=>'A', // leg
	'board_code'=>'1.MPB', // 板卡编号
	'board_location'=>'', // 板卡位置
	'severity_level'=>'3=information', // 报警级别
	'message_describe'=>'Network Clock Increment changed from 11081 to 21081', // 日志描述
	'logTime'=>'2019-10-16 10:11:13',), // 日志添加时间
];
echo json_encode($resunt);

//'logTime'=>'2019-10-16 10:11:13' 