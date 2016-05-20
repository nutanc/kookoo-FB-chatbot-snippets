<?php
session_start();
if($_REQUEST['event']=='NewChat')
{
echo "<response><chat-reply>Hi,I am a startup news bot. If you want to see the list of today's stories please type stories. To get more, say more or next. Or say bye to close me. </chat-reply></response>";
$url = "http://www.nextbigwhat.com/feed/";
$x=file_get_contents($url);
//$x=htmlspecialchars(stripslashes(trim($x)));
$x=trim($x);
//echo $x;
libxml_use_internal_errors(true);
$rss = simplexml_load_string($x);
//var_dump($rss);
//var_dump(libxml_get_errors());
//$rss = simplexml_load_file('http://feeds.bbci.co.uk/news/england/rss.xml');
$_SESSION['page']=1;
$_SESSION['current_item']=0;
$_SESSION['number_of_items']=count($rss->channel->item );
$_SESSION['data']=$x;
//echo count($rss->channel->item );
/*foreach ($rss->channel->item as $item) {
   echo '<h2><a href="'. $item->link .'">' . $item->title . "</a></h2>";
   echo "<p>" . $item->pubDate . "</p>";
   echo "<p>" . $item->description . "</p>";
}*/
}

if($_REQUEST['event']=='Reply')
{
	//var_dump($_SESSION['rss']);
	if((strpos(strtolower($_REQUEST['chat_text']), 'stories') !== false) || (strpos(strtolower($_REQUEST['chat_text']), 'more') !== false) || strpos(strtolower($_REQUEST['chat_text']), 'next') !== false)
	{
		libxml_use_internal_errors(true);
		$rss = simplexml_load_string($_SESSION['data']);
		//var_dump(libxml_get_errors());

		if($_SESSION['current_item']<$_SESSION['number_of_items'])
		{
		echo "<response><template-reply>";
		$i=0;
		while($i<10 && $_SESSION['current_item']<$_SESSION['number_of_items'])
		{
			$clean_title=str_replace("'", "",strip_tags($rss->channel->item[$_SESSION['current_item']]->title));
			$clean_desc=str_replace("'", "",strip_tags($rss->channel->item[$_SESSION['current_item']]->description));
			echo "<send-template title='".substr($clean_title,0,45)."' subtitle='".substr($clean_desc,0,80)."'><button button_type = 'web_url' button_title = 'Open'>".$rss->channel->item[$_SESSION['current_item']]->link."</button></send-template>";
			//echo "<chat-reply type='image'>http://kookoo.in/customers/fbchat/stories/story".$_SESSION['story_number']."/story-".$_SESSION['current_page'].".png</chat-reply></response>";
			$_SESSION['current_item']=$_SESSION['current_item']+1;
			$i=$i+1;
		}
		echo "</template-reply></response>";
		}
		else
			echo "<response><chat-reply>Thanks for reading the stories. Thats all the NexBigWhats we have for today :) Bye. Say Hi to start reading again.</chat-reply><disconnect/></response>";
	}
	else if(strpos(strtolower($_REQUEST['chat_text']), 'bye') !== false)
	{
		echo "<response><chat-reply>Thanks for reading the stories.Bye.Say Hi to start another story</chat-reply><disconnect/></response>";
	}
	else
		echo "<response> <chat-reply>Sorry! I am just a poor old StartupNewsBot. Please say next or bye :(</chat-reply></response>";
}

if($_REQUEST['event']=='Error')
{
	$_SESSION['state']='top_menu';
	echo "<response><chat-reply>Sorry! Something unexpected happened. Please say Hi or Bye or Next.</chat-reply></response>";
	
}

 
?>
