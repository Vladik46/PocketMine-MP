<?php

require 'sys/sid.php';
require 'sys/config.php';
include 'sys/user.php';
include 'sys/head.php';

visit(0, 'build');

$do = isset($_GET['do']) ? $_GET['do'] : NULL;
switch($do)
{
default:
if (isset($_GET['id']))
{
	$id = number($_GET['id']);
	$double = mysql_query("SELECT id FROM construct WHERE user = '$user[id]' AND cid = '$id' LIMIT 1");
	$myb = mysql_query("SELECT id FROM construct WHERE user = '$user[id]' AND timeleft > '$rtime' LIMIT 1");
	if (mysql_num_rows($myb) != false)
	{
		err('Одновременно можно строить только одно здание!');
	}
	elseif ($id < 1 || $id > 14 || mysql_num_rows($double) > 0)
	{
		err('Здание не найдено на карте!');
	}
	else
	{
		$non = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
		if ($id > 5 && !isset($non['id']) == 1 && !isset($non['id']) == 2 && !isset($non['id']) == 3 && !isset($non['id']) == 4 && !isset($non['id']) == 5)
		{
			err('Для того, чтобы строить инфраструктурные здания, необходимо сначала построить ресурсные!');
			include 'sys/foot.php';
			exit();
		}
		if ($id == 10 && !isset($non['id']) == 6)
		{
			err('Для постройки Кузницы необходима Казарма!');
			include 'sys/foot.php';
			exit();
		}
		if ($id == 7 && !isset($non['id']) == 9)
		{
			err('Для постройки Торговой Палаты необходим Рынок!');
			include 'sys/foot.php';
			exit();
		}
		if ($id == 13 && !isset($non['id']) == 12)
		{
			err('Для постройки Бастиона необходима Цитадель!');
			include 'sys/foot.php';
			exit();
		}
		if ($id == 14 && !isset($non['id']) == 6)
		{
			err('Для постройки Караульной башни необходима Казарма!');
			include 'sys/foot.php';
			exit();
		}
		switch($id):
			case 1:
				$tleft = 60;
				$sql = "UPDATE users SET time_later = '$rtime', tree = tree - '100', gold = gold - '200' WHERE id = '$user[id]' LIMIT 1"; 
			break;
			case 2:
				$tleft = 120;
				$sql = "UPDATE users SET time_later = '$rtime', tree = tree - '200', gold = gold - '250' WHERE id = '$user[id]' LIMIT 1"; 
			break;
			case 3:
				$tleft = 120;
				$sql = "UPDATE users SET time_later = '$rtime', stone = stone - '200', ore = ore - '250' WHERE id = '$user[id]' LIMIT 1"; 
			break;
			case 4:
				$tleft = 120;
				$sql = "UPDATE users SET time_later = '$rtime', food = food - '400', tree = tree - '250', ore = ore - '400' WHERE id = '$user[id]' LIMIT 1";
			break;
			case 5:
				$tleft = 120;
				$sql = "UPDATE users SET time_later = '$rtime', tree = tree - '200', stone = stone - '300', ore = ore - '150' WHERE id = '$user[id]' LIMIT 1";
			break;
			case 6:
				$tleft = 120;
				$sql = "UPDATE users SET time_later = '$rtime', tree = tree - '200', stone = stone - '300', ore = ore - '200', gold = gold - '150' WHERE id = '$user[id]' LIMIT 1";
			break;
			case 7:
				$tleft = 600;
				$sql = "UPDATE users SET time_later = '$rtime', stone = stone - '400', gold = gold - '200' WHERE id = '$user[id]' LIMIT 1";
			break;
			case 8:
				$tleft = 480;
				$sql = "UPDATE users SET time_later = '$rtime', stone = stone - '200', ore = ore - '200' WHERE id = '$user[id]' LIMIT 1";
			break;
			case 9:
				$tleft = 300;
				$sql = "UPDATE users SET time_later = '$rtime', tree = tree - '150', gold = gold - '200' WHERE id = '$user[id]' LIMIT 1";
			break;
			case 10:
				$tleft = 480;
				$sql = "UPDATE users SET time_later = '$rtime', ore = ore - '200', gold = gold - '250' WHERE id = '$user[id]' LIMIT 1";
			break;
			case 11:
				$tleft = 360;
				$sql = "UPDATE users SET time_later = '$rtime', tree = tree - '400', stone = stone - '450', gold = gold - '300' WHERE id = '$user[id]' LIMIT 1";
			break;
			case 12:
				$tleft = 320;
				$sql = "UPDATE users SET time_later = '$rtime', tree = tree - '300', ore = ore - '450', gold = gold - '200' WHERE id = '$user[id]' LIMIT 1";
			break;
			case 13:
				$tleft = 360;
				$sql = "UPDATE users SET time_later = '$rtime', tree = tree - '350', stone = stone - '400', gold = gold - '300' WHERE id = '$user[id]' LIMIT 1";
			break;
			case 14:
				$tleft = 900;
				$sql = "UPDATE users SET time_later = '$rtime', tree = tree - '350', gold = gold - '300' WHERE id = '$user[id]' LIMIT 1";
			break;
		endswitch;
		##############################
		if ($id == 1)
		{
			if ($user['tree'] < 100) $short[] .= '<img src="/images/res.2.png" alt="" title="Дерево"/>' . (100 - $user['tree']);
			if ($user['gold'] < 200) $short[] .= '<img src="/images/res.5.png" alt="" title="Золото"/>' . (200 - $user['gold']);
			if (empty($short))
			{
				mysql_query($sql);
				mysql_query("INSERT INTO construct SET user = '$user[id]', cid = '$id', timeleft = '" . ($rtime + $tleft) . "', stamp = '$tleft', maxlvl = '10', uplvl = '2'");
				msg('Стройка началась.');
				header('Refresh: 1; URL=/');
			}
			else err('У тебя нехватает: ' . implode(', ', $short));
		}
		if ($id == 2)
		{
			if ($user['tree'] < 200) $short[] .= '<img src="/images/res.2.png" alt="" title="Дерево"/>' . (200 - $user['tree']);
			if ($user['gold'] < 250) $short[] .= '<img src="/images/res.5.png" alt="" title="Золото"/>' . (250 - $user['gold']);
			if (empty($short))
			{
				mysql_query($sql);
				mysql_query("INSERT INTO construct SET user = '$user[id]', cid = '$id', timeleft = '" . ($rtime + $tleft) . "', stamp = '$tleft', maxlvl = '10', uplvl = '2'");
				msg('Стройка началась.');
				header('Refresh: 1; URL=/');
			}
			else err('У тебя нехватает: ' . implode(', ', $short));
		}
		if ($id == 3)
		{
			if ($user['stone'] < 200) $short[] .= '<img src="/images/res.3.png" alt="" title="Камень"/>' . (200 - $user['stone']);
			if ($user['ore'] < 250) $short[] .= '<img src="/images/res.4.png" alt="" title="Руда"/>' . (250 - $user['ore']);
			if (empty($short))
			{
				mysql_query($sql);
				mysql_query("INSERT INTO construct SET user = '$user[id]', cid = '$id', timeleft = '" . ($rtime + $tleft) . "', stamp = '$tleft', maxlvl = '10', uplvl = '2'");
				msg('Стройка началась.');
				header('Refresh: 1; URL=/');
			}
			else err('У тебя нехватает: ' . implode(', ', $short));
		}
		if ($id == 4)
		{
			if ($user['food'] < 400) $short[] .= '<img src="/images/res.1.png" alt="" title="Еда"/>' . (400 - $user['food']);
			if ($user['tree'] < 250) $short[] .= '<img src="/images/res.2.png" alt="" title="Дерево"/>' . (250 - $user['tree']);
			if ($user['ore'] < 400) $short[] .= '<img src="/images/res.4.png" alt="" title="Руда"/>' . (400 - $user['ore']);
			if (empty($short))
			{
				mysql_query($sql);
				mysql_query("INSERT INTO construct SET user = '$user[id]', cid = '$id', timeleft = '" . ($rtime + $tleft) . "', stamp = '$tleft', maxlvl = '10', uplvl = '2'");
				msg('Стройка началась.');
				header('Refresh: 1; URL=/');
			}
			else err('У тебя нехватает: ' . implode(', ', $short));
		}
		if ($id == 5)
		{
			if ($user['tree'] < 200) $short[] .= '<img src="/images/res.2.png" alt="" title="Дерево"/>' . (200 - $user['tree']);
			if ($user['stone'] < 300) $short[] .= '<img src="/images/res.3.png" alt="" title="Камень"/>' . (300 - $user['stone']);
			if ($user['ore'] < 150) $short[] .= '<img src="/images/res.4.png" alt="" title="Руда"/>' . (150 - $user['ore']);
			if (empty($short))
			{
				mysql_query($sql);
				mysql_query("INSERT INTO construct SET user = '$user[id]', cid = '$id', timeleft = '" . ($rtime + $tleft) . "', stamp = '$tleft', maxlvl = '10', uplvl = '2'");
				msg('Стройка началась.');
				header('Refresh: 1; URL=/');
			}
			else err('У тебя нехватает: ' . implode(', ', $short));
		}
		if ($id == 6)
		{
			if ($user['tree'] < 200) $short[] .= '<img src="/images/res.2.png" alt="" title="Дерево"/>' . (200 - $user['tree']);
			if ($user['stone'] < 300) $short[] .= '<img src="/images/res.3.png" alt="" title="Камень"/>' . (300 - $user['stone']);
			if ($user['ore'] < 200) $short[] .= '<img src="/images/res.4.png" alt="" title="Руда"/>' . (200 - $user['ore']);
			if ($user['gold'] < 150) $short[] .= '<img src="/images/res.5.png" alt="" title="Золото"/>' . (150 - $user['gold']);
			if (empty($short))
			{
				mysql_query($sql);
				mysql_query("INSERT INTO construct SET user = '$user[id]', cid = '$id', timeleft = '" . ($rtime + $tleft) . "', stamp = '$tleft', maxlvl = '1', uplvl = '2'");
				msg('Стройка началась.');
				header('Refresh: 1; URL=/');
			}
			else err('У тебя нехватает: ' . implode(', ', $short));
		}
		if ($id == 7)
		{
			if ($user['stone'] < 400) $short[] .= '<img src="/images/res.3.png" alt="" title="Камень"/>' . (400 - $user['stone']);
			if ($user['gold'] < 200) $short[] .= '<img src="/images/res.5.png" alt="" title="Золото"/>' . (200 - $user['gold']);
			if (empty($short))
			{
				mysql_query($sql);
				mysql_query("INSERT INTO construct SET user = '$user[id]', cid = '$id', timeleft = '" . ($rtime + $tleft) . "', stamp = '$tleft', maxlvl = '10', uplvl = '2'");
				msg('Стройка началась.');
				header('Refresh: 1; URL=/');
			}
			else err('У тебя нехватает: ' . implode(', ', $short));
		}
		if ($id == 8)
		{
			if ($user['stone'] < 200) $short[] .= '<img src="/images/res.3.png" alt="" title="Камень"/>' . (200 - $user['stone']);
			if ($user['ore'] < 200) $short[] .= '<img src="/images/res.4.png" alt="" title="Руда"/>' . (200 - $user['ore']);
			if (empty($short))
			{
				mysql_query($sql);
				mysql_query("INSERT INTO construct SET user = '$user[id]', cid = '$id', timeleft = '" . ($rtime + $tleft) . "', stamp = '$tleft', maxlvl = '20', uplvl = '2'");
				msg('Стройка началась.');
				header('Refresh: 1; URL=/');
			}
			else err('У тебя нехватает: ' . implode(', ', $short));
		}
		if ($id == 9)
		{
			if ($user['tree'] < 150) $short[] .= '<img src="/images/res.2.png" alt="" title="Дерево"/>' . (150 - $user['tree']);
			if ($user['gold'] < 150) $short[] .= '<img src="/images/res.5.png" alt="" title="Золото"/>' . (150 - $user['gold']);
			if (empty($short))
			{
				mysql_query($sql);
				mysql_query("INSERT INTO construct SET user = '$user[id]', cid = '$id', timeleft = '" . ($rtime + $tleft) . "', stamp = '$tleft', maxlvl = '10', uplvl = '2'");
				msg('Стройка началась.');
				header('Refresh: 1; URL=/');
			}
			else err('У тебя нехватает: ' . implode(', ', $short));
		}
		if ($id == 10)
		{
			if ($user['ore'] < 200) $short[] .= '<img src="/images/res.4.png" alt="" title="Руда"/>' . (200 - $user['ore']);
			if ($user['gold'] < 250) $short[] .= '<img src="/images/res.5.png" alt="" title="Золото"/>' . (250 - $user['gold']);
			if (empty($short))
			{
				mysql_query($sql);
				mysql_query("INSERT INTO construct SET user = '$user[id]', cid = '$id', timeleft = '" . ($rtime + $tleft) . "', stamp = '$tleft', maxlvl = '1', uplvl = '2'");
				msg('Стройка началась.');
				header('Refresh: 1; URL=/');
			}
			else err('У тебя нехватает: ' . implode(', ', $short));
		}
		if ($id == 11)
		{
			if ($user['tree'] < 400) $short[] .= '<img src="/images/res.2.png" alt="" title="Дерево"/>' . (400 - $user['tree']);
			if ($user['stone'] < 450) $short[] .= '<img src="/images/res.3.png" alt="" title="Камень"/>' . (450 - $user['stone']);
			if ($user['gold'] < 300) $short[] .= '<img src="/images/res.5.png" alt="" title="Золото"/>' . (300 - $user['gold']);
			if (empty($short))
			{
				mysql_query($sql);
				mysql_query("INSERT INTO construct SET user = '$user[id]', cid = '$id', timeleft = '" . ($rtime + $tleft) . "', stamp = '$tleft', maxlvl = '1', uplvl = '2'");
				msg('Стройка началась.');
				header('Refresh: 1; URL=/');
			}
			else err('У тебя нехватает: ' . implode(', ', $short));
		}
		if ($id == 12)
		{
			if ($user['tree'] < 300) $short[] .= '<img src="/images/res.2.png" alt="" title="Дерево"/>' . (300 - $user['tree']);
			if ($user['ore'] < 450) $short[] .= '<img src="/images/res.4.png" alt="" title="Руда"/>' . (450 - $user['ore']);
			if ($user['gold'] < 200) $short[] .= '<img src="/images/res.5.png" alt="" title="Золото"/>' . (200 - $user['gold']);
			if (empty($short))
			{
				mysql_query($sql);
				mysql_query("INSERT INTO construct SET user = '$user[id]', cid = '$id', timeleft = '" . ($rtime + $tleft) . "', stamp = '$tleft', maxlvl = '1', uplvl = '2'");
				msg('Стройка началась.');
				header('Refresh: 1; URL=/');
			}
			else err('У тебя нехватает: ' . implode(', ', $short));
		}
		if ($id == 13)
		{
			if ($user['tree'] < 350) $short[] .= '<img src="/images/res.2.png" alt="" title="Дерево"/>' . (350 - $user['tree']);
			if ($user['stone'] < 400) $short[] .= '<img src="/images/res.3.png" alt="" title="Камень"/>' . (400 - $user['stone']);
			if ($user['gold'] < 300) $short[] .= '<img src="/images/res.5.png" alt="" title="Золото"/>' . (300 - $user['gold']);
			if (empty($short))
			{
				mysql_query($sql);
				mysql_query("INSERT INTO construct SET user = '$user[id]', cid = '$id', timeleft = '" . ($rtime + $tleft) . "', stamp = '$tleft', maxlvl = '1', uplvl = '2'");
				msg('Стройка началась.');
				header('Refresh: 1; URL=/');
			}
			else err('У тебя нехватает: ' . implode(', ', $short));
		}
		if ($id == 14)
		{
			if ($user['tree'] < 350) $short[] .= '<img src="/images/res.2.png" alt="" title="Дерево"/>' . (350 - $user['tree']);
			if ($user['gold'] < 300) $short[] .= '<img src="/images/res.5.png" alt="" title="Золото"/>' . (300 - $user['gold']);
			if (empty($short))
			{
				mysql_query($sql);
				mysql_query("INSERT INTO construct SET user = '$user[id]', cid = '$id', timeleft = '" . ($rtime + $tleft) . "', stamp = '$tleft', maxlvl = '1', uplvl = '2'");
				msg('Стройка началась.');
				header('Refresh: 1; URL=/');
			}
			else err('У тебя нехватает: ' . implode(', ', $short));
		}
		##############################
	}
}

$b1 = mysql_query("SELECT id FROM construct WHERE user = '$user[id]' AND cid = '1' LIMIT 1");
$b2 = mysql_query("SELECT id FROM construct WHERE user = '$user[id]' AND cid = '2' LIMIT 1");
$b3 = mysql_query("SELECT id FROM construct WHERE user = '$user[id]' AND cid = '3' LIMIT 1");
$b4 = mysql_query("SELECT id FROM construct WHERE user = '$user[id]' AND cid = '4' LIMIT 1");
$b5 = mysql_query("SELECT id FROM construct WHERE user = '$user[id]' AND cid = '5' LIMIT 1");
$b6 = mysql_query("SELECT id FROM construct WHERE user = '$user[id]' AND cid = '6' LIMIT 1");
$b7 = mysql_query("SELECT id FROM construct WHERE user = '$user[id]' AND cid = '7' LIMIT 1");
$b8 = mysql_query("SELECT id FROM construct WHERE user = '$user[id]' AND cid = '8' LIMIT 1");
$b9 = mysql_query("SELECT id FROM construct WHERE user = '$user[id]' AND cid = '9' LIMIT 1");
$b10 = mysql_query("SELECT id FROM construct WHERE user = '$user[id]' AND cid = '10' LIMIT 1");
$b11 = mysql_query("SELECT id FROM construct WHERE user = '$user[id]' AND cid = '11' LIMIT 1");
$b12 = mysql_query("SELECT id FROM construct WHERE user = '$user[id]' AND cid = '12' LIMIT 1");
$b13 = mysql_query("SELECT id FROM construct WHERE user = '$user[id]' AND cid = '13' LIMIT 1");
$b14 = mysql_query("SELECT id FROM construct WHERE user = '$user[id]' AND cid = '14' LIMIT 1");

if (mysql_num_rows($b1) == false)
{
echo '<img src="images/building_group_1.png" alt=""/> Ферма<br/>
	  Выращивает +300 еды в час<br/>
	  <img src="images/lvl.png" alt="" title="ур"/> Максимальный уровень: 10<br/>
	  Требуется: <img src="images/res.2.png" alt="" title="Дерево"/> 100
	  <img src="images/res.5.png" alt="" title="Золото"/> 200
	  <img src="images/time.png" alt="" title="Время"/> 1 мин.
	  <br/>- - -
	  <br/>
	  <a href="build1"><u>Построить</u></a>
	  ' . separator;
}

if (mysql_num_rows($b2) == false)
{
echo '<img src="images/building_group_2.png" alt=""/> Шахта<br/>
	  Добывает +300 руды в час<br/>
	  <img src="images/lvl.png" alt="" title="ур"/> Максимальный уровень: 10<br/>
	  Требуется: <img src="images/res.2.png" alt="" title="Дерево"/> 200
	  <img src="images/res.5.png" alt="" title="Золото"/> 250
	  <img src="images/time.png" alt="" title="Время"/> 2 мин.
	  <br/>- - -
	  <br/>
	  <a href="build2"><u>Построить</u></a>
	  ' . separator;
}

if (mysql_num_rows($b3) == false)
{
echo '<img src="images/building_group_3.png" alt=""/> Лесопилка<br/>
	  Вырубает +300 древесины в час<br/>
	  <img src="images/lvl.png" alt="" title="ур"/> Максимальный уровень: 10<br/>
	  Требуется: <img src="images/res.3.png" alt="" title="Камень"/> 200
	  <img src="images/res.4.png" alt="" title="Руда"/> 250
	  <img src="images/time.png" alt="" title="Время"/> 2 мин.
	  <br/>- - -
	  <br/>
	  <a href="build3"><u>Построить</u></a>
	  ' . separator;
}

if (mysql_num_rows($b4) == false)
{
echo '<img src="images/building_group_4.png" alt=""/> Каменоломня<br/>
	  Добывает +300 камня в час<br/>
	  <img src="images/lvl.png" alt="" title="ур"/> Максимальный уровень: 10<br/>
	  Требуется: <img src="images/res.1.png" alt="" title="Еда"/> 400
	  <img src="images/res.2.png" alt="" title="Дерево"/> 250
	  <img src="images/res.4.png" alt="" title="Руда"/> 400
	  <img src="images/time.png" alt="" title="Время"/> 2 мин.
	  <br/>- - -
	  <br/>
	  <a href="build4"><u>Построить</u></a>
	  ' . separator;
}

if (mysql_num_rows($b5) == false)
{
echo '<img src="images/building_group_5.png" alt=""/> Прииск<br/>
	  Отмывает +300 золота в час<br/>
	  <img src="images/lvl.png" alt="" title="ур"/> Максимальный уровень: 10<br/>
	  Требуется: <img src="images/res.2.png" alt="" title="Дерево"/> 200
	  <img src="images/res.3.png" alt="" title="Камень"/> 300
	  <img src="images/res.4.png" alt="" title="Руда"/> 200
	  <img src="images/time.png" alt="" title="Время"/> 2 мин.
	  <br/>- - -
	  <br/>
	  <a href="build5"><u>Построить</u></a>
	  ' . separator;
}

if (mysql_num_rows($b6) == false)
{
echo '<img src="images/building_group_6.png" alt=""/> Казарма<br/>
	  Позволяет тренировать виды войск<br/>
	  Необходимо: ресурсные здания<br/>
	  <img src="images/lvl.png" alt="" title="ур"/> Максимальный уровень: 1<br/>
	  Требуется: <img src="images/res.2.png" alt="" title="Дерево"/> 200
	  <img src="images/res.3.png" alt="" title="Камень"/> 300
	  <img src="images/res.4.png" alt="" title="Руда"/> 200
	  <img src="images/time.png" alt="" title="Время"/> 2 мин.
	  <br/>- - -
	  <br/>
	  <a href="build6"><u>Построить</u></a>
	  ' . separator;
}

if (mysql_num_rows($b7) == false)
{
echo '<img src="images/building_group_7.png" alt=""/> Торговая палата<br/>
	  Позволяет нанимать торговцев.<br/>
	  С развитием торговой палаты увеличивается количество переносимых ресурсов.<br/>
	  Необходимо: ресурсные здания, Рынок 1й уровень<br/>
	  <img src="images/lvl.png" alt="" title="ур"/> Максимальный уровень: 10<br/>
	  Требуется: <img src="images/res.3.png" alt="" title="Камень"/> 400
	  <img src="images/res.5.png" alt="" title="Золото"/> 200
	  <img src="images/time.png" alt="" title="Время"/> 10 мин.
	  <br/>- - -
	  <br/>
	  <a href="build7"><u>Построить</u></a>
	  ' . separator;
}

if (mysql_num_rows($b8) == false)
{
echo '<img src="images/building_group_8.png" alt=""/> Склад<br/>
	  Увеличивает запас ресурсов<br/>
	  Необходимо: ресурсные здания<br/>
	  <img src="images/lvl.png" alt="" title="ур"/> Максимальный уровень: 20<br/>
	  Требуется: <img src="images/res.3.png" alt="" title="Камень"/> 200
	  <img src="images/res.4.png" alt="" title="Руда"/> 200
	  <img src="images/time.png" alt="" title="Время"/> 8 мин.
	  <br/>- - -
	  <br/>
	  <a href="build8"><u>Построить</u></a>
	  ' . separator;
}

if (mysql_num_rows($b9) == false)
{
echo '<img src="images/building_group_9.png" alt=""/> Рынок<br/>
	  Позволяет обмениваться ресурсами с другими игроками<br/>
	  С развитием рынка увеличивается количество доступных торговцев.<br/>
	  Необходимо: ресурсные здания<br/>
	  <img src="images/lvl.png" alt="" title="ур"/> Максимальный уровень: 10<br/>
	  Требуется: <img src="images/res.2.png" alt="" title="Дерево"/> 150
	  <img src="images/res.5.png" alt="" title="Золото"/> 200
	  <img src="images/time.png" alt="" title="Время"/> 5 мин.
	  <br/>- - -
	  <br/>
	  <a href="build9"><u>Построить</u></a>
	  ' . separator;
}

if (mysql_num_rows($b10) == false)
{
echo '<img src="images/building_group_10.png" alt=""/> Кузница<br/>
	  Позволяет модернизировать оружие и броню, увеличивая атаку и защиту войск.<br/>
	  Необходимо: ресурсные здания, Казарма 1й уровень<br/>
	  <img src="images/lvl.png" alt="" title="ур"/> Максимальный уровень: 1<br/>
	  Требуется: <img src="images/res.2.png" alt="" title="Дерево"/> 150
	  <img src="images/res.5.png" alt="" title="Золото"/> 200
	  <img src="images/time.png" alt="" title="Время"/> 5 мин.
	  <br/>- - -
	  <br/>
	  <a href="build10"><u>Построить</u></a>
	  ' . separator;
}

if (mysql_num_rows($b11) == false)
{
echo '<img src="images/building_group_11.png" alt=""/> Дипломатический центр<br/>
	  Позволяет вступать в существующие альянсы и создавать свои.<br/>
	  Необходимо: ресурсные здания<br/>
	  <img src="images/lvl.png" alt="" title="ур"/> Максимальный уровень: 1<br/>
	  Требуется: <img src="images/res.2.png" alt="" title="Дерево"/> 400
	  <img src="images/res.3.png" alt="" title="Камень"/> 450
	  <img src="images/res.5.png" alt="" title="Золото"/> 300
	  <img src="images/time.png" alt="" title="Время"/> 6 мин.
	  <br/>- - -
	  <br/>
	  <a href="build11"><u>Построить</u></a>
	  ' . separator;
}

if (mysql_num_rows($b12) == false)
{
echo '<img src="images/building_group_12.png" alt=""/> Цитадель<br/>
	  Позволяет тренировать специальных юнитов.<br/>
	  Необходимо: ресурсные здания<br/>
	  <img src="images/lvl.png" alt="" title="ур"/> Максимальный уровень: 1<br/>
	  Требуется: <img src="images/res.2.png" alt="" title="Дерево"/> 300
	  <img src="images/res.4.png" alt="" title="Руда"/> 450
	  <img src="images/res.5.png" alt="" title="Золото"/> 200
	  <img src="images/time.png" alt="" title="Время"/> 5 мин.
	  <br/>- - -
	  <br/>
	  <a href="build12"><u>Построить</u></a>
	  ' . separator;
}

if (mysql_num_rows($b13) == false)
{
echo '<img src="images/building_group_13.png" alt=""/> Бастион<br/>
	  Позволяет улучшать навыки специальных юнитов.<br/>
	  Необходимо: ресурсные здания, Цитадель<br/>
	  <img src="images/lvl.png" alt="" title="ур"/> Максимальный уровень: 1<br/>
	  Требуется: <img src="images/res.2.png" alt="" title="Дерево"/> 350
	  <img src="images/res.3.png" alt="" title="Камень"/> 400
	  <img src="images/res.5.png" alt="" title="Золото"/> 300
	  <img src="images/time.png" alt="" title="Время"/> 5 мин.
	  <br/>- - -
	  <br/>
	  <a href="build13"><u>Построить</u></a>
	  ' . separator;
}

if (mysql_num_rows($b14) == false)
{
echo '<img src="images/building_group_14.png" alt=""/> Караульная башня<br/>
	  Отображает все нападения на ваш замок, а так же передвижение ваших армий.<br/>
	  Необходимо: ресурсные здания, Казарма<br/>
	  <img src="images/lvl.png" alt="" title="ур"/> Максимальный уровень: 1<br/>
	  Требуется: <img src="images/res.2.png" alt="" title="Дерево"/> 350
	  <img src="images/res.5.png" alt="" title="Золото"/> 300
	  <img src="images/time.png" alt="" title="Время"/> 15 мин.
	  <br/>- - -
	  <br/>
	  <a href="build14"><u>Построить</u></a>
	  ' . separator;
}

if (mysql_num_rows($b1) == 1
&& mysql_num_rows($b2) == 1
&& mysql_num_rows($b3) == 1
&& mysql_num_rows($b4) == 1
&& mysql_num_rows($b5) == 1
&& mysql_num_rows($b6) == 1
&& mysql_num_rows($b7) == 1
&& mysql_num_rows($b8) == 1
&& mysql_num_rows($b9) == 1
&& mysql_num_rows($b10) == 1
&& mysql_num_rows($b11) == 1
&& mysql_num_rows($b12) == 1
&& mysql_num_rows($b13) == 1
&& mysql_num_rows($b14) == 1) echo 'Нет зданий доступных для постройки!<br/>';
break;

case upgrade:
if (isset($_GET['id']))
{
	$id = number($_GET['id']);
	$err = mysql_query("SELECT id, cid, level, timeleft FROM construct WHERE user = '$user[id]' AND id = '$id' LIMIT 1");
	if (mysql_num_rows($err) == false)
	{
		err('Здание не найдено на карте!');
	}
	else
	{
		$is = mysql_fetch_assoc($err);
		$myb = mysql_query("SELECT * FROM construct WHERE user = '$user[id]' AND id = '$id' AND timeleft > '$rtime' LIMIT 1");
		$in = mysql_fetch_assoc($myb);

		if ($user['timeleft'] < $rtime) mysql_query("UPDATE users SET timeleft = '" .  ($rtime + $in['stamp']) . "' WHERE id = '$user[id]' LIMIT 1");

		$current = $rtime - $user['timeleft'];

		if (mysql_num_rows($myb) != false && $current <= $in['stamp'])
		{
			$b = 'Идет строительство [<img src="images/build.png" alt=""/> ' . round(($current + $in['stamp']) * 100 / $in['stamp'], 1) . '%..]<br/>
			Осталось ' . val($user['timeleft'] - $rtime) . '<br/>';
		}

		if ($is['level'] < 10) $up = '<br/>На ' . ($is['level'] + 1) . '-м уровне добыча ' . (300 + 300 * $is['level']) . '<br/>';
		if (mysql_num_rows($myb) == false && $is['level'] < 10) $u = $up . '<img src="images/upgrade.png" alt="+"/>  <a href="pump'.$id.'">Улучшить</a><br/>';

		switch($is['cid']):
			case 1:
				$numArmy = mysql_fetch_assoc(mysql_query("SELECT SUM(lot) AS num FROM duty WHERE user = '$user[id]' AND timeleft < '$rtime'"));
				$consuming = ceil($numArmy['num'] / 100 * 10);
				$name = $b . '<img src="images/building_group_1.png" alt=""/><br/>
						<img src="images/building_group_1_small.png" alt=""/> <u>Ферма</u> ' . $is['level'] . '-й уровень<br/>
						Выращивает ' . ($is['level'] == 1 ? 300 : 300 * $is['level']) . ' еды в час<br/>
						Потребление: <img src="images/res.1.png" alt=""/> <span style="color:#ff3030;">' . $consuming . '/час</span>
						<br/>
						' . (isset($u) ?
						'Требуется: <img src="images/res.2.png" alt="Дерево"/> ' . round(100 * ($is['level'] + 1) / 1.5) . '
						<img src="images/res.5.png" alt="Золото"/> ' . round(200 * ($is['level'] + 1) / 1.5) . '
						<img src="images/time.png" alt="" title="Время"/> ' . val(60 * ($is['level'] + 1) / 1.5) : '') . $u;
			break;
			case 2:
				$name = $b . '<img src="images/building_group_2.png" alt=""/><br/>
						<img src="images/building_group_2_small.png" alt=""/> <u>Шахта</u> ' . $is['level'] . '-й уровень<br/>
						Добывает ' . ($is['level'] == 1 ? 300 : 300 * $is['level']) . ' руды в час<br/>
						' . (isset($u) ?
						'Требуется: <img src="images/res.2.png" alt="" title="Дерево"/> ' . round(200 * ($is['level'] + 1) / 1.5) . '
	  					<img src="images/res.5.png" alt="" title="Золото"/> ' . round(250 * ($is['level'] + 1) / 1.5) . '
	  					<img src="images/time.png" alt="" title="Время"/> ' . val(120 * ($is['level'] + 1) / 1.5) : '') . $u;
			break;
			case 3:
				$name = $b . '<img src="images/building_group_3.png" alt=""/><br/>
						<img src="images/building_group_3_small.png" alt=""/> <u>Лесопилка</u> ' . $is['level'] . '-й уровень<br/>
						Вырубает ' . ($is['level'] == 1 ? 300 : 300 * $is['level']) . ' древесины в час<br/>
						' . (isset($u) ?
						'Требуется: <img src="images/res.3.png" alt="" title="Камень"/> ' . round(200 * ($is['level'] + 1) / 1.5) . '
	  					<img src="images/res.4.png" alt="" title="Руда"/> ' . round(250 * ($is['level'] + 1) / 1.5) . '
	 				    <img src="images/time.png" alt="" title="Время"/> ' . val(120 * ($is['level'] + 1) / 1.5) : '') . $u;
			break;
			case 4:
				$name = $b . '<img src="images/building_group_4.png" alt=""/><br/>
						<img src="images/building_group_4_small.png" alt=""/> <u>Каменоломня</u> ' . $is['level'] . '-й уровень<br/>
						Добывает ' . ($is['level'] == 1 ? 300 : 300 * $is['level']) . ' камня в час<br/>
						' . (isset($u) ?
						'Требуется: <img src="images/res.1.png" alt="" title="Еда"/> ' . round(400 * ($is['level'] + 1) / 1.5) . '
	  					<img src="images/res.2.png" alt="" title="Дерево"/> ' . round(250 * ($is['level'] + 1) / 1.5) . '
	  					<img src="images/res.4.png" alt="" title="Руда"/> ' . round(400 * ($is['level'] + 1) / 1.5) . '
	 				    <img src="images/time.png" alt="" title="Время"/> ' . val(120 * ($is['level'] + 1) / 1.5) : '') . $u;
			break;
			case 5:
				$name = $b . '<img src="images/building_group_5.png" alt=""/><br/>
						<img src="images/building_group_5_small.png" alt=""/> <u>Прииск</u> ' . $is['level'] . '-й уровень<br/>
						Отмывает ' . ($is['level'] == 1 ? 300 : 300 * $is['level']) . ' золота в час<br/>
						' . (isset($u) ?
						'Требуется: <img src="images/res.2.png" alt="" title="Дерево"/> ' . round(200 * ($is['level'] + 1) / 1.5) . '
	  					<img src="images/res.3.png" alt="" title="Камень"/> ' . round(300 * ($is['level'] + 1) / 1.5) . '
	 				    <img src="images/res.4.png" alt="" title="Руда"/> ' . round(200 * ($is['level'] + 1) / 1.5) . '
	 				    <img src="images/time.png" alt="" title="Время"/> ' . val(120 * ($is['level'] + 1) / 1.5) : '') . $u;
			break;
			case 6:
				$c_1 = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) AS lot, SUM(lot) AS num FROM duty WHERE cid = '1' AND user = '$user[id]' AND timeleft < '$rtime'"));
				$c_2 = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) AS lot, SUM(lot) AS num FROM duty WHERE cid = '2' AND user = '$user[id]' AND timeleft < '$rtime'"));
				$c_3 = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) AS lot, SUM(lot) AS num FROM duty WHERE cid = '3' AND user = '$user[id]' AND timeleft < '$rtime'"));
				$c_4 = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) AS lot, SUM(lot) AS num FROM duty WHERE cid = '4' AND user = '$user[id]' AND timeleft < '$rtime'"));
				$c_5 = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) AS lot, SUM(lot) AS num FROM duty WHERE cid = '5' AND user = '$user[id]' AND timeleft < '$rtime'"));

				$units = ($user['race'] == 1)
								?
							 '<img src="resize.php?img=images/paladin.png&width=35&height=0" alt=""/> <a href="duty1">Паладин</a> (' . (empty($c_1['num']) ? 0 : $c_1['num']) . '/' . $c_1['lot'] . ')<br/>
							  <img src="resize.php?img=images/mechnik.png&width=35&height=0" alt=""/> <a href="duty2">Мечник</a> (' . (empty($c_2['num']) ? 0 : $c_2['num']) . '/' . $c_2['lot'] . ')<br/>
							  <img src="resize.php?img=images/strelok.png&width=35&height=0" alt=""/> <a href="duty3">Стрелок</a> (' . (empty($c_3['num']) ? 0 : $c_3['num']) . '/' . $c_3['lot'] . ')<br/>
							  <img src="resize.php?img=images/mag.png&width=35&height=0" alt=""/> <a href="duty4">Маг</a> (' . (empty($c_4['num']) ? 0 : $c_4['num']) . '/' . $c_4['lot'] . ')<br/>
							  <img src="resize.php?img=images/koloss.png&width=35&height=0" alt=""/> <a href="duty5">Колосс</a> (' . (empty($c_5['num']) ? 0 : $c_5['num']) . '/' . $c_5['lot'] . ')<br/>
							  ' : '
							  <img src="resize.php?img=images/prizrak.png&width=35&height=0" alt=""/> <a href="duty1">Призрак</a> (' . (empty($c_1['num']) ? 0 : $c_1['num']) . '/' . $c_1['lot'] . ')<br/>
							  <img src="resize.php?img=images/himera.png&width=35&height=0" alt=""/> <a href="duty2">Химера</a> (' . (empty($c_2['num']) ? 0 : $c_2['num']) . '/' . $c_2['lot'] . ')<br/>
							  <img src="resize.php?img=images/oskvernitel.png&width=35&height=0" alt=""/> <a href="duty3">Осквернитель</a> (' . (empty($c_3['num']) ? 0 : $c_3['num']) . '/' . $c_3['lot'] . ')<br/>
							  <img src="resize.php?img=images/grifon.png&width=35&height=0" alt=""/> <a href="duty4">Грифон</a> (' . (empty($c_4['num']) ? 0 : $c_4['num']) . '/' . $c_4['lot'] . ')<br/>
							  <img src="resize.php?img=images/yetti.png&width=35&height=0" alt=""/> <a href="duty5">Йетти</a> (' . (empty($c_5['num']) ? 0 : $c_5['num']) . '/' . $c_5['lot'] . ')<br/>';
				$name = $b . '<img src="images/building_group_6.png" alt=""/><br/>
						<img src="images/building_group_6_small.png" alt=""/> <u>Казарма</u><br/>
						Позволяет тренировать виды войск<br/>' . $units;
			break;
			case 7:
				$dealers = mysql_result(mysql_query("SELECT SUM(dealer) FROM rialto WHERE player = '$user[id]'"), 0);
				$test = mysql_query("SELECT id, timeleft FROM rialto WHERE player = '$user[id]' AND timeleft > '$rtime' LIMIT 1");
				$tpl = mysql_fetch_assoc(mysql_query("SELECT level FROM construct WHERE cid = '9' AND user = '$user[id]' AND timeleft < '$rtime'"));
				$cap = ($user['race'] == 1) ? round(15 * $is['level'] / 2) : round(25 * $is['level'] / 2);
				$sp = ($user['race'] == 1) ? 35 : 20;

				$dealers = ($dealers == false) ? 0 : $dealers;
				if (isset($_POST['ok']))
				{
					$dealer = number($_POST['dealer']);
					if ($is['timeleft'] > $rtime)
					{
						err('Здание еще не достроено!');
					}
					elseif (empty($dealer))
					{
						err('Необходимо указать кол-во торговцев!');
					}
					elseif ($dealer > ($tpl['level'] * 5 - $dealers))
					{
						err('Максимум можно нанять еще ' . ($tpl['level'] * 5 - $dealers) . ' торговцев!');
					}
					elseif (mysql_num_rows($test) != false)
					{
						err('Только 1 партию торговцев можно нанимать одновременно!');
					}
					elseif ($user['gold'] < 100 * $dealer)
					{
						err('Тебе нехватает: <img src="/images/res.5.png" alt="" title="Золото"/>' . (100 * $dealer - $user['gold']));
					}
					elseif ($user['food'] < 200 * $dealer)
					{
						err('Тебе нехватает: <img src="/images/res.1.png" alt="" title="Еда"/>' . (200 * $dealer - $user['food']));
					}
					else
					{
						mysql_query("UPDATE users SET
									food = food - '" . (200 * $dealer) . "',
									gold = gold - '" . (100 * $dealer) . "' WHERE id = '$user[id]' LIMIT 1");
						if ($dealers == 0)
						{
							mysql_query("INSERT INTO rialto SET player = '$user[id]', timeleft = '" . ($rtime + $dealer * 300) . "', dealer = '$dealer'");
						}
						else
						{
							mysql_query("UPDATE rialto SET timeleft = '" . ($rtime + $dealer * 300) . "', dealer = dealer + '$dealer' WHERE player = '$user[id]' LIMIT 1");
						}
						msg('Торговцы подготавливаются.');
					}
				}

				if (mysql_num_rows($test) != false)
				{
					$inSql = mysql_fetch_assoc($test);
					echo 'Подготовка торговцев закончится через ' . val($inSql['timeleft'] - $rtime) . '<br/>';
				}

				if (mysql_num_rows($myb) == false && $is['level'] < 10)
				{
					$us = '<img src="images/upgrade.png" alt="+"/> <a href="pump'.$id.'">Улучшить</a><br/>';
				}

				if ($is['level'] < 10) $up = 'На ' . ($is['level'] + 1) . '-м уровне кол-во переносимых ресурсов одним торговцем ' . ($cap * 2) . ' ед.<br/>';

				$dealers_run = mysql_num_rows(mysql_query("SELECT id FROM rialto WHERE player = '$user[id]' AND time_run > '$rtime'"));
				$dealers_back = mysql_num_rows(mysql_query("SELECT id FROM rialto WHERE player = '$user[id]' AND time_back > '$rtime'"));

				$name = $b . '<img src="images/building_group_7.png" alt=""/><br/>
						<img src="images/building_group_7_small.png" alt=""/> <u>Торговая палата</u>  ' . $is['level'] . '-й уровень<br/>
						Позволяет нанимать торговцев.<br/>
						С развитием торговой палаты увеличивается количество переносимых ресурсов.<br/>
						1 торговец переносит ' . $cap . ' ед. ресурса, скорость ' . $sp . ' клеток в час.<br/>
						' . $up . (isset($up) ?
						'Требуется: <img src="images/res.1.png" alt="" title="Камень"/> ' . round(400 * ($is['level'] + 1) / 1.5) . '
	  					<img src="images/res.5.png" alt="" title="Золото"/> ' . round(200 * ($is['level'] + 1) / 1.5) . '
	  					<img src="images/time.png" alt="" title="Время"/> ' . val(600 * ($is['level'] + 1) / 1.5) . '<br/>' : '') . '
						<br/>
						Торговцев в резерве: ' . $dealers . '
						<br/>
						<br/>
						&#8226; <a href="dealers_run">Торговцы в дороге</a> (' . $dealers_run . ')<br/>
	  					&#8226; <a href="dealers_back">Прибывающие торговцы</a> (' . $dealers_back . ')<br/>
						<br/>
						<form method="post" action="upgrade'.$id.'">
						<input type="text" name="dealer" size="2" maxlength="2" value="'.($tpl['level'] * 5 - $dealers).'"/>
						<input type="submit" name="ok" value="Нанять"/>
						</form>' . $us;
			break;
			case 8:
				if (mysql_num_rows($myb) == false && $is['level'] < 20)
				{
					$us = '<br/><img src="images/upgrade.png" alt="+"/> <a href="pump'.$id.'">Улучшить</a><br/>';
				}

				if ($is['level'] < 20) $up = '<br/>На ' . ($is['level'] + 1) . '-м уровне вместимость ' . (1000 * ($is['level'] + 1) * 5) . ' ед.<br/>';

				$storage = 1000 * $is['level'] * 5;
				$name = $b . '<img src="images/building_group_8.png" alt=""/><br/>
						<img src="images/building_group_8_small.png" alt=""/> <u>Склад</u> ' . $is['level'] . '-й уровень<br/>
						Увеличивает запас ресурсов
						' . $up . separator . '
						Размеры склада:<br/>
						<span class="nowrap"><img src="/images/res.1.png" alt="" title="Еда"/> ' . $storage . '</span>
	  					<span class="nowrap"><img src="/images/res.2.png" alt="" title="Дерево"/> ' . $storage . '</span>
	  					<span class="nowrap"><img src="/images/res.3.png" alt="" title="Камень"/> ' . $storage . '</span>
	 				   	<span class="nowrap"><img src="/images/res.4.png" alt="" title="Руда"/> ' . $storage . '</span>
	 				   	<span class="nowrap"><img src="/images/res.5.png" alt="" title="Золото"/> ' . $storage . '</span><br/>
						' . (isset($up) ?
						'Требуется: <img src="images/res.3.png" alt="" title="Камень"/> ' . round(200 * ($is['level'] + 1) / 1.5) . '
	  					<img src="images/res.4.png" alt="" title="Руда"/> ' . round(200 * ($is['level'] + 1) / 1.5) . '
	  					<img src="images/time.png" alt="" title="Время"/> ' . val(600 * ($is['level'] + 1) / 1.5) : '') . $us;
			break;
			case 9:
				$Ri = mysql_query("SELECT * FROM rialto WHERE timeleft < '$rtime' AND player = '$user[id]' LIMIT 1");

				if (mysql_num_rows($Ri) != false)
				{
					$sRi = mysql_fetch_assoc($Ri);
					if ($sRi['time_run'] > $rtime)
					{
						echo 'Торговцы в пути, осталось ' . val($sRi['time_run'] - $rtime) . '<br/>';
					}
					elseif ($sRi['time_back'] > $rtime)
					{
						echo 'Торговцы возвращаются, осталось ' . val($sRi['time_back'] - $rtime) . '<br/>';
					}
				}

				if (mysql_num_rows($myb) == false && $is['level'] < 10)
				{
					$us = '<br/><img src="images/upgrade.png" alt="+"/> <a href="pump'.$id.'">Улучшить</a><br/>';
				}

				if ($is['level'] < 10) $up = '<br/>На ' . ($is['level'] + 1) . '-м уровне ' . (5 * ($is['level'] + 1)) . ' торговцев.<br/>';

				$name = $b . '<img src="images/building_group_9.png" alt=""/><br/>
						<img src="images/building_group_9_small.png" alt=""/> <u>Рынок</u> ' . $is['level'] . '-й уровень<br/>
						Доступно ' . (5 * $is['level']) . ' торговецев.' . $up . '
						Позволяет обмениваться ресурсами с другими игроками.<br/>
						С развитием рынка увеличивается количество доступных торговцев.<br/>
						' . (isset($up) ?
						'Требуется: <img src="images/res.2.png" alt="" title="Дерево"/> ' . round(150 * ($is['level'] + 1) / 1.5) . '
	  					<img src="images/res.5.png" alt="" title="Золото"/> ' . round(200 * ($is['level'] + 1) / 1.5) . '
	  					<img src="images/time.png" alt="" title="Время"/>' . val(300 * ($is['level'] + 1) / 1.5) : '') . $us . '
						<br/>
						&#8226; <a href="transmint">Передать ресурсы</a><br/>
						&#8226; <a href="market">Обмен</a><br/>';
			break;
			case 10:
				$units = ($user['race'] == 1)
								?
							 '<img src="resize.php?img=images/paladin.png&width=35&height=0" alt=""/> <a href="smithy1">Паладин</a> (' . paramUnit(1, $user['id'], 1) . '/' . paramUnit(1, $user['id'], 2) . ')<br/>
							  <img src="resize.php?img=images/mechnik.png&width=35&height=0" alt=""/> <a href="smithy2">Мечник</a> (' . paramUnit(2, $user['id'], 1) . '/' . paramUnit(2, $user['id'], 2) . ')<br/>
							  <img src="resize.php?img=images/strelok.png&width=35&height=0" alt=""/> <a href="smithy3">Стрелок</a> (' . paramUnit(3, $user['id'], 1) . '/' . paramUnit(3, $user['id'], 2) . ')<br/>
							  <img src="resize.php?img=images/mag.png&width=35&height=0" alt=""/> <a href="smithy4">Маг</a> (' . paramUnit(4, $user['id'], 1) . '/' . paramUnit(4, $user['id'], 2) . ')<br/>
							  <img src="resize.php?img=images/koloss.png&width=35&height=0" alt=""/> <a href="smithy5">Колосс</a> (' . paramUnit(5, $user['id'], 1) . '/' . paramUnit(5, $user['id'], 2) . ')<br/>
							  ' : '
							  <img src="resize.php?img=images/prizrak.png&width=35&height=0" alt=""/> <a href="smithy1">Призрак</a> (' . paramUnit(1, $user['id'], 1) . '/' . paramUnit(1, $user['id'], 2) . ')<br/>
							  <img src="resize.php?img=images/himera.png&width=35&height=0" alt=""/> <a href="smithy2">Химера</a> (' . paramUnit(2, $user['id'], 1) . '/' . paramUnit(2, $user['id'], 2) . ')<br/>
							  <img src="resize.php?img=images/oskvernitel.png&width=35&height=0" alt=""/> <a href="smithy3">Осквернитель</a> (' . paramUnit(3, $user['id'], 1) . '/' . paramUnit(3, $user['id'], 2) . ')<br/>
							  <img src="resize.php?img=images/grifon.png&width=35&height=0" alt=""/> <a href="smithy4">Грифон</a> (' . paramUnit(4, $user['id'], 1) . '/' . paramUnit(4, $user['id'], 2) . ')<br/>
							  <img src="resize.php?img=images/yetti.png&width=35&height=0" alt=""/> <a href="smithy5">Йетти</a> (' . paramUnit(5, $user['id'], 1) . '/' . paramUnit(5, $user['id'], 2) . ')<br/>';

				$name = $b . '<img src="images/building_group_10.png" alt=""/><br/>
						<img src="images/building_group_10_small.png" alt=""/> <u>Кузница</u><br/>
						Позволяет модернизировать оружие и броню, увеличивая атаку и защиту войск.<br/>
						' . $units;
			break;
			case 11:
						$myAliance = mysql_query("SELECT * FROM aliance WHERE id IN(SELECT uid FROM players_aliance WHERE player = '$user[id]') LIMIT 1");
						if (mysql_num_rows($myAliance) != false)
						{
							$inAL = mysql_fetch_assoc($myAliance);
							$LOGO = ($inAL['logo'] != '') ? '<img src="images/clan/'.$inAL['logo'].'" alt=""/>' : '';
							$myal = '&rarr; <a href="aliance'.$inAL['id'].'"><u>' . $inAL['name'] . '</u> ' . $LOGO . '</a><br/>';
						}
						else
						{
							$c = '&rarr; <a href="create_aliance"><u>Создать альянс</u></a><br/>';
						}
				$name = $b . '<img src="images/building_group_11.png" alt=""/><br/>
					 	<img src="images/building_group_11_small.png" alt=""/> <u>Дипломатический центр</u><br/>
						Позволяет вступать в существующие альянсы и создавать свои.<br/>
						&rarr; <a href="list_aliance"><u>Список альянсов</u></a><br/>
						&rarr; <a href="search_aliance"><u>Найти альянс</u></a><br/>
						' . $c . $myal;
			break;
			case 12:
				$general = mysql_result(mysql_query("SELECT COUNT(*) FROM generals WHERE player = '$user[id]' AND timeleft < '$rtime'"), 0);

				$name = $b . '<img src="images/building_group_12.png" alt=""/><br/>
					 	<img src="images/building_group_12_small.png" alt=""/> <u>Цитадель</u><br/>
						Позволяет тренировать специальных юнитов.<br/>
						&raquo; <a href="general">Генералы</a> (' . $general . ')<br/>
						&raquo; <a href="thief">Вор</a><br/>
						&raquo; <a href="scout">Шпион</a><br/>
						&raquo; <a href="recruiter">Вербовщик</a><br/>
						&raquo; <a href="saboteur">Саботажник</a><br/>';
			break;
			case 13:
				$name = $b . '<img src="images/building_group_13.png" alt=""/><br/>
					 	<img src="images/building_group_13_small.png" alt=""/> <u>Бастион</u><br/>
						Позволяет улучшать навыки специальных юнитов.<br/>
						&raquo; <a href="generals">Генералы</a><br/>
						&raquo; <a href="thiefs">Вор</a><br/>
						&raquo; <a href="scouts">Шпион</a><br/>
						&raquo; <a href="recruiters">Вербовщик</a><br/>
						&raquo; <a href="saboteurs">Саботажник</a><br/>';
			break;
			case 14:
				$troops_my = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) AS run, (SELECT COUNT(*) FROM duty WHERE user = '$user[id]' AND time_back > '$rtime') AS back FROM duty WHERE user = '$user[id]' AND time_run > '$rtime'"));
				$troops_enemy = mysql_num_rows(mysql_query("SELECT id FROM users WHERE terrain IN(SELECT id FROM location WHERE id_player = '$user[id]') AND id IN(SELECT user FROM duty WHERE time_run > '$rtime' AND done = '0')"));

				$scout = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) AS run, (SELECT COUNT(*) FROM scouts WHERE player = '$user[id]' AND time_back > '$rtime') AS back FROM scouts WHERE player = '$user[id]' AND time_run > '$rtime'"));

				$saboteur = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) AS run, (SELECT COUNT(*) FROM saboteurs WHERE player = '$user[id]' AND time_back > '$rtime') AS back FROM saboteurs WHERE player = '$user[id]' AND time_run > '$rtime'"));

				$recruiter = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) AS run, (SELECT COUNT(*) FROM recruiters WHERE player = '$user[id]' AND time_back > '$rtime') AS back FROM recruiters WHERE player = '$user[id]' AND time_run > '$rtime'"));

				$name = $b . '<img src="images/building_group_14.png" alt=""/><br/>
					 	<img src="images/building_group_14_small.png" alt=""/> <u>Караульная башня</u><br/>
						Отображает все нападения на ваш замок, а так же передвижение ваших армий.<br/><br/>
						&raquo; <a href="troops_run">Войска в дороге</a> (' . $troops_my['run'] . ')<br/>
	  					&raquo; <a href="troops_back">Прибывающие войска</a> (' . $troops_my['back'] . ')<br/>
						&raquo; <a href="troops_enemy">Вражеские войска</a> (' . $troops_enemy . ')<br/>
						- - -
						<br/>
						&raquo; <a href="my_scout_run">Шпионы в разведке</a> (' . $scout['run'] . ')<br/>
						&raquo; <a href="my_scout_back">Прибывающие шпионы</a> (' . $scout['back'] . ')<br/>
						- - -
						<br/>
						&raquo; <a href="my_saboteur_run">Саботажники в дороге</a> (' . $saboteur['run'] . ')<br/>
						&raquo; <a href="my_saboteur_back">Прибывающие саботажники</a> (' . $saboteur['back'] . ')<br/>
						- - -
						<br/>
						&raquo; <a href="my_recruiter_run">Вербовщики в дороге</a> (' . $recruiter['run'] . ')<br/>
						&raquo; <a href="my_recruiter_back">Прибывающие вербовщики</a> (' . $recruiter['back'] . ')<br/>';
			break;
		endswitch;

		if ($user['timeleft'] < $rtime) mysql_query("UPDATE users SET timeleft = '" .  ($rtime + $in['stamp']) . "' WHERE id = '$user[id]' LIMIT 1");

		$current = $rtime - $user['timeleft'];

		if (mysql_num_rows($myb) != false && $current < $in['stamp'])
		{
			echo 'Идет строительство [<img src="images/build.png" alt=""/> ' . round(($current + $in['stamp']) * 100 / $in['stamp'], 1) . '%..]<br/>
			Осталось ' . val($user['timeleft'] - $rtime) . '<br/>';
		}
		else
		{
			echo $name;
		}
	}
}
break;

case recruiter:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '12' AND user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
	if (!isset($id_build['id']))
	{
		err('У тебя не построено здание Рынок!');
		include 'sys/foot.php';
		exit();
	}

	$test = mysql_query("SELECT id, timeleft FROM recruiters WHERE player = '$user[id]' LIMIT 1");
	$inSql = mysql_fetch_assoc($test);

	$food = 640;
	$tree = 760;
	$stone = 800;
	$ore = 775;
	$gold = 350;

	if (isset($_POST['ok']))
	{
		if ($user['food'] < $food) $err2 .= ($food - $user['food']);
		if ($user['tree'] < $tree) $err2 .= ($tree - $user['tree']);
		if ($user['stone'] < $stone) $err2 .= ($stone - $user['stone']);
		if ($user['ore'] < $ore) $err2 .= ($ore - $user['ore']);
		if ($user['gold'] < $gold) $err2 .= ($gold - $user['gold']);
		$tm = $rtime + 1800;

		if ($inSql['timeleft'] > $rtime)
		{
			err('Вербовщик уже тренируется!');
		}
		elseif (isset($inSql['id']))
		{
			err('Вербовщик уже имеется!');
		}
		elseif (!empty($err2))
		{
			err('Недостаточно ресурсов: ' . $err2);
		}
		else
		{
			mysql_query("UPDATE users SET
			food = food - '$food',
			tree = tree - '$tree',
			stone = stone - '$stone',
			ore = ore - '$ore',
			gold = gold - '$gold'
			WHERE id = '$user[id]' LIMIT 1");

			mysql_query("INSERT INTO recruiters SET player = '$user[id]', timeleft = '$tm', health = '200'");
			msg('Тренировка началась.');
		}
	}

	if ($inSql['timeleft'] > $rtime) echo 'Тренировка вербовщика закончится через ' . val($inSql['timeleft'] - $rtime) . separator;

	echo '<form method="post" action="recruiter">
		  <img src="images/recruiter.png" alt="."/>
		  <br/>
		  <b>Вербовщик</b><br/>
		  <img src="images/time.png" alt="" title="Время обучения"/> Время обучения: ' . val(1800) . '<br/>
		  <img src="images/res.1.png" alt="Еда"/> ' . $food . '
		  <img src="images/res.2.png" alt="Дерево"/> ' . $tree . '
		  <img src="images/res.3.png" alt="Камень"/> ' . $stone . '
		  <img src="images/res.4.png" alt="Руда"/> ' . $ore . '
		  <img src="images/res.5.png" alt="Золото"/> ' . $gold . '
		  <br/>
		  <input type="submit" name="ok" value="Тренировать"/>
		  </form>
		  <a href="upgrade'.$id_build['id'].'">Вернуться</a>';
break;

case saboteur:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '12' AND user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
	if (!isset($id_build['id']))
	{
		err('У тебя не построено здание Рынок!');
		include 'sys/foot.php';
		exit();
	}

	$test = mysql_query("SELECT id, timeleft FROM saboteurs WHERE player = '$user[id]' LIMIT 1");
	$inSql = mysql_fetch_assoc($test);

	$food = 640;
	$tree = 760;
	$stone = 800;
	$ore = 775;
	$gold = 350;

	if (isset($_POST['ok']))
	{
		if ($user['food'] < $food) $err2 .= ($food - $user['food']);
		if ($user['tree'] < $tree) $err2 .= ($tree - $user['tree']);
		if ($user['stone'] < $stone) $err2 .= ($stone - $user['stone']);
		if ($user['ore'] < $ore) $err2 .= ($ore - $user['ore']);
		if ($user['gold'] < $gold) $err2 .= ($gold - $user['gold']);
		$tm = $rtime + 1800;

		if ($inSql['timeleft'] > $rtime)
		{
			err('Саботажник уже тренируется!');
		}
		elseif (isset($inSql['id']))
		{
			err('Саботажник уже имеется!');
		}
		elseif (!empty($err2))
		{
			err('Недостаточно ресурсов: ' . $err2);
		}
		else
		{
			mysql_query("UPDATE users SET
			food = food - '$food',
			tree = tree - '$tree',
			stone = stone - '$stone',
			ore = ore - '$ore',
			gold = gold - '$gold'
			WHERE id = '$user[id]' LIMIT 1");

			mysql_query("INSERT INTO saboteurs SET player = '$user[id]', timeleft = '$tm', health = '200'");
			msg('Тренировка началась.');
		}
	}

	if ($inSql['timeleft'] > $rtime) echo 'Тренировка саботажника закончится через ' . val($inSql['timeleft'] - $rtime) . separator;

	echo '<form method="post" action="saboteur">
		  <img src="images/saboteur.png" alt="."/>
		  <br/>
		  <b>Саботажник</b><br/>
		  <img src="images/time.png" alt="" title="Время обучения"/> Время обучения: ' . val(1800) . '<br/>
		  <img src="images/res.1.png" alt="Еда"/> ' . $food . '
		  <img src="images/res.2.png" alt="Дерево"/> ' . $tree . '
		  <img src="images/res.3.png" alt="Камень"/> ' . $stone . '
		  <img src="images/res.4.png" alt="Руда"/> ' . $ore . '
		  <img src="images/res.5.png" alt="Золото"/> ' . $gold . '
		  <br/>
		  <input type="submit" name="ok" value="Тренировать"/>
		  </form>
		  <a href="upgrade'.$id_build['id'].'">Вернуться</a>';
break;

case thief:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '12' AND user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
	if (!isset($id_build['id']))
	{
		err('У тебя не построено здание Рынок!');
		include 'sys/foot.php';
		exit();
	}

	$test = mysql_query("SELECT id, timeleft FROM thiefs WHERE player = '$user[id]' LIMIT 1");
	$inSql = mysql_fetch_assoc($test);

	$food = 640;
	$tree = 760;
	$stone = 800;
	$ore = 775;
	$gold = 350;

	if (isset($_POST['ok']))
	{
		if ($user['food'] < $food) $err2 .= ($food - $user['food']);
		if ($user['tree'] < $tree) $err2 .= ($tree - $user['tree']);
		if ($user['stone'] < $stone) $err2 .= ($stone - $user['stone']);
		if ($user['ore'] < $ore) $err2 .= ($ore - $user['ore']);
		if ($user['gold'] < $gold) $err2 .= ($gold - $user['gold']);
		$tm = $rtime + 1800;

		if ($inSql['timeleft'] > $rtime)
		{
			err('Вор уже тренируется!');
		}
		elseif (isset($inSql['id']))
		{
			err('Вор уже имеется!');
		}
		elseif (!empty($err2))
		{
			err('Недостаточно ресурсов: ' . $err2);
		}
		else
		{
			mysql_query("UPDATE users SET
			food = food - '$food',
			tree = tree - '$tree',
			stone = stone - '$stone',
			ore = ore - '$ore',
			gold = gold - '$gold'
			WHERE id = '$user[id]' LIMIT 1");

			mysql_query("INSERT INTO thiefs SET player = '$user[id]', timeleft = '$tm', health = '200'");
			msg('Тренировка началась.');
		}
	}

	if ($inSql['timeleft'] > $rtime) echo 'Тренировка вора закончится через ' . val($inSql['timeleft'] - $rtime) . separator;

	echo '<form method="post" action="thief">
		  <img src="images/thief.png" alt="."/>
		  <br/>
		  <b>Вор</b><br/>
		  <img src="images/time.png" alt="" title="Время обучения"/> Время обучения: ' . val(1800) . '<br/>
		  <img src="images/res.1.png" alt="Еда"/> ' . $food . '
		  <img src="images/res.2.png" alt="Дерево"/> ' . $tree . '
		  <img src="images/res.3.png" alt="Камень"/> ' . $stone . '
		  <img src="images/res.4.png" alt="Руда"/> ' . $ore . '
		  <img src="images/res.5.png" alt="Золото"/> ' . $gold . '
		  <br/>
		  <input type="submit" name="ok" value="Тренировать"/>
		  </form>
		  <a href="upgrade'.$id_build['id'].'">Вернуться</a>';
break;

case scout:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '12' AND user = '$user[id]' LIMIT 1"));
	$test = mysql_query("SELECT id, timeleft FROM scouts WHERE player = '$user[id]' LIMIT 1");
	$inSql = mysql_fetch_assoc($test);

	$food = 640;
	$tree = 760;
	$stone = 800;
	$ore = 775;
	$gold = 350;

	if (isset($_POST['ok']))
	{
		if ($user['food'] < $food) $err2 .= ($food - $user['food']);
		if ($user['tree'] < $tree) $err2 .= ($tree - $user['tree']);
		if ($user['stone'] < $stone) $err2 .= ($stone - $user['stone']);
		if ($user['ore'] < $ore) $err2 .= ($ore - $user['ore']);
		if ($user['gold'] < $gold) $err2 .= ($gold - $user['gold']);
		$tm = $rtime + 1800;

		if ($inSql['timeleft'] > $rtime)
		{
			err('Шпион уже тренируется!');
		}
		elseif (isset($inSql['id']))
		{
			err('Шпион уже имеется!');
		}
		elseif (!empty($err2))
		{
			err('Недостаточно ресурсов: ' . $err2);
		}
		else
		{
			mysql_query("UPDATE users SET
			food = food - '$food',
			tree = tree - '$tree',
			stone = stone - '$stone',
			ore = ore - '$ore',
			gold = gold - '$gold'
			WHERE id = '$user[id]' LIMIT 1");

			mysql_query("INSERT INTO scouts SET player = '$user[id]', timeleft = '$tm', health = '200'");
			msg('Тренировка началась.');
		}
	}

	if ($inSql['timeleft'] > $rtime) echo 'Тренировка шпиона закончится через ' . val($inSql['timeleft'] - $rtime) . separator;

	echo '<form method="post" action="scout">
		  <img src="images/scout.png" alt="."/>
		  <br/>
		  <b>Шпион</b><br/>
		  <img src="images/time.png" alt="" title="Время обучения"/> Время обучения: ' . val(1800) . '<br/>
		  <img src="images/res.1.png" alt="Еда"/> ' . $food . '
		  <img src="images/res.2.png" alt="Дерево"/> ' . $tree . '
		  <img src="images/res.3.png" alt="Камень"/> ' . $stone . '
		  <img src="images/res.4.png" alt="Руда"/> ' . $ore . '
		  <img src="images/res.5.png" alt="Золото"/> ' . $gold . '
		  <br/>
		  <input type="submit" name="ok" value="Тренировать"/>
		  </form>
		  <a href="upgrade'.$id_build['id'].'">Вернуться</a>';
break;

case tr_general:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '12' AND user = '$user[id]' LIMIT 1"));
	$available = mysql_result(mysql_query("SELECT COUNT(*) FROM generals WHERE player = '$user[id]' AND timeleft < '$rtime'"), 0);
	$test = mysql_query("SELECT id, timeleft FROM generals WHERE player = '$user[id]' AND timeleft > '$rtime' LIMIT 1");
	$inSql = mysql_fetch_assoc($test);

	$food = 640;
	$tree = 760;
	$stone = 800;
	$ore = 775;
	$gold = 350;

	if (isset($_POST['ok']))
	{
		$num = number($_POST['num']);

		if ($user['food'] < $food) $err2 .= ($food * $num - $user['food']);
		if ($user['tree'] < $tree * $num) $err2 .= ($tree * $num - $user['tree']);
		if ($user['stone'] < $stone * $num) $err2 .= ($stone * $num - $user['stone']);
		if ($user['ore'] < $ore * $num) $err2 .= ($ore * $num - $user['ore']);
		if ($user['gold'] < $gold * $num) $err2 .= ($gold * $num - $user['gold']);
		$tm = $rtime + $num * 1800;

		if (empty($num))
		{
			err('Необходимо ввести кол-во!');
		}
		elseif (mysql_num_rows($test) != false)
		{
			err('Только 1 партию генералов можно тренировать одновременно!');
		}
		elseif ($num > (3 - $available))
		{
			err('Введено кол-во больше допустимого!');
		}
		elseif (!empty($err2))
		{
			err('Недостаточно ресурсов: ' . $err2);
		}
		else
		{
			mysql_query("UPDATE users SET
			food = food - '" . ($food * $num) . "',
			tree = tree - '" . ($tree * $num) . "',
			stone = stone - '" . ($stone * $num) . "',
			ore = ore - '" . ($ore * $num) . "',
			gold = gold - '" . ($gold * $num) . "'
			WHERE id = '$user[id]' LIMIT 1");

			mysql_query("INSERT INTO generals SET player = '$user[id]', timeleft = '$tm', level = '1', health = '200'");
			msg('Тренировка началась.');
		}
	}

	if (isset($inSql['id'])) echo 'Тренировка генералов закончится через ' . val($inSql['timeleft'] - $rtime) . separator;

	echo '<form method="post" action="general">
		  <img src="images/general.png" alt="."/>
		  <br/>
		  <b>Генерал</b><br/>
		  <img src="images/time.png" alt="" title="Время обучения"/> Время обучения: ' . val(1800) . '<br/>
		  Кол-во:
		  <br/>
		  <input type="text" name="num" size="3" maxlength="3"/> (доступно: ' . (3 - $available) . ' из 3)
		  <br/>
		  <img src="images/res.1.png" alt="Еда"/> ' . $food . '
		  <img src="images/res.2.png" alt="Дерево"/> ' . $tree . '
		  <img src="images/res.3.png" alt="Камень"/> ' . $stone . '
		  <img src="images/res.4.png" alt="Руда"/> ' . $ore . '
		  <img src="images/res.5.png" alt="Золото"/> ' . $gold . '
		  <br/>
		  <input type="submit" name="ok" value="Тренировать"/>
		  </form>
		  <a href="upgrade'.$id_build['id'].'">Вернуться</a>';
break;

case generals:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '13' AND user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
	if (!isset($id_build['id']))
	{
		err('У тебя не построено здание Цитадель!');
		include 'sys/foot.php';
		exit();
	}
	$available = mysql_result(mysql_query("SELECT COUNT(*) FROM generals WHERE player = '$user[id]' AND timeleft < '$rtime'"), 0);
	if ($available == 0)
	{
		echo 'В резерве нет генералов. Найми их в Цитаделе.<br/>';
	}
	else
	{
		$slct = mysql_query("SELECT * FROM generals WHERE player = '$user[id]' AND timeleft < '$rtime'");
		$N = 0;
		while($q = mysql_fetch_assoc($slct))
		{
			++$N;
			echo '&raquo; <a href="general_control'.$q['id'].'">'.(empty($q['name']) ? 'Генерал №' . $N : $q['name']).'</a><br/>';
		}
	}
	echo '<br/><a href="upgrade'.$id_build['id'].'">Вернуться</a>';
break;

case thiefs:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '12' AND user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
	if (!isset($id_build['id']))
	{
		err('У тебя не построено здание Бастион!');
		include 'sys/foot.php';
		exit();
	}

	$available = mysql_query("SELECT * FROM thiefs WHERE player = '$user[id]' AND timeleft < '$rtime'");
	if (mysql_num_rows($available) == false)
	{
		echo 'В резерве нет вора. Найми его в Цитаделе.<br/>';
	}
	else
	{
		$in = mysql_fetch_assoc($available);
		$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '13' AND user = '$user[id]' LIMIT 1"));

		if ($in['health'] < 200 && $in['time_recovery'] != 0)
		{
			$ONL = round(($rtime - $in['time_recovery']) / 20);
			$rec = round(2 * 100 / 100);

			echo 'Идет восстановление...' . separator;
			mysql_query("UPDATE thiefs SET time_recovery = '$rtime', health = '" . plus($in['health'], isset($ONL) ? ($ONL * $rec) : $rec, 200) . "' WHERE player = '$user[id]' LIMIT 1");
		}
		elseif ($in['health'] == 200 && $in['time_recovery'] != 0)
		{
			mysql_query("UPDATE thiefs SET time_recovery = '0' WHERE player = '$user[id]' LIMIT 1");
		}

		echo '<img src="images/thief.png" alt=""/>
			  <br/>
			  <b>Вор</b><br/>
			  <img src="images/hp.png" alt="" title="Здоровье"/> Здоровье: ' . $in['health'] . ' / 200
			  <a href="thief_life"><u>Восстановить</u></a><br/>
			  <img src="images/lvl.png" alt="" title="Уровень"/> Воровство: ' . $in['practice'] . '%
			  <a href="thief_up"><u>Повысить</u></a><br/>';
	}
	echo '<br/><a href="upgrade'.$id_build['id'].'">Вернуться</a>';
break;

case thief_up:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '13' AND user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
	if (!isset($id_build['id']))
	{
		err('У тебя не построено здание Цитадель!');
		include 'sys/foot.php';
		exit();
	}
	$available = mysql_query("SELECT * FROM thiefs WHERE player = '$user[id]' AND timeleft < '$rtime' LIMIT 1");
	if (mysql_num_rows($available) == false)
	{
		echo 'В резерве нет вора. Найми его в Цитаделе.<br/>';
	}
	else
	{
		$in = mysql_fetch_assoc($available);
		$cfg = mysql_fetch_assoc(mysql_query("SELECT thief_percent FROM config WHERE id = '1' LIMIT 1"));

		if ($in['practice'] == 100)
		{
			err('Параметр воровства достигнут максимальной отметки!');
			include 'sys/foot.php';
			exit();
		}

		if (isset($_POST['ok']))
		{
			$num = number($_POST['num']);
			if ($num > 100 || ($in['practice'] + $num) > 100)
			{
				err('Параметр воровства не может превышать 100% !');
			}
			elseif ($user['crystal'] < ($num * $cfg['thief_percent']))
			{
				err('Тебе нехватает ' . ($num * $cfg['thief_percent'] - $user['crystal']) . ' <img src="images/crystal.png" title="Кристаллы" alt=""/>');
			}
			else
			{
				mysql_query("UPDATE users SET crystal = crystal - '" . ($num * $cfg['thief_percent']) . "' WHERE id = '$user[id]' LIMIT 1");
				mysql_query("UPDATE thiefs SET practice = practice + '$num' WHERE player = '$user[id]' AND timeleft < '$rtime' LIMIT 1");
				msg('Параметр воровства был повышен на ' . $num . '%');
			}
		}

		echo '<form method="post" action="thief_up">
			  Текущий параметр воровства: ' . $in['practice'] . '%<br/>
			  На сколько % повысить: (стоимость 1% = ' . $cfg['thief_percent'] . ' <img src="images/crystal.png" title="Кристаллы" alt=""/>)
			  <br/>
			  <input type="text" size="4" name="num"/>
			  <br/>
			  <input type="submit" name="ok" value="Повысить"/>
			  </form>
			  <a href="thiefs">Вернуться</a>';
	}
break;

case thief_life:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '13' AND user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
	if (!isset($id_build['id']))
	{
		err('У тебя не построено здание Бастион!');
		include 'sys/foot.php';
		exit();
	}
	$available = mysql_query("SELECT * FROM thiefs WHERE player = '$user[id]' AND timeleft < '$rtime' LIMIT 1");
	if (mysql_num_rows($available) == false)
	{
		echo 'В резерве нет вора. Найми его в Цитаделе.<br/>';
	}
	else
	{
		$in = mysql_fetch_assoc($available);
		$cfg = mysql_fetch_assoc(mysql_query("SELECT thief_recovery FROM config WHERE id = '1' LIMIT 1"));
		$price = round($cfg['thief_recovery'] / 200 * (200 - $in['health']));

	if ($in['health'] == 200)
	{
		err('У вора полное здоровье!');
	}
	else
	{
		if (isset($_GET['recovery']))
		{
			if ($user['crystal'] < $price)
			{
				err('У тебя нехватает: ' . ($price - $user['crystal']) . ' <img src="images/crystal.png" title="Кристаллы" alt=""/>');
			}
			elseif ($in['time_recovery'] != 0)
			{
				err('Вор уже восстанавливается!');
			}
			else
			{
				mysql_query("UPDATE users SET crystal = crystal - '$price' WHERE id = '$user[id]' LIMIT 1");
				mysql_query("UPDATE thiefs SET time_recovery = '$rtime' WHERE player = '$user[id]' LIMIT 1");
				msg('Восстановление началось.');
			}
		}

		if ($in['health'] < 200 && $in['time_recovery'] != 0)
		{
			$ONL = round(($rtime - $in['time_recovery']) / 20);
			$rec = round(2 * 100 / 100);

			echo 'Идет восстановление...' . separator;
			mysql_query("UPDATE thiefs SET time_recovery = '$rtime', health = '" . plus($in['health'], isset($ONL) ? ($ONL * $rec) : $rec, 200) . "' WHERE player = '$user[id]' LIMIT 1");
		}
		elseif ($in['health'] == 200 && $in['time_recovery'] != 0)
		{
			mysql_query("UPDATE thiefs SET time_recovery = '0' WHERE player = '$user[id]' LIMIT 1");
		}

		echo '<img src="images/small_life.png" alt="small"/> <a href="thief_life&amp;recovery"><u>Восстановить</u></a><br/>
			  Требуется:  ' . $cfg['thief_recovery'] . ' <img src="images/crystal.png" title="Кристаллы" alt=""/><br/>';
	}
	}
	echo '<br/><a href="thiefs">Вернуться</a>';
break;

case scouts:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '13' AND user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
	if (!isset($id_build['id']))
	{
		err('У тебя не построено здание Бастион!');
		include 'sys/foot.php';
		exit();
	}

	$available = mysql_query("SELECT * FROM scouts WHERE player = '$user[id]' AND timeleft < '$rtime'");
	if (mysql_num_rows($available) == false)
	{
		echo 'В резерве нет шпиона. Найми его в Цитаделе.<br/>';
	}
	else
	{
		$in = mysql_fetch_assoc($available);

		if ($in['health'] < 200 && $in['time_recovery'] != 0)
		{
			$ONL = round(($rtime - $in['time_recovery']) / 20);
			$rec = round(2 * 100 / 100);

			echo 'Идет восстановление...' . separator;
			mysql_query("UPDATE scouts SET time_recovery = '$rtime', health = '" . plus($in['health'], isset($ONL) ? ($ONL * $rec) : $rec, 200) . "' WHERE player = '$user[id]' LIMIT 1");
		}
		elseif ($in['health'] == 200 && $in['time_recovery'] != 0)
		{
			mysql_query("UPDATE scouts SET time_recovery = '0' WHERE player = '$user[id]' LIMIT 1");
		}

		echo '<img src="images/scout.png" alt=""/>
			  <br/>
			  <b>Шпион</b><br/>
			  <img src="images/hp.png" alt="" title="Здоровье"/> Здоровье: ' . $in['health'] . ' / 200
			  <a href="scout_life"><u>Восстановить</u></a><br/>
			  <img src="images/lvl.png" alt="" title="Уровень"/> Шпионаж: ' . $in['practice'] . '%
			  <a href="scout_up"><u>Повысить</u></a><br/>';
	}
	echo '<br/><a href="upgrade'.$id_build['id'].'">Вернуться</a>';
break;

case scout_up:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '13' AND user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
	if (!isset($id_build['id']))
	{
		err('У тебя не построено здание Бастион!');
		include 'sys/foot.php';
		exit();
	}

	$available = mysql_query("SELECT * FROM scouts WHERE player = '$user[id]' AND timeleft < '$rtime' LIMIT 1");
	if (mysql_num_rows($available) == false)
	{
		echo 'В резерве нет шпиона. Найми его в Цитаделе.<br/>';
	}
	else
	{
		$in = mysql_fetch_assoc($available);
		$cfg = mysql_fetch_assoc(mysql_query("SELECT scout_percent FROM config WHERE id = '1' LIMIT 1"));

		if ($in['practice'] == 100)
		{
			err('Параметр шпионажа достигнут максимальной отметки!');
			include 'sys/foot.php';
			exit();
		}

		if (isset($_POST['ok']))
		{
			$num = number($_POST['num']);
			if ($num > 100 || ($in['practice'] + $num) > 100)
			{
				err('Параметр шпионажа не может превышать 100% !');
			}
			elseif ($user['crystal'] < ($num * $cfg['scout_percent']))
			{
				err('Тебе нехватает ' . ($num * $cfg['scout_percent'] - $user['crystal']) . ' <img src="images/crystal.png" title="Кристаллы" alt=""/>');
			}
			else
			{
				mysql_query("UPDATE users SET crystal = crystal - '" . ($num * $cfg['scout_percent']) . "' WHERE id = '$user[id]' LIMIT 1");
				mysql_query("UPDATE scouts SET practice = practice + '$num' WHERE player = '$user[id]' AND timeleft < '$rtime' LIMIT 1");
				msg('Параметр шпионажа был повышен на ' . $num . '%');
			}
		}

		echo '<form method="post" action="scout_up">
			  Текущий параметр шпионажа: ' . $in['practice'] . '%<br/>
			  На сколько % повысить: (стоимость 1% = ' . $cfg['scout_percent'] . ' <img src="images/crystal.png" title="Кристаллы" alt=""/>)
			  <br/>
			  <input type="text" size="4" name="num"/>
			  <br/>
			  <input type="submit" name="ok" value="Повысить"/>
			  </form>
			  <a href="scouts">Вернуться</a>';
	}
break;

case scout_life:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '13' AND user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
	if (!isset($id_build['id']))
	{
		err('У тебя не построено здание Бастион!');
		include 'sys/foot.php';
		exit();
	}

	$available = mysql_query("SELECT * FROM scouts WHERE player = '$user[id]' AND timeleft < '$rtime' LIMIT 1");
	if (mysql_num_rows($available) == false)
	{
		echo 'В резерве нет шпиона. Найми его в Цитаделе.<br/>';
	}
	else
	{
		$in = mysql_fetch_assoc($available);
		$cfg = mysql_fetch_assoc(mysql_query("SELECT scout_recovery FROM config WHERE id = '1' LIMIT 1"));
		$price = round($cfg['scout_recovery'] / 200 * (200 - $in['health']));

	if ($in['health'] == 200)
	{
		err('У шпиона полное здоровье!');
	}
	else
	{
		if (isset($_GET['recovery']))
		{
			if ($user['crystal'] < $price)
			{
				err('У тебя нехватает: ' . ($price - $user['crystal']) . ' <img src="images/crystal.png" title="Кристаллы" alt=""/>');
			}
			elseif ($in['time_recovery'] != 0)
			{
				err('Шпион уже восстанавливается!');
			}
			else
			{
				mysql_query("UPDATE users SET crystal = crystal - '$price' WHERE id = '$user[id]' LIMIT 1");
				mysql_query("UPDATE scouts SET time_recovery = '$rtime' WHERE player = '$user[id]' LIMIT 1");
				msg('Восстановление началось.');
			}
		}

		if ($in['health'] < 200 && $in['time_recovery'] != 0)
		{
			$ONL = round(($rtime - $in['time_recovery']) / 20);
			$rec = round(2 * 100 / 100);

			echo 'Идет восстановление...' . separator;
			mysql_query("UPDATE scouts SET time_recovery = '$rtime', health = '" . plus($in['health'], isset($ONL) ? ($ONL * $rec) : $rec, 200) . "' WHERE player = '$user[id]' LIMIT 1");
		}
		elseif ($in['health'] == 200 && $in['time_recovery'] != 0)
		{
			mysql_query("UPDATE scouts SET time_recovery = '0' WHERE player = '$user[id]' LIMIT 1");
		}

		echo '<img src="images/small_life.png" alt="small"/> <a href="scout_life&amp;recovery"><u>Восстановить</u></a><br/>
			  Требуется:  ' . $cfg['scout_recovery'] . ' <img src="images/crystal.png" title="Кристаллы" alt=""/><br/>';
	}
	}
	echo '<br/><a href="scouts">Вернуться</a>';
break;

case saboteurs:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '13' AND user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
	if (!isset($id_build['id']))
	{
		err('У тебя не построено здание Бастион!');
		include 'sys/foot.php';
		exit();
	}

	$available = mysql_query("SELECT * FROM saboteurs WHERE player = '$user[id]' AND timeleft < '$rtime'");
	if (mysql_num_rows($available) == false)
	{
		echo 'В резерве нет саботажника. Найми его в Цитаделе.<br/>';
	}
	else
	{
		$in = mysql_fetch_assoc($available);

		if ($in['health'] < 200 && $in['time_recovery'] != 0)
		{
			$ONL = round(($rtime - $in['time_recovery']) / 20);
			$rec = round(2 * 100 / 100);

			echo 'Идет восстановление...' . separator;
			mysql_query("UPDATE saboteurs SET time_recovery = '$rtime', health = '" . plus($in['health'], isset($ONL) ? ($ONL * $rec) : $rec, 200) . "' WHERE player = '$user[id]' LIMIT 1");
		}
		elseif ($in['health'] == 200 && $in['time_recovery'] != 0)
		{
			mysql_query("UPDATE saboteurs SET time_recovery = '0' WHERE player = '$user[id]' LIMIT 1");
		}

		echo '<img src="images/saboteur.png" alt=""/>
			  <br/>
			  <b>Саботажник</b><br/>
			  <img src="images/hp.png" alt="" title="Здоровье"/> Здоровье: ' . $in['health'] . ' / 200
			  <a href="saboteur_life"><u>Восстановить</u></a><br/>
			  <img src="images/lvl.png" alt="" title="Уровень"/> Саботажничество: ' . $in['practice'] . '%
			  <a href="saboteur_up"><u>Повысить</u></a><br/>';
	}
	echo '<br/><a href="upgrade'.$id_build['id'].'">Вернуться</a>';
break;

case saboteur_up:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '13' AND user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
	if (!isset($id_build['id']))
	{
		err('У тебя не построено здание Бастион!');
		include 'sys/foot.php';
		exit();
	}

	$available = mysql_query("SELECT * FROM saboteurs WHERE player = '$user[id]' AND timeleft < '$rtime' LIMIT 1");
	if (mysql_num_rows($available) == false)
	{
		echo 'В резерве нет саботажника. Найми его в Цитаделе.<br/>';
	}
	else
	{
		$in = mysql_fetch_assoc($available);
		$cfg = mysql_fetch_assoc(mysql_query("SELECT saboteur_percent FROM config WHERE id = '1' LIMIT 1"));

		if ($in['practice'] == 100)
		{
			err('Параметр саботажничества достигнут максимальной отметки!');
			include 'sys/foot.php';
			exit();
		}

		if (isset($_POST['ok']))
		{
			$num = number($_POST['num']);
			if ($num > 100 || ($in['practice'] + $num) > 100)
			{
				err('Параметр саботажничества не может превышать 100% !');
			}
			elseif ($user['crystal'] < ($num * $cfg['saboteur_percent']))
			{
				err('Тебе нехватает ' . ($num * $cfg['saboteur_percent'] - $user['crystal']) . ' <img src="images/crystal.png" title="Кристаллы" alt=""/>');
			}
			else
			{
				mysql_query("UPDATE users SET crystal = crystal - '" . ($num * $cfg['saboteur_percent']) . "' WHERE id = '$user[id]' LIMIT 1");
				mysql_query("UPDATE saboteurs SET practice = practice + '$num' WHERE player = '$user[id]' AND timeleft < '$rtime' LIMIT 1");
				msg('Параметр саботажничества был повышен на ' . $num . '%');
			}
		}

		echo '<form method="post" action="saboteur_up">
			  Текущий параметр саботажничества: ' . $in['practice'] . '%<br/>
			  На сколько % повысить: (стоимость 1% = ' . $cfg['saboteur_percent'] . ' <img src="images/crystal.png" title="Кристаллы" alt=""/>)
			  <br/>
			  <input type="text" size="4" name="num"/>
			  <br/>
			  <input type="submit" name="ok" value="Повысить"/>
			  </form>
			  <a href="saboteurs">Вернуться</a>';
	}
break;

case saboteur_life:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '13' AND user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
	if (!isset($id_build['id']))
	{
		err('У тебя не построено здание Бастион!');
		include 'sys/foot.php';
		exit();
	}

	$available = mysql_query("SELECT * FROM saboteurs WHERE player = '$user[id]' AND timeleft < '$rtime' LIMIT 1");
	if (mysql_num_rows($available) == false)
	{
		echo 'В резерве нет саботажника. Найми его в Цитаделе.<br/>';
	}
	else
	{
		$in = mysql_fetch_assoc($available);
		$cfg = mysql_fetch_assoc(mysql_query("SELECT saboteur_recovery FROM config WHERE id = '1' LIMIT 1"));
		$price = round($cfg['saboteur_recovery'] / 200 * (200 - $in['health']));

	if ($in['health'] == 200)
	{
		err('У саботажника полное здоровье!');
	}
	else
	{
		if (isset($_GET['recovery']))
		{
			if ($user['crystal'] < $price)
			{
				err('У тебя нехватает: ' . ($price - $user['crystal']) . ' <img src="images/crystal.png" title="Кристаллы" alt=""/>');
			}
			elseif ($in['time_recovery'] != 0)
			{
				err('Саботажник уже восстанавливается!');
			}
			else
			{
				mysql_query("UPDATE users SET crystal = crystal - '$price' WHERE id = '$user[id]' LIMIT 1");
				mysql_query("UPDATE saboteurs SET time_recovery = '$rtime' WHERE player = '$user[id]' LIMIT 1");
				msg('Восстановление началось.');
			}
		}

		if ($in['health'] < 200 && $in['time_recovery'] != 0)
		{
			$ONL = round(($rtime - $in['time_recovery']) / 20);
			$rec = round(2 * 100 / 100);

			echo 'Идет восстановление...' . separator;
			mysql_query("UPDATE saboteurs SET time_recovery = '$rtime', health = '" . plus($in['health'], isset($ONL) ? ($ONL * $rec) : $rec, 200) . "' WHERE player = '$user[id]' LIMIT 1");
		}
		elseif ($in['health'] == 200 && $in['time_recovery'] != 0)
		{
			mysql_query("UPDATE saboteurs SET time_recovery = '0' WHERE player = '$user[id]' LIMIT 1");
		}

		echo '<img src="images/small_life.png" alt="small"/> <a href="saboteur_life&amp;recovery"><u>Восстановить</u></a><br/>
			  Требуется:  ' . $cfg['saboteur_recovery'] . ' <img src="images/crystal.png" title="Кристаллы" alt=""/><br/>';
	}
	}
	echo '<br/><a href="saboteurs">Вернуться</a>';
break;

case recruiters:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '13' AND user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
	if (!isset($id_build['id']))
	{
		err('У тебя не построено здание Бастион!');
		include 'sys/foot.php';
		exit();
	}

	$available = mysql_query("SELECT * FROM recruiters WHERE player = '$user[id]' AND timeleft < '$rtime'");
	if (mysql_num_rows($available) == false)
	{
		echo 'В резерве нет вербовщика. Найми его в Цитаделе.<br/>';
	}
	else
	{
		$in = mysql_fetch_assoc($available);

		if ($in['health'] < 200 && $in['time_recovery'] != 0)
		{
			$ONL = round(($rtime - $in['time_recovery']) / 20);
			$rec = round(2 * 100 / 100);

			echo 'Идет восстановление...' . separator;
			mysql_query("UPDATE recruiters SET time_recovery = '$rtime', health = '" . plus($in['health'], isset($ONL) ? ($ONL * $rec) : $rec, 200) . "' WHERE player = '$user[id]' LIMIT 1");
		}
		elseif ($in['health'] == 200 && $in['time_recovery'] != 0)
		{
			mysql_query("UPDATE recruiters SET time_recovery = '0' WHERE player = '$user[id]' LIMIT 1");
		}

		echo '<img src="images/recruiter.png" alt=""/>
			  <br/>
			  <b>Вербовщик</b><br/>
			  <img src="images/hp.png" alt="" title="Здоровье"/> Здоровье: ' . $in['health'] . ' / 200
			  <a href="recruiter_life"><u>Восстановить</u></a><br/>
			  <img src="images/lvl.png" alt="" title="Уровень"/> Вербовничество: ' . $in['practice'] . '%
			  <a href="recruiter_up"><u>Повысить</u></a><br/>';
	}
	echo '<br/><a href="upgrade'.$id_build['id'].'">Вернуться</a>';
break;

case recruiter_up:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '13' AND user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
	if (!isset($id_build['id']))
	{
		err('У тебя не построено здание Бастион!');
		include 'sys/foot.php';
		exit();
	}

	$available = mysql_query("SELECT * FROM recruiters WHERE player = '$user[id]' AND timeleft < '$rtime' LIMIT 1");
	if (mysql_num_rows($available) == false)
	{
		echo 'В резерве нет вербовщика. Найми его в Цитаделе.<br/>';
	}
	else
	{
		$in = mysql_fetch_assoc($available);
		$cfg = mysql_fetch_assoc(mysql_query("SELECT recruiter_percent FROM config WHERE id = '1' LIMIT 1"));

		if ($in['practice'] == 100)
		{
			err('Параметр вербовничества достигнут максимальной отметки!');
			include 'sys/foot.php';
			exit();
		}

		if (isset($_POST['ok']))
		{
			$num = number($_POST['num']);
			if ($num > 100 || ($in['practice'] + $num) > 100)
			{
				err('Параметр вербовничества не может превышать 100% !');
			}
			elseif ($user['crystal'] < ($num * $cfg['recruiter_percent']))
			{
				err('Тебе нехватает ' . ($num * $cfg['recruiter_percent'] - $user['crystal']) . ' <img src="images/crystal.png" title="Кристаллы" alt=""/>');
			}
			else
			{
				mysql_query("UPDATE users SET crystal = crystal - '" . ($num * $cfg['recruiter_percent']) . "' WHERE id = '$user[id]' LIMIT 1");
				mysql_query("UPDATE recruiters SET practice = practice + '$num' WHERE player = '$user[id]' AND timeleft < '$rtime' LIMIT 1");
				msg('Параметр вербовничества был повышен на ' . $num . '%');
			}
		}

		echo '<form method="post" action="recruiter_up">
			  Текущий параметр вербовничества: ' . $in['practice'] . '%<br/>
			  На сколько % повысить: (стоимость 1% = ' . $cfg['recruiter_percent'] . ' <img src="images/crystal.png" title="Кристаллы" alt=""/>)
			  <br/>
			  <input type="text" size="4" name="num"/>
			  <br/>
			  <input type="submit" name="ok" value="Повысить"/>
			  </form>
			  <a href="recruiters">Вернуться</a>';
	}
break;

case recruiter_life:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '13' AND user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
	if (!isset($id_build['id']))
	{
		err('У тебя не построено здание Бастион!');
		include 'sys/foot.php';
		exit();
	}

	$available = mysql_query("SELECT * FROM recruiters WHERE player = '$user[id]' AND timeleft < '$rtime' LIMIT 1");
	if (mysql_num_rows($available) == false)
	{
		echo 'В резерве нет вербовщика. Найми его в Цитаделе.<br/>';
	}
	else
	{
		$in = mysql_fetch_assoc($available);
		$cfg = mysql_fetch_assoc(mysql_query("SELECT recruiter_recovery FROM config WHERE id = '1' LIMIT 1"));
		$price = round($cfg['recruiter_recovery'] / 200 * (200 - $in['health']));

	if ($in['health'] == 200)
	{
		err('У вербовщика полное здоровье!');
	}
	else
	{
		if (isset($_GET['recovery']))
		{
			if ($user['crystal'] < $price)
			{
				err('У тебя нехватает: ' . ($price - $user['crystal']) . ' <img src="images/crystal.png" title="Кристаллы" alt=""/>');
			}
			elseif ($in['time_recovery'] != 0)
			{
				err('Вербовщик уже восстанавливается!');
			}
			else
			{
				mysql_query("UPDATE users SET crystal = crystal - '$price' WHERE id = '$user[id]' LIMIT 1");
				mysql_query("UPDATE recruiters SET time_recovery = '$rtime' WHERE player = '$user[id]' LIMIT 1");
				msg('Восстановление началось.');
			}
		}

		if ($in['health'] < 200 && $in['time_recovery'] != 0)
		{
			$ONL = round(($rtime - $in['time_recovery']) / 20);
			$rec = round(2 * 100 / 100);

			echo 'Идет восстановление...' . separator;
			mysql_query("UPDATE recruiters SET time_recovery = '$rtime', health = '" . plus($in['health'], isset($ONL) ? ($ONL * $rec) : $rec, 200) . "' WHERE player = '$user[id]' LIMIT 1");
		}
		elseif ($in['health'] == 200 && $in['time_recovery'] != 0)
		{
			mysql_query("UPDATE recruiters SET time_recovery = '0' WHERE player = '$user[id]' LIMIT 1");
		}

		echo '<img src="images/small_life.png" alt="small"/> <a href="recruiter_life&amp;recovery"><u>Восстановить</u></a><br/>
			  Требуется:  ' . $cfg['recruiter_recovery'] . ' <img src="images/crystal.png" title="Кристаллы" alt=""/><br/>';
	}
	}
	echo '<br/><a href="recruiters">Вернуться</a>';
break;

case recruiter_up:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '13' AND user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
	if (!isset($id_build['id']))
	{
		err('У тебя не построено здание Бастион!');
		include 'sys/foot.php';
		exit();
	}

	$available = mysql_query("SELECT * FROM recruiters WHERE player = '$user[id]' AND timeleft < '$rtime' LIMIT 1");
	if (mysql_num_rows($available) == false)
	{
		echo 'В резерве нет вербовщика. Найми его в Цитаделе.<br/>';
	}
	else
	{
		$in = mysql_fetch_assoc($available);
		$cfg = mysql_fetch_assoc(mysql_query("SELECT recruiter_percent FROM config WHERE id = '1' LIMIT 1"));

		if ($in['practice'] == 100)
		{
			err('Параметр вербовничества достигнут максимальной отметки!');
			include 'sys/foot.php';
			exit();
		}

		if (isset($_POST['ok']))
		{
			$num = number($_POST['num']);
			if ($num > 100 || ($in['practice'] + $num) > 100)
			{
				err('Параметр вербовничества не может превышать 100% !');
			}
			elseif ($user['crystal'] < ($num * $cfg['recruiter_percent']))
			{
				err('Тебе нехватает ' . ($num * $cfg['recruiter_percent'] - $user['crystal']) . ' <img src="images/crystal.png" title="Кристаллы" alt=""/>');
			}
			else
			{
				mysql_query("UPDATE users SET crystal = crystal - '" . ($num * $cfg['recruiter_percent']) . "' WHERE id = '$user[id]' LIMIT 1");
				mysql_query("UPDATE recruiters SET practice = practice + '$num' WHERE player = '$user[id]' AND timeleft < '$rtime' LIMIT 1");
				msg('Параметр вербовничества был повышен на ' . $num . '%');
			}
		}

		echo '<form method="post" action="recruiter_up">
			  Текущий параметр вербовничества: ' . $in['practice'] . '%<br/>
			  На сколько % повысить: (стоимость 1% = ' . $cfg['recruiter_percent'] . ' <img src="images/crystal.png" title="Кристаллы" alt=""/>)
			  <br/>
			  <input type="text" size="4" name="num"/>
			  <br/>
			  <input type="submit" name="ok" value="Повысить"/>
			  </form>
			  <a href="recruiters">Вернуться</a>';
	}
break;

case recruiter_life:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '13' AND user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
	if (!isset($id_build['id']))
	{
		err('У тебя не построено здание Бастион!');
		include 'sys/foot.php';
		exit();
	}

	$available = mysql_query("SELECT * FROM recruiters WHERE player = '$user[id]' AND timeleft < '$rtime' LIMIT 1");
	if (mysql_num_rows($available) == false)
	{
		echo 'В резерве нет вербовщика. Найми его в Цитаделе.<br/>';
	}
	else
	{
		$in = mysql_fetch_assoc($available);
		$cfg = mysql_fetch_assoc(mysql_query("SELECT recruiter_recovery FROM config WHERE id = '1' LIMIT 1"));
		$price = round($cfg['recruiter_recovery'] / 200 * (200 - $in['health']));

	if ($in['health'] == 200)
	{
		err('У вербовщика полное здоровье!');
	}
	else
	{
		if (isset($_GET['recovery']))
		{
			if ($user['crystal'] < $price)
			{
				err('У тебя нехватает: ' . ($price - $user['crystal']) . ' <img src="images/crystal.png" title="Кристаллы" alt=""/>');
			}
			elseif ($in['time_recovery'] != 0)
			{
				err('Вербовщик уже восстанавливается!');
			}
			else
			{
				mysql_query("UPDATE users SET crystal = crystal - '$price' WHERE id = '$user[id]' LIMIT 1");
				mysql_query("UPDATE recruiters SET time_recovery = '$rtime' WHERE player = '$user[id]' LIMIT 1");
				msg('Восстановление началось.');
			}
		}

		if ($in['health'] < 200 && $in['time_recovery'] != 0)
		{
			$ONL = round(($rtime - $in['time_recovery']) / 20);
			$rec = round(2 * 100 / 100);

			echo 'Идет восстановление...' . separator;
			mysql_query("UPDATE recruiters SET time_recovery = '$rtime', health = '" . plus($in['health'], isset($ONL) ? ($ONL * $rec) : $rec, 200) . "' WHERE player = '$user[id]' LIMIT 1");
		}
		elseif ($in['health'] == 200 && $in['time_recovery'] != 0)
		{
			mysql_query("UPDATE recruiters SET time_recovery = '0' WHERE player = '$user[id]' LIMIT 1");
		}

		echo '<img src="images/small_life.png" alt="small"/> <a href="recruiter_life&amp;recovery"><u>Восстановить</u></a><br/>
			  Требуется:  ' . $cfg['recruiter_recovery'] . ' <img src="images/crystal.png" title="Кристаллы" alt=""/><br/>';
	}
	}
	echo '<br/><a href="recruiters">Вернуться</a>';
break;

case general_control:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '13' AND user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
	if (!isset($id_build['id']))
	{
		err('У тебя не построено здание Цитадель!');
		include 'sys/foot.php';
		exit();
	}

	$id = number($_GET['id']);
	$slct = mysql_query("SELECT * FROM generals WHERE id = '$id' AND player = '$user[id]' AND timeleft < '$rtime'");
	if (mysql_num_rows($slct) == false)
	{
		err('Ошибка!');
	}
	else
	{
		$in = mysql_fetch_assoc($slct);
		echo '<img src="images/general.png" alt=""/>
			  <br/>';

		//------------- переименование генерала -------------//
		if (isset($_POST['savename']))
		{
			$newname = trim(mysql_real_escape_string(char($_POST['newname'], 0)));
			mysql_query("UPDATE generals SET name = '$newname' WHERE player = '$user[id]' AND id = '$id' LIMIT 1");
			header('location: general_control' . $id);
		}
		if (isset($_GET['name']))
		{
			echo separator . '<form method="post" action="general_control'.$id.'">
				  Переименовать генерала:
				  <br/>
				  <input type="text" name="newname" value="' . $in['name'] . '"/>
				  <br/>
				  <input type="submit" name="savename" value="Сохранить"/>
				  <a href="general_control'.$id.'"><u>Отмена</u></a>
				  </form>- - -<br/>';
		}
		else
		{
			echo (empty($in['name']) ? 'Генерал (Без имени)' : 'Генерал <b>' . $in['name'] . '</b>') . ' ' . ($in['health'] == 0 ? '<span style="color:#FF3030;">(Мертв)</span>' : '') . '<br/>
				  &#8226; <a href="general_control'.$id.'&amp;name"><u>Переименовать</u></a><br/>';
		}
		//------------- переименование генерала -------------//
		//------------- возглавление армии -------------//
		if (isset($_POST['head']))
		{
			$Army = number($_POST['Army']);
			$tst = mysql_query("SELECT id, time_run, time_back FROM duty WHERE user = '$user[id]' AND id = '$Army' AND timeleft < '$rtime' AND done = '0' LIMIT 1");
			if (mysql_num_rows($tst) == false && $Army != 0)
			{
				err('Легиона не существует!');
			}
			else
			{
				$INA = mysql_fetch_assoc($tst);
				$qq = mysql_query("SELECT id, health FROM generals WHERE player = '$user[id]' $_sql LIMIT 1");
				$is = mysql_fetch_assoc($qq);
				if ($INA['time_run'] > $rtime || $INA['time_back'] > $rtime)
				{
					err('Этот генерал сейчас в походе с армией!');
				}
				elseif ($is['health'] == 0)
				{
					err('Этот генерал мертв и не может возглавить легион!');
				}
				else
				{
					mysql_query("UPDATE generals SET cid = '$Army' WHERE player = '$user[id]' AND id = '$id' LIMIT 1");
					header('location: general_control' . $id);
				}
			}
		}
		if (isset($_GET['head']))
		{
			$arm = mysql_query("SELECT * FROM duty WHERE user = '$user[id]' AND timeleft < '$rtime' AND done = '0' AND id NOT IN(SELECT cid FROM generals WHERE player = '$user[id]' AND timeleft < '$rtime')");
			echo separator . '<form method="post" action="general_control'.$id.'">
				  Какую армию возглавить:
				  <br/>
				  <select name="Army">
				  <option value="0">-снять с руководства-</option>';
			while($ar = mysql_fetch_assoc($arm))
			{
				echo '<option value="' . $ar['id'] . '">' . $ar['name'] . '</option>';
			}
			echo '</select>
				  <br/>
				  <input type="submit" name="head" value="Сохранить"/>
				  <a href="general_control'.$id.'"><u>Отмена</u></a>
				  </form>- - -<br/>';
		}
		else
		{
			echo '&#8226; <a href="general_control'.$id.'&amp;head"><u>Возглавить армию</u></a><br/>';
		}
		echo '&#8226; <a href="general_recovery'.$id.'"><u>' . ($in['health'] == 0 ? 'Воскресить' : 'Лечить') . '</u></a>' . separator;
		//------------- возглавление армии -------------//
		//------------- распределение параметров -------------//
		if (isset($_GET['param']))
		{
			$param = htmlspecialchars($_GET['param']);
			if ($in['free_params'] == 0)
			{
				err('Не осталось свободных очков!');
			}
			else
			{
				if ($param == 'attack')
				{
					mysql_query("UPDATE generals SET attack = attack + '1', free_params = free_params - '1' WHERE player = '$user[id]' AND id = '$id' LIMIT 1");
				}
				if ($param == 'defence')
				{
					mysql_query("UPDATE generals SET defence = defence + '1', free_params = free_params - '1' WHERE player = '$user[id]' AND id = '$id' LIMIT 1");
				}
				if ($param == 'career')
				{
					mysql_query("UPDATE generals SET career = career + '1', free_params = free_params - '1' WHERE player = '$user[id]' AND id = '$id' LIMIT 1");
				}
				if ($param == 'recovery')
				{
					mysql_query("UPDATE generals SET recovery = recovery + '1', free_params = free_params - '1' WHERE player = '$user[id]' AND id = '$id' LIMIT 1");
				}
				header('location: general_control' . $id);
			}
		}
		//------------- распределение параметров -------------//

		// восстановление здоровья
		if ($in['health'] < 200 && $in['time_recovery'] != 0 && round(($rtime - $in['time_recovery']) / 20) > 0)
		{
			$ONL = round(($rtime - $in['time_recovery']) / 20);
			$rec = round(2 + ($in['level'] * $in['recovery']) * 100 / 100);
			mysql_query("UPDATE generals SET time_recovery = '$rtime', health = '" . plus($in['health'], isset($ONL) ? ($ONL * $rec) : $rec, 200) . "' WHERE id = '$id' AND player = '$user[id]' LIMIT 1");
		}
		elseif ($in['time_recovery'] != 0 && $in['health'] == 200)
		{
			mysql_query("UPDATE generals SET time_recovery = '0' WHERE id = '$id' AND player = '$user[id]' LIMIT 1");
		}
		// восстановление здоровья

			$whyArm = mysql_fetch_assoc(mysql_query("SELECT id, name FROM duty WHERE id = '$in[cid]' AND user = '$user[id]' AND timeleft < '$rtime' AND done = '0' LIMIT 1"));
			echo '<img src="images/ranks.png" alt=""/> Возглавляет армию:
			' . ($in['cid'] != 0 && isset($whyArm['id']) ? '<a href="army'.$whyArm['id'].'"><u>' . $whyArm['name'] . '</u></a>' : '(Нет)') . '
			<br/>
			<img src="images/lvl.png" alt="" title="Уровень"/> Уровень: ' . $in['level'] . ',
			 	 ' . ($in['level'] < 20 ? 'до ' . ($in['level'] + 1) . '-го ур. осталось ' . number_format(compute_params($in['id'], 1)) . ' опыта.' : '') . '
			  <br/>
			  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . rating($in['id'], $in['practice']) . '
			  <br/>
			  <img src="images/hp.png" alt="" title="Здоровье"/> Здоровье: ' . $in['health'] . ' / 200<br/>
			  <img src="images/attack.png" alt="" title="Атака"/> Командование атакой: ' . $in['attack'] . ' <a href="param_up_attack'.$id.'">[+]</a><br/>
		  	  <img src="images/defence.png" alt="" title="Защита"/> Командование защитой: ' . $in['defence'] . ' <a href="param_up_defence'.$id.'">[+]</a><br/>
		  	  <img src="images/career.png" alt="" title="Карьера"/> Карьера: ' . $in['career'] . ' <a href="param_up_career'.$id.'">[+]</a><br/>
		 	  <img src="images/recovery.png" alt="" title="Медицина"/> Медицина: ' . $in['recovery'] . ' <a href="param_up_recovery'.$id.'">[+]</a><br/>- - -<br/>
			  Свободных очков: ' . $in['free_params'] . '<br/>';
	}
	echo '<br/><a href="generals">Вернуться</a>';
break;

case general_recovery:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '13' AND user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
	if (!isset($id_build['id']))
	{
		err('У тебя не построено здание Цитадель!');
		include 'sys/foot.php';
		exit();
	}
	$id = number($_GET['id']);
	$emp = mysql_query("SELECT * FROM generals WHERE id = '$id' AND player = '$user[id]' LIMIT 1");
	$in = mysql_fetch_assoc($emp);
	$cfg = mysql_fetch_assoc(mysql_query("SELECT res, crystal FROM config WHERE id = '1' LIMIT 1"));
	$price = round($cfg['res'] / 200 * (200 - $in['health']));

	if (mysql_num_rows($emp) == false)
	{
		err('У тебя нет такого генерала!');
	}
	elseif ($in['health'] == 200)
	{
		err('У этого генерала полное здоровье!');
	}
	else
	{
		if (isset($_GET['normal']))
		{
			if ($user['tree'] < $price) $short[] .= ' <img src="/images/res.2.png" alt="" title="Дерево"/>' . ($price - $user['tree']);
			if ($user['food'] < $price) $short[] .= ' <img src="/images/res.1.png" alt="" title="Еда"/>' . ($price - $user['food']);
			if ($user['stone'] < $price) $short[] .= ' <img src="/images/res.3.png" alt="" title="Камень"/>' . ($price - $user['stone']);
			if ($user['ore'] < $price) $short[] .= ' <img src="/images/res.4.png" alt="" title="Руда"/>' . ($price - $user['ore']);
			if ($user['gold'] < $price) $short[] .= ' <img src="/images/res.5.png" alt="" title="Золото"/>' . ($price - $user['gold']);

			if (!empty($short))
			{
				err('У тебя нехватает: ' . implode(', ', $short));
			}
			elseif ($in['time_recovery'] != 0)
			{
				err('Этот генерал уже восстанавливается!');
			}
			else
			{
				mysql_query("UPDATE users SET
				food = food - '$price',
				tree = tree - '$price',
				stone = stone - '$price',
				ore = ore - '$price',
				gold = gold - '$price'
				WHERE id = '$user[id]' LIMIT 1");

				mysql_query("UPDATE generals SET time_recovery = '$rtime' WHERE id = '$id' AND player = '$user[id]' LIMIT 1");
				msg('Восстановление началось.');
			}
		}

		if (isset($_GET['quick']))
		{
			if ($user['crystal'] < $cfg['crystal'])
			{
				err('У тебя нехватает ' . ($cfg['crystal'] - $user['crystal']) . ' кристаллов!');
			}
			elseif ($in['time_recovery'] != 0)
			{
				err('Этот генерал уже восстанавливается!');
			}
			else
			{
				mysql_query("UPDATE users SET crystal = crystal - '$cfg[crystal]' WHERE id = '$user[id]' LIMIT 1");
				mysql_query("UPDATE generals SET health = '200' WHERE id = '$id' AND player = '$user[id]' LIMIT 1");
				msg('Здоровье генерала восстановлено.');
			}
		}

		if ($in['health'] < 200 && $in['time_recovery'] != 0)
		{
			$ONL = round(($rtime - $in['time_recovery']) / 20);
			$rec = round(2 + ($in['level'] * $in['recovery']) * 100 / 100);

			echo 'Идет восстановление...' . separator;
			mysql_query("UPDATE generals SET time_recovery = '$rtime', health = '" . plus($in['health'], isset($ONL) ? ($ONL * $rec) : $rec, 200) . "' WHERE id = '$id' AND player = '$user[id]' LIMIT 1");
		}
		elseif ($in['health'] == 200 && $in['time_recovery'] != 0)
		{
			mysql_query("UPDATE generals SET time_recovery = '0' WHERE id = '$id' AND player = '$user[id]' LIMIT 1");
		}

		$ONL = round(($rtime - $in['time_recovery']) / 20);
		$rec = round(2 + ($in['level'] * $in['recovery']) * 100 / 100);
		$_left = round((200 - $in['health']) / $rec * 60);

		echo '<img src="images/small_life.png" alt="small"/> <a href="general_recovery'.$id.'&amp;normal"><u>Малый эликсир</u></a><br/>
			  <small>Требуется:</small><br/>
			  <img src="images/res.1.png" alt="Еда"/> <small>' . $price . '</small>
			  <img src="images/res.2.png" alt="Дерево"/> <small>' . $price . '</small>
			  <img src="images/res.3.png" alt="Камень"/> <small>' . $price . '</small>
			  <img src="images/res.4.png" alt="Руда"/> <small>' . $price . '</small>
			  <img src="images/res.5.png" alt="Золото"/> <small>' . $price . '</small>
			  <img src="images/time.png" alt="Время"/><small>' . val($_left) . '</small>
			  ' . separator . '
			  <img src="images/big_life.png" alt="big"/> <a href="general_recovery'.$id.'&amp;quick"><u>Большой эликсир (мгновенно)</u></a><br/>
			  <small>Требуется:</small> <small>' . $cfg['crystal'] . '</small> <img src="images/crystal.png" title="Кристаллы" alt=""/>
			  <br/>';
	}
	echo '<br/><a href="general_control'.$id.'">Вернуться</a>';
break;

case dealers_run:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '9' AND user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
	if (!isset($id_build['id']))
	{
		err('У тебя не построено здание Торговая палата!');
		include 'sys/foot.php';
		exit();
	}

	if (isset($_GET['run']))
	{
		$run = number($_GET['run']);
		$tst = mysql_query("SELECT * FROM rialto WHERE player = '$user[id]' AND id = '$run' AND timeleft < '$rtime' AND time_run > '$rtime' LIMIT 1");

		if (mysql_num_rows($tst) == false)
		{
			err('Торговцы возвращаются домой или уже вернулись!');
		}
		else
		{
			$q = mysql_fetch_assoc($tst);
			$sp = ($user['race'] == 1) ? 35 : 20;
			$loc = mysql_fetch_assoc(mysql_query("SELECT x, y FROM location WHERE id = '$q[destination_dealer]' LIMIT 1"));
			$arrival = travel_time(10 * $sp, $user['coor_x'], $user['coor_y'], $loc['x'], $loc['y']);
			$timer = ($rtime + $arrival) - $q['time_run'];
			mysql_query("UPDATE rialto SET time_back = '".($rtime + $timer)."' WHERE id = '$run' AND player = '$user[id]' LIMIT 1");
			header('location: dealers_back');
		}
	}

	$dealers_run = mysql_query("SELECT id, time_run, destination_dealer FROM rialto WHERE player = '$user[id]' AND time_run > '$rtime'");
	if (mysql_num_rows($dealers_run) == 0)
	{
		echo 'Нет торговцев в дороге.<br/>';
	}
	else
	{
		$view = mysql_fetch_assoc($dealers_run);
		$COOR = mysql_fetch_assoc(mysql_query("SELECT x, y FROM location WHERE id = '$view[destination_dealer]' LIMIT 1"));
		echo '&rarr; Направляются к <a href="location'.$COOR['x'].'_'.$COOR['y'].'">[x:' . $COOR['x'] . ';y:' . $COOR['y'] . ']</a>
			  <a href="cansel_dealers'.$view['id'].'"><img src="images/unit_action_2.png" alt=""/></a><br/>
			  Осталось ' . val($view['time_run'] - $rtime) . '<br/>';
	}
	echo '<br/><a href="upgrade'.$id_build['id'].'">Вернуться</a>';
break;

case dealers_back:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '9' AND user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
	if (!isset($id_build['id']))
	{
		err('У тебя не построено здание Торговая палата!');
		include 'sys/foot.php';
		exit();
	}

	$dealers_back = mysql_query("SELECT time_back FROM rialto WHERE player = '$user[id]' AND time_back > '$rtime'");
	if (mysql_num_rows($dealers_back) == 0)
	{
		echo 'Нет приближающихся торговцев.<br/>';
	}
	else
	{
		$view = mysql_fetch_assoc($dealers_back);
		echo '&rarr; Возвращаются домой...<br/>
			  Осталось ' . val($view['time_back'] - $rtime) . '<br/>';
	}
	echo '<br/><a href="upgrade'.$id_build['id'].'">Вернуться</a>';
break;

case pump:
	$id = number($_GET['id']);
	$double = mysql_query("SELECT * FROM construct WHERE user = '$user[id]' AND id = '$id' LIMIT 1");
	$myb = mysql_query("SELECT * FROM construct WHERE user = '$user[id]' AND timeleft > '$rtime' LIMIT 1");
	$inm = mysql_fetch_assoc($double);
	$in = mysql_fetch_assoc($myb);

	if (mysql_num_rows($myb) != false)
	{
		err('В настоящее время стройка занята!');
	}
	elseif (mysql_num_rows($double) == false)
	{
		err('Здание не найдено на карте!');
	}
	elseif ($inm['maxlvl'] == 1)
	{
		err('Это здание нельзя улучшать!');
	}
	elseif ($inm['level'] == $inm['maxlvl'])
	{
		err('Здание улучшено на максимальный уровень!');
	}
	else
	{
		switch($inm['cid']):
			case 1:
				$tleft = (60 * ($inm['level'] + 1) / 1.5);
				$tree = round(100 * ($inm['level'] + 1) / 1.5);
				$gold = round(200 * ($inm['level'] + 1) / 1.5);
				$sql = "UPDATE users SET time_later = '$rtime', tree = tree - '$tree', gold = gold - '$gold' WHERE id = '$user[id]' LIMIT 1"; 
			break;
			case 2:
				$tleft = (120 * ($inm['level'] + 1) / 1.5);
				$tree = round(200 * ($inm['level'] + 1) / 1.5);
				$gold = round(250 * ($inm['level'] + 1) / 1.5);
				$sql = "UPDATE users SET time_later = '$rtime', tree = tree - '$tree', gold = gold - '$gold' WHERE id = '$user[id]' LIMIT 1"; 
			break;
			case 3:
				$tleft = (120 * ($inm['level'] + 1) / 1.5);
				$stone = round(200 * ($inm['level'] + 1) / 1.5);
				$ore = round(250 * ($inm['level'] + 1) / 1.5);
				$sql = "UPDATE users SET time_later = '$rtime', stone = stone - '$stone', ore = ore - '$ore' WHERE id = '$user[id]' LIMIT 1"; 
			break;
			case 4:
				$tleft = (120 * ($inm['level'] + 1) / 1.5);
				$food = round(400 * ($inm['level'] + 1) / 1.5);
				$tree = round(250 * ($inm['level'] + 1) / 1.5);
				$ore = round(400 * ($inm['level'] + 1) / 1.5);
				$sql = "UPDATE users SET time_later = '$rtime', food = food - '$food', tree = tree - '$tree', ore = ore - '$ore' WHERE id = '$user[id]' LIMIT 1";
			break;
			case 5:
				$tleft = (120 * ($inm['level'] + 1) / 1.5);
				$stone = round(200 * ($inm['level'] + 1) / 1.5);
				$tree = round(300 * ($inm['level'] + 1) / 1.5);
				$ore = round(200 * ($inm['level'] + 1) / 1.5);
				$sql = "UPDATE users SET time_later = '$rtime', tree = tree - '$tree', stone = stone - '$stone', ore = ore - '$ore' WHERE id = '$user[id]' LIMIT 1";
			break;
			case 7:
				$tleft = (600 * ($inm['level'] + 1) / 1.5);
				$stone = round(400 * ($inm['level'] + 1) / 1.5);
				$gold = round(200 * ($inm['level'] + 1) / 1.5);
				$sql = "UPDATE users SET time_later = '$rtime', gold = gold - '$gold', stone = stone - '$stone' WHERE id = '$user[id]' LIMIT 1";
			break;
			case 8:
				$tleft = (600 * ($inm['level'] + 1) / 1.5);
				$ore = round(200 * ($inm['level'] + 1) / 1.5);
				$stone = round(200 * ($inm['level'] + 1) / 1.5);
				$sql = "UPDATE users SET time_later = '$rtime', ore = ore - '$ore', stone = stone - '$stone' WHERE id = '$user[id]' LIMIT 1";
			break;
			case 9:
				$tleft = (300 * ($inm['level'] + 1) / 1.5);
				$tree = round(150 * ($inm['level'] + 1) / 1.5);
				$gold = round(200 * ($inm['level'] + 1) / 1.5);
				$sql = "UPDATE users SET time_later = '$rtime', gold = gold - '$gold', tree = tree - '$tree' WHERE id = '$user[id]' LIMIT 1";
			break;
		endswitch;
		##############################
		if ($inm['cid'] == 1)
		{
			if ($user['tree'] < $tree) $short[] .= '<img src="/images/res.2.png" alt="" title="Дерево"/>' . ($tree - $user['tree']);
			if ($user['gold'] < $gold) $short[] .= '<img src="/images/res.5.png" alt="" title="Золото"/>' . ($gold - $user['gold']);
			if (empty($short))
			{
				mysql_query($sql);
				mysql_query("UPDATE construct SET uplvl = '1', timeleft = '" . ($rtime + $tleft) . "', stamp = '$tleft' WHERE id = '$id' AND user = '$user[id]' LIMIT 1");
				msg('Улучшение началось.');
			}
			else err('У тебя нехватает: ' . implode(', ', $short));
		}
		if ($inm['cid'] == 2)
		{
			if ($user['tree'] < $tree) $short[] .= '<img src="/images/res.2.png" alt="" title="Дерево"/>' . ($tree - $user['tree']);
			if ($user['gold'] < $gold) $short[] .= '<img src="/images/res.5.png" alt="" title="Золото"/>' . ($gold - $user['gold']);
			if (empty($short))
			{
				mysql_query($sql);
				mysql_query("UPDATE construct SET uplvl = '1', timeleft = '" . ($rtime + $tleft) . "', stamp = '$tleft' WHERE id = '$id' AND user = '$user[id]' LIMIT 1");
				msg('Улучшение началось.');
			}
			else err('У тебя нехватает: ' . implode(', ', $short));
		}
		if ($inm['cid'] == 3)
		{
			if ($user['stone'] < $stone) $short[] .= '<img src="/images/res.3.png" alt="" title="Камень"/>' . ($stone - $user['stone']);
			if ($user['ore'] < $ore) $short[] .= '<img src="/images/res.4.png" alt="" title="Руда"/>' . ($ore - $user['ore']);
			if (empty($short))
			{
				mysql_query($sql);
				mysql_query("UPDATE construct SET uplvl = '1', timeleft = '" . ($rtime + $tleft) . "', stamp = '$tleft' WHERE id = '$id' AND user = '$user[id]' LIMIT 1");
				msg('Улучшение началось.');
			}
			else err('У тебя нехватает: ' . implode(', ', $short));
		}
		if ($inm['cid'] == 4)
		{
			if ($user['food'] < $food) $short[] .= '<img src="/images/res.1.png" alt="" title="Еда"/>' . ($food - $user['food']);
			if ($user['tree'] < $tree) $short[] .= '<img src="/images/res.2.png" alt="" title="Дерево"/>' . ($tree - $user['tree']);
			if ($user['ore'] < $ore) $short[] .= '<img src="/images/res.4.png" alt="" title="Руда"/>' . ($ore - $user['ore']);
			if (empty($short))
			{
				mysql_query($sql);
				mysql_query("UPDATE construct SET uplvl = '1', timeleft = '" . ($rtime + $tleft) . "', stamp = '$tleft' WHERE id = '$id' AND user = '$user[id]' LIMIT 1");
				msg('Улучшение началось.');
			}
			else err('У тебя нехватает: ' . implode(', ', $short));
		}
		if ($inm['cid'] == 5)
		{
			if ($user['tree'] < $tree) $short[] .= '<img src="/images/res.2.png" alt="" title="Дерево"/>' . ($tree - $user['tree']);
			if ($user['stone'] < $stone) $short[] .= '<img src="/images/res.3.png" alt="" title="Камень"/>' . ($stone - $user['stone']);
			if ($user['ore'] < $ore) $short[] .= '<img src="/images/res.4.png" alt="" title="Руда"/>' . ($ore - $user['ore']);
			if (empty($short))
			{
				mysql_query($sql);
				mysql_query("UPDATE construct SET uplvl = '1', timeleft = '" . ($rtime + $tleft) . "', stamp = '$tleft' WHERE id = '$id' AND user = '$user[id]' LIMIT 1");
				msg('Улучшение началось.');
			}
			else err('У тебя нехватает: ' . implode(', ', $short));
		}
		if ($inm['cid'] == 7)
		{
			if ($user['stone'] < $stone) $short[] .= '<img src="/images/res.3.png" alt="" title="Камень"/>' . ($stone - $user['stone']);
			if ($user['food'] < $food) $short[] .= '<img src="/images/res.1.png" alt="" title="Еда"/>' . ($food - $user['food']);
			if (empty($short))
			{
				mysql_query($sql);
				mysql_query("UPDATE construct SET uplvl = '1', timeleft = '" . ($rtime + $tleft) . "', stamp = '$tleft' WHERE id = '$id' AND user = '$user[id]' LIMIT 1");
				msg('Улучшение началось.');
			}
			else err($short);
		}
		if ($inm['cid'] == 8)
		{
			if ($user['stone'] < $stone) $short[] .= '<img src="/images/res.3.png" alt="" title="Камень"/>' . ($stone - $user['stone']);
			if ($user['ore'] < $ore) $short[] .= '<img src="/images/res.4.png" alt="" title="Руда"/>' . ($ore - $user['ore']);
			if (empty($short))
			{
				mysql_query($sql);
				mysql_query("UPDATE construct SET uplvl = '1', timeleft = '" . ($rtime + $tleft) . "', stamp = '$tleft' WHERE id = '$id' AND user = '$user[id]' LIMIT 1");
				msg('Улучшение началось.');
			}
			else err($short);
		}
		if ($inm['cid'] == 9)
		{
			if ($user['stone'] < $stone) $short[] .= '<img src="/images/res.3.png" alt="" title="Камень"/>' . ($stone - $user['stone']);
			if ($user['tree'] < $tree) $short[] .= '<img src="/images/res.2.png" alt="" title="Дерево"/>' . ($tree - $user['tree']);
			if ($user['food'] < $food) $short[] .= '<img src="/images/res.1.png" alt="" title="Еда"/>' . ($food - $user['food']);
			if (empty($short))
			{
				mysql_query($sql);
				mysql_query("UPDATE construct SET uplvl = '1', timeleft = '" . ($rtime + $tleft) . "', stamp = '$tleft' WHERE id = '$id' AND user = '$user[id]' LIMIT 1");
				msg('Улучшение началось.');
			}
			else err($short);
		}
		##############################
	}
break;

case market:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '9' AND user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
	if (!isset($id_build['id']))
	{
		err('У тебя не построено здание Рынок!');
		include 'sys/foot.php';
		exit();
	}

	if (isset($_POST['swap']))
	{
		$resource_1 = char($_POST['resource_1'], 0);
		$resource_2 = char($_POST['resource_2'], 0);
		$amount = number($_POST['amount']);

		if (empty($amount)) $err = 'Не введено кол-во!';
		elseif ($resource_1 == $resource_2) $err = 'Обмен идентичных ресурсов невозможен!';
		elseif ($amount > $user[$resource_1]) $err = 'Не хватает обмениваемого ресурса!';

		if (empty($err))
		{
			$skl = mysql_query("SELECT id, level FROM construct WHERE user = '$user[id]' AND cid = '8' AND timeleft < '$rtime' LIMIT 1");
			$my_skl = mysql_fetch_assoc($skl);
			$STORAGE = $my_skl['level'] * 5 * 1000;

			mysql_query("UPDATE users SET $resource_1 = '" . minus($user[$resource_1], $amount, (!isset($my_skl['id'])) ? 1000 : $STORAGE) . "', $resource_2 = '" . plus($user[$resource_2], $amount, (!isset($my_skl['id'])) ? 1000 : $STORAGE) . "' WHERE id = '$user[id]' LIMIT 1");
			msg('Ресурсы обменены!');
		}
		else
		{
			err($err);
		}
	}
		echo '<form method="post" action="market">
			  Что меняем?
			  <br/>
			  <select name="resource_1">
			  <option value="food">Еда</option>
			  <option value="tree">Дерево</option>
			  <option value="stone">Камень</option>
			  <option value="ore">Руда</option>
			  <option value="gold">Золото</option>
			  </select>
			  <input type="text" name="amount" size="5" maxlength="5"/>
			  <br/>
			  На что?
			  <br/>
			  <select name="resource_2">
			  <option value="food">Еда</option>
			  <option value="tree">Дерево</option>
			  <option value="stone">Камень</option>
			  <option value="ore">Руда</option>
			  <option value="gold">Золото</option>
			  </select>
			  <br/>
			  <input type="submit" name="swap" value="Обменять"/>
			  </form>
			  <a href="upgrade'.$id_build['id'].'">Вернуться</a>';
break;

case transmint:
	$id_build = mysql_fetch_assoc(mysql_query("SELECT id FROM construct WHERE cid = '9' AND user = '$user[id]' AND timeleft < '$rtime' LIMIT 1"));
	if (!isset($id_build['id']))
	{
		err('У тебя не построено здание Рынок!');
		include 'sys/foot.php';
		exit();
	}
	$cr = mysql_query("SELECT * FROM rialto WHERE player = '$user[id]' AND timeleft < '$rtime' LIMIT 1");
	$cargo = mysql_fetch_assoc($cr);
	$cap_level = mysql_fetch_assoc(mysql_query("SELECT level FROM construct WHERE cid = '7' AND timeleft < '$rtime' AND user = '$user[id]' LIMIT 1"));
	$cap = ($user['race'] == 1) ? round(15 * $cap_level['level'] / 2) : round(25 * $cap_level['level'] / 2);
	$bag = $cap * $cargo['dealer'];

	if (isset($_POST['forward']))
	{
		$x = char($_POST['x'], 0);
		$y = char($_POST['y'], 0);
		$res = char($_POST['res'], 0);
		$N = number($_POST['N']);

		$ctrl = mysql_fetch_assoc(mysql_query("SELECT id, id_player FROM location WHERE x = '$x' AND y = '$y' LIMIT 1"));

		if (mysql_num_rows($cr) == false)
		{
			err('У тебя нет свободных торговцев!');
		}
		elseif ($cargo['time_run'] > $rtime)
		{
			err('Торговцы в настоящее время в походе!');
		}
		elseif ($cargo['time_back'] > $rtime)
		{
			err('Торговцы возвращаются домой!');
		}
		elseif ($x < -98 || $x > 98 || $y < -98 || $y > 98)
		{
			err('Неверные координаты!');
		}
		elseif ($ctrl['id_player'] == 0)
		{
			err('На этой местности никого нет!');
		}
		elseif ($x == $user['coor_x'] && $y == $user['coor_y'])
		{
			err('Ты и так на этой местности!');
		}
		elseif (empty($N))
		{
			err('Необходимо ввести кол-во!');
		}
		elseif ($N > $bag)
		{
			err('Торговцы не унесут столько!');
		}
		elseif ($N > $user[$res])
		{
			err('Нехватает данного ресурса!');
		}
		elseif ($N > $user[$res])
		{
			err('Нехватает данного ресурса!');
		}
		else
		{
			$arrival = travel_time(10 * ($user['race'] == 1 ? 35 : 20), $user['coor_x'], $user['coor_y'], $x, $y);
			mysql_query("UPDATE rialto SET
			resource = '$res',
			destination_dealer = '$ctrl[id]',
			goods = '$N',
			time_run = '" . ($rtime + $arrival) . "',
			time_back = '0'
			WHERE player = '$user[id]' LIMIT 1");
			mysql_query("UPDATE users SET $res = $res - '$N' WHERE id = '$user[id]' LIMIT 1");
			msg('Торговцы отправились.');
		}
	}

	echo '<form method="post" action="transmint">
		  Координаты:<br/>
		  x: <input type="text" name="x" maxlength="3" size="3"/>
		  y: <input type="text" name="y" maxlength="3" size="3"/>
	 	  <br/>
		  Передаваемый ресурс:<br/>
		  <select name="res">
		  <option value="food">Еда</option>
		  <option value="tree">Дерево</option>
		  <option value="stone">Камень</option>
		  <option value="ore">Руда</option>
		  <option value="gold">Золото</option>
		  </select>
		  <br/>
		  Количество: (макс. ' . $bag . ' ед.)<br/>
		  <input type="text" name="N" size="5" maxlength="5"/>
		  <br/>
	 	  <input type="submit" name="forward" value="Отправить"/>
	 	  </form>
		  <a href="upgrade'.$id_build['id'].'">Вернуться</a>';
break;

}

echo '<br/><a href="index.php">На главную</a>';

include 'sys/foot.php';
?>
