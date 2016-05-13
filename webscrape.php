<?php
$url = 'https://www.facebook.com/MCMartinJacobs?fref=ts';
$output = file_get_contents($url);
echo $output;
?>