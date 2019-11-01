<?php
$token = 'TOKEN';
$admin = 'USERID';
//-----------[ method bot ]-------------
define('API_KEY',"$token");
function bot($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}
//------------[ functions ]--------------[ Share by: mr-incognito.ir ]------------
/* Tabee Send Message */
function SendMessage($chatid,$text,$parsmde,$disable_web_page_preview,$keyboard){
	bot('sendMessage',[
	'chat_id'=>$chatid,
	'text'=>$text,
	'parse_mode'=>$parsmde,
	'disable_web_page_preview'=>$disable_web_page_preview,
	'reply_markup'=>$keyboard
	]);
	}
	/* Tabee Forward Message */
function ForwardMessage($chatid,$from_chat,$message_id){
	bot('ForwardMessage',[
	'chat_id'=>$chatid,
	'from_chat_id'=>$from_chat,
	'message_id'=>$message_id
	]);
	}
	/* Tabee Get Chat Member */
function GetChatMember($chatid,$userid){
	$truechannel = json_decode(file_get_contents('https://api.telegram.org/bot'.API_KEY."/getChatMember?chat_id=".$chatid."&user_id=".$userid));
	$tch = $truechannel->result->status;
	return $tch;
	}
//---------------[ Variables ]-------------------[ Share by: mr-incognito.ir ]------------
$update = json_decode(file_get_contents('php://input'));
$chat_id = $update->message->chat->id;
$from_id = $update->message->from->id;
$Username = $update->message->from->username;
$type = $update->message->chat->type;
$first = $update->message->from->first_name;
$last = $update->message->from->last_name;
$username = $update->message->from->username;
$text = $update->message->text;
$message_id = $update->message->message_id;
$reply = $update->message->reply_to_message->forward_from->id;
@$command = file_get_contents("data/command.txt");
@$members = file_get_contents("data/members.txt");
@$text_start = file_get_contents("data/textstart.txt");
@$text_bio = file_get_contents("data/biography.txt");
@$text_send = file_get_contents("data/textsend.txt");
@$channel = file_get_contents("data/channel.txt");
@$set_channel = file_get_contents("data/setchannel.txt");
@$ban_list = file_get_contents("data/banlist.txt");
@$bot_type = file_get_contents("data/bot.txt");
$truechannel = json_decode(file_get_contents('https://api.telegram.org/bot'.API_KEY."/getChatMember?chat_id=@$channel&user_id=".$chat_id));
$tch = $truechannel->result->status;
//---------------[ bottons ]----------------[ Share by: mr-incognito.ir ]------------
$button_admin = json_encode(['keyboard'=>[
[['text'=>"📊 Bot Statistics"]],
[['text'=>"📧 Public Message"],['text'=>"📢 Publish Public Message"]],
[['text'=>"📚 Help"],['text'=>"🔒 Channel Lock"]],
[['text'=>"🏁 Start Message"],['text'=>"📝 Set Response Text"]],
[['text'=>"📑 Set Biography Text"],['text'=>"👨‍👩‍👧‍👦 Members"]],
[['text'=>"⚙️ About Programmer"]]
],'resize_keyboard'=>true]);
$button_back = json_encode(['keyboard'=>[
[['text'=>"Back 🔄"]]
],'resize_keyboard'=>true]);
$button_remove = json_encode(['KeyboardRemove'=>[
],'remove_keyboard'=>true]);
$profile = json_encode(['keyboard'=>[
[['text'=>"📑 My Biography"]
]],'resize_keyboard'=>true]);
$inlinebutton = json_encode(['inline_keyboard'=>[[['text'=>"Developer Channel",'url'=>"https://telegram.me/$channel"]],]]);
$donate = json_encode(['inline_keyboard'=>[[['text'=>"Donate To Him",'url'=>"https://zarinp.al/@incognito"]],]]);
//---------------[ start bot ]----------------[ Share by: mr-incognito.ir ]------------
if (strpos($ban_list , "$from_id") !== false) {
	sendMessage($from_id,"You have been blocked from our server. Do not send another message");
	return false;
	}
elseif($text == "/start" or $text == "/Start"){
	if($chat_id == $admin){
		file_put_contents("data/command.txt","none");
		SendMessage($chat_id,"$first\n$text_start","html","true",$button_admin);
	}
	elseif($chat_id == $from_id){
	    SendMessage($chat_id,"$first\n$text_start","html","true",$profile);
	}
	$user = file_get_contents('data/members.txt');
        $members2 = explode("\n", $user);
        if (!in_array($from_id, $members2)) {
            $add_user = file_get_contents('data/members.txt');
            $add_user .= $from_id . "\n";
            file_put_contents('data/members.txt', $add_user);
        }
}
//--------------[ channel lock ]----------------[ Share by: mr-incognito.ir ]------------
elseif($tch != 'member' && $tch != 'creator' && $tch != 'administrator' && $set_channel == "yes" && $chat_id != $admin){
	SendMessage($chat_id,"🚫 To support us, as well as send messages to this robot, you must first subscribe to the channel:

@$channel

♻️ After subscrible to channel send /start .","html","true",$button_remove);
}
//--------------[ panel ]---------------------[ Share by: mr-incognito.ir ]------------
elseif($text == "Back 🔄" and $chat_id == $admin){
	file_put_contents("data/command.txt","none");
	SendMessage($chat_id,"You returned to the main menu","html","true",$button_admin);
}
elseif($text == "/on" and $chat_id == $admin){
	file_put_contents("data/bot.txt","on");
	SendMessage($chat_id,"The robot was turned on","html","true",$button_admin);
}
elseif($text == "/off" and $chat_id == $admin){
	file_put_contents("data/bot.txt","off");
	SendMessage($chat_id,"The robot was turned off","html","true",$button_admin);
}
elseif($text == "📚 Help" and $chat_id == $admin){
	SendMessage($chat_id,"1️⃣ To reply to the user, click on the message and submit the response using the Reply option

2️⃣ To block the user reply to it and send /ban command!

3️⃣ To Ublock the user reply to it and send /unban command!

4️⃣ Shutdown bot /off !

5️⃣ Getup bot by /on !

","html","true",$button_admin);
}
elseif($text == "🔒 Channel Lock" and $chat_id == $admin){
	file_put_contents("data/command.txt","set channel");
	SendMessage($chat_id,"Please add bot to admins in your channel and send your channel username without @
For cancel channel lock send /NotSet to me.","html","true",$button_back);
}
elseif($text == "📑 Set Biography Text" and $chat_id == $admin){
    file_put_contents("data/command.txt","set Bio");
    SendMessage($chat_id,"Please Insert New Biography Text.","html","true",$button_back);
}
elseif($command == "set channel"){
	file_put_contents("data/command.txt","none");
	if($text == "/NotSet"){
		file_put_contents("data/setchannel.txt","no");
		SendMessage($chat_id,"Channel lock removed","html","true",$button_admin);
	}else{
		file_put_contents("data/channel.txt","$text");
		file_put_contents("data/setchannel.txt","yes");
		SendMessage($chat_id,"Channel lock for @$text set!","html","true",$button_admin);
	}
}
elseif($text == "📧 Public Message" and $chat_id == $admin){
	file_put_contents("data/command.txt","send pm");
	SendMessage($chat_id,"If your message is a photo or movie or ... Send it to the Publish Public Message!
	Post your text in plain text: ","html","true",$button_back);
}
elseif($command == "send pm"){
	file_put_contents("data/command.txt","none");
	SendMessage($chat_id,"The message was sent to the queue...","html","true",$button_admin);
	$fp = fopen("data/members.txt", 'r');
        while (!feof($fp)) {
            $ckar = fgets($fp);
			SendMessage($ckar,"$text","html","true",null);
}
SendMessage($chat_id,"Your message has been sent!","html","true",$button_admin);   
}
elseif($text == "📢 Publish Public Message" and $chat_id == $admin){
	file_put_contents("data/command.txt","fwd");
	SendMessage($chat_id,"Forward the message:","html","true",$button_back);
}
elseif($command == "fwd"){
	file_put_contents("data/command.txt","none");
	SendMessage($chat_id,"Your Message is waiting for the Forward . . .","html","true",$button_admin);
	$forp = fopen("data/members.txt", 'r');
        while (!feof($forp)) {
            $fakar = fgets($forp);
            ForwardMessage($fakar, $chat_id, $message_id);
		}
		SendMessage($chat_id,"Your message was forwards!","html","true",$button_admin); 
}
elseif($text == "📊 Bot Statistics" and $chat_id == $admin){
$member_id = explode("\n", $members);
$member_count = count($member_id) - 1;
SendMessage($chat_id,"Number of members at the moment :\n $member_count member.","html","true",$button_admin);
}
elseif($text == "🏁 Start Message" and $chat_id == $admin){
file_put_contents("data/command.txt","set text start");
sendMessage($chat_id,"Submit the text you want","html","true",$button_back);
}
elseif($command == "set text start"){
	file_put_contents("data/command.txt","none");
	file_put_contents("data/textstart.txt",$text);
	SendMessage($chat_id,"Start robot text set:
	
	$text","html","true",$button_admin);
}
elseif($command == "set Bio"){
    file_put_contents("data/command.txt","none");
	file_put_contents("data/biography.txt",$text);
	SendMessage($chat_id,"New Bio Graphy Text:
	
	$text","html","true",$button_admin);
}
elseif($text == "📝 Set Response Text" and $chat_id == $admin){
file_put_contents("data/command.txt","set text send");
sendMessage($chat_id,"Submit the text you want","html","true",$button_back);
}
elseif($command == "set text send"){
	file_put_contents("data/command.txt","none");
	file_put_contents("data/textsend.txt",$text);
	SendMessage($chat_id,"Response text set:
	
	$text","html","true",$button_admin);
}
elseif($reply != null && $chat_id == $admin && $text == "/ban"){
	$ban = "$ban_list\n$reply";
	file_put_contents("data/banlist.txt","$ban");
	SendMessage($chat_id,"🚫 Banned.","html","true",$button_admin);
}
elseif($reply != null && $chat_id == $admin && $text == "/unban"){
	$ban = str_replace($reply,$ban_list);
	file_put_contents("data/banlist.txt",$ban);
	SendMessage($chat_id,"🚫 Unbanned.","html","true",$button_admin);
}
elseif($reply != null && $chat_id == $admin){
	SendMessage($reply,"$text","html","true",null);
	SendMessage($chat_id,"📤 Sent","html","true",$button_admin);
}
elseif($text == "📑 My Biography" and $chat_id == $from_id){
SendMessage($chat_id,$text_bio,"html","",$inlinebutton);
}
elseif($text == "👨‍👩‍👧‍👦 Members"and $chat_id == $admin){
    $member_id = explode("\n", $members);
    $member_count = count($member_id) - 1;
    SendMessage($chat_id,"<i>This is Memebers chat id list :</i>\n\n$members\n<b>Total</b> : $member_count member","html","true",$button_admin);
}
elseif($text == "⚙️ About Programmer" and $chat_id ==$admin){
    SendMessage($chat_id,"Fix and Release : alireza ahmand.
Nationality : IRAN
Age : 17
Phone : <code>09386498722</code>
Support link : @Incognito_Coder
Please join to my channel : @Incognito_Coders","html","true",$donate);
}
//-------------[ user ]------------------------------[ Share by: mr-incognito.ir ]------------
elseif($text == "/creator" and $chat_id != $admin or $text == "/Creator" and $chat_id != $admin){
SendMessage($chat_id,$text_bio,"html","true",null);
}
else{
	if($bot_type == "on"){
	if($chat_id != $admin){
	ForwardMessage($admin, $chat_id, $message_id);	
	bot('sendMessage',[
	'chat_id'=>$chat_id,
	'text'=>"$text_send",
	'parse_mode'=>"html",
	'disable_web_page_preview'=>true,
	'reply_to_message_id'=>$message_id
	]);
	}else{
	 SendMessage($chat_id,"Unspecified command . . .","MarkDown","true",$Button_admin);   
	}
}else{
	bot('sendMessage',[
	'chat_id'=>$chat_id,
	'text'=>"🚫 The bot is off now.
The robot is off, please send the message later",
	'parse_mode'=>"html",
	'disable_web_page_preview'=>true,
	'reply_to_message_id'=>$message_id
	]);
}
}
unlink('error_log');

?>
