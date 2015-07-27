<?php
	echo file_get_contents("http://bxmusic-bxmusic.stor.sinaapp.com/---(T-ara)-DAY%20BY%20DAY.lrc");
	unlink("http://bxmusic-bxmusic.stor.sinaapp.com/---(T-ara)-DAY%20BY%20DAY.lrc");
	echo "content:";
	echo file_get_contents("http://bxmusic-bxmusic.stor.sinaapp.com/---(T-ara)-DAY%20BY%20DAY.lrc");
?>