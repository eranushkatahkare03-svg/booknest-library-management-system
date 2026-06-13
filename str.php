<?php

echo "hiii";
$s = 'string hello';
$i=0;
$space=0;
$len=0;
while ($s[$i] != '') {
    if($s[$i]==" ")
    {   
       $space++;
    }
  $i++;
}

while ($s[$i] != '') {
    $len++;
    $i++;
}
echo "len".$len."<br>";
echo "words".$space+1;

?>