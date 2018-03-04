<?php
echo $content->content;
foreach($photo as $v){
    echo "<img src='".$v['path']."'>";
}