<?php
session_start();
$story_pages[1]=11;
$story_pages[2]=39;
$story_pages[3]=10;
if($_REQUEST['event']=='NewChat')
{
		$_SESSION['page']=1;
		$_SESSION['story_number']=rand(1,3);
		$_SESSION['number_of_pages']=$story_pages[$_SESSION['story_number']];
		$_SESSION['current_page']=1;
		echo "<response><chat-reply>Hi,I am a story bot. If you want to hear a story please type next. Or say bye to close me. I show 1 page at a time. Type 'next' to get the next page.</chat-reply></response>";
}

if($_REQUEST['event']=='Reply')
{
	
	if(strpos(strtolower($_REQUEST['chat_text']), 'next') !== false)
	{
		if($_SESSION['current_page']<=$_SESSION['number_of_pages'])
		{
			echo "<response><chat-reply type='image'>{URL_of_stories_directory}/stories/story".$_SESSION['story_number']."/story-".$_SESSION['current_page'].".png</chat-reply></response>";
			$_SESSION['current_page']=$_SESSION['current_page']+1;
		}
		else
			echo "<response><chat-reply>Thanks for listening to the story.Bye. Say Hi to start another story.</chat-reply><disconnect/></response>";
	}
	else if(strpos(strtolower($_REQUEST['chat_text']), 'bye') !== false)
	{
		echo "<response><chat-reply>Thanks for listening to the story.Bye.Say Hi to start another story</chat-reply><disconnect/></response>";
	}
	else
		echo "<response> <chat-reply>Sorry! I am just a poor old StoryBot. Please say next or bye :(</chat-reply></response>";
}

if($_REQUEST['event']=='Error')
{
	$_SESSION['state']='top_menu';
	echo "<response><chat-reply>Sorry! Something unexpected happened. Please say Hi or Bye or Next.</chat-reply></response>";
	
}
?>


