<?php

function isEmpty($val){
if($val===0){
return true;
}else if (empty($val) && $val !== '0') {
    return true;
}else{
	return false;
}
}
function get_header($headerName)
{
    $headers = getallheaders();
     return isset($headerName) && isset($headers[$headerName]) && !isEmpty($headers[$headerName])? $headers[$headerName] : null;
}
?>
