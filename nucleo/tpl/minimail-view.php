<?php
/*=======================================================================
| UberCMS - Advanced Website and Content Management System for uberEmu
| #######################################################################
| Copyright (c) 2010, Roy 'Meth0d'
| http://www.meth0d.org
| #######################################################################
| This program is free software: you can redistribute it and/or modify
| it under the terms of the GNU General Public License as published by
| the Free Software Foundation, either version 3 of the License, or
| (at your option) any later version.
| #######################################################################
| This program is distributed in the hope that it will be useful,
| but WITHOUT ANY WARRANTY; without even the implied warranty of
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
| GNU General Public License for more details.
\======================================================================*/

require_once "global.php";

if (!LOGGED_IN)
{
	exit;
}

$messageId = 0;
$messageData = null;
$senderData = null;

if (isset($_GET['messageId']) && is_numeric($_GET['messageId']))
{
	$messageId = (int)$_GET['messageId'];
}

if ($messageId > 0)
{
	$getMessage = db::query("SELECT * FROM site_minimail WHERE id = ? AND receiver_id = ? LIMIT 1", $messageId, USER_ID)->fetch(2);
	
	if ($getMessage)
	{
        $senderData = db::query("SELECT * FROM users WHERE id = '" . $getMessage['sender_id'] . "' LIMIT 1")->fetch(2);

	}
}

if ($messageId == 0 || $messageData == null || $senderData == null)
{
	die("<div style='padding: 10px;'><b>Oops!</b><br />El mensaje no se pudo cargar. Por favor, intentelo de nuevo. Si esto es un problema persistente, ponte en contacto.</div>");
}

$tpl->Init();

$message = new Template('minimail-message');
$message->SetParam('to', USER_NAME);
$message->SetParam('from', clean($senderData['username']));
$message->SetParam('message_id', $messageData['id']);
$message->SetParam('subject', clean($messageData['subject']));
$message->SetParam('body-text', nl2br(clean($messageData['body'])));
$message->SetParam('trashed', ($messageData['folder'] == "trash") ? true : false);
$message->SetParam('sent', ($messageData['folder'] == "sent") ? true : false);

if ($messageData['is_read'] == "0")
{
	db::query("UPDATE site_minimail SET is_read = '1' WHERE id = '" . $messageData['id'] . "' LIMIT 1");
}

$tpl->AddTemplate($message);
$tpl->Output();

?>