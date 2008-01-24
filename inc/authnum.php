<?php    

main();

function getRandStr($length = 6, $mode = 0){
    $str1 = '1234567890';
    $str2 = 'abcdefghijklmnopqrstuvwxyz';
    $str3 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str4 = '_';
    $str5 = '`~!@#$%^&*()-+=\\|{}[];:\'",./?';
    switch ($mode) {
        case '0':
            $str = $str1 . $str2 . $str3 . $str4;
            break;
        case '1':
            $str = $str1;
            break;
        case '2':
            $str = $str2;
            break;
        case '3':
            $str = $str3;
            break;
        case '4':
            $str = $str2 . $str3;
            break;
        case '5':
            $str = $str1 . $str2;
            break;
        case '6':
            $str = $str1 . $str3;
            break;
        case '7':
            $str = $str1 . $str2 . $str3;
            break;
        case '8':
            $str = $str1 . $str2 . $str3 . $str4 . $str5;
            break;
        default:
            $str = $str1 . $str2 . $str3 . $str4;
            break;
    }
    
        $bgnIdx = 0;
        $endIdx = strlen($str)-1; 
        $code = "";
        for($i=0; $i<$length; $i++) {
            $curPos = rand($bgnIdx, $endIdx);
            $code .= substr($str, $curPos, 1);
        }
        return $code;
}    
        
    function main() {
        session_start();        
        $verifyCode = getRandStr($length = 5, $mode = 1);
        $_SESSION["verifyCode"] = $verifyCode;
        $imgWidth = 80;
        $imgHeight = 22;
        $imgFont = 6;
        doOutputImg($verifyCode, $imgWidth, $imgHeight, $imgFont);
    }
    
    function doOutputImg($string, $imgWidth, $imgHeight, $imgFont, $imgFgColorArr=array(70,40,60), $imgBgColorArr=array(225,255,255)) {
        $image = imagecreatetruecolor($imgWidth, $imgHeight);

        $backColor = imagecolorallocate($image, 255, 255, 255);
        $borderColor = imagecolorallocate($image, 0, 0, 0);
        imagefilledrectangle($image, 0, 0, $imgWidth - 1, $imgHeight - 1, $backColor);
        imagerectangle($image, 0, 0, $imgWidth - 1, $imgHeight - 1, $borderColor);

        $imgFgColor = imagecolorallocate ($image, $imgFgColorArr[0], $imgFgColorArr[1], $imgFgColorArr[2]);
        doDrawStr($image, $string, $imgFgColor, $imgFont);
        doPollute($image, 300);

        header('Content-type: image/png');
        imagepng($image);
        imagedestroy($image);
    }

    function doDrawStr($image, $string, $color, $imgFont) {
        $imgWidth = imagesx($image);
        $imgHeight = imagesy($image);
        
        $count = strlen($string);
        $xpace = ($imgWidth/$count);
        
        $x = ($xpace-6)/2;
        $y = ($imgHeight/2-8);
        for ($p = 0; $p<$count;  $p ++) {
            $xoff = rand(-2, +2);
            $yoff = rand(-2, +2);
            $curChar = substr($string, $p, 1);
            imagestring($image, $imgFont, $x+$xoff, $y+$yoff, $curChar, $color);
            $x += $xpace;
        }
        return 0;
    }
    
    function doPollute($image, $times) {  
        $imgWidth = imagesx($image);
        $imgHeight = imagesy($image);
        for($j=0; $j<$times; $j++) {
            $x = rand(0, $imgWidth);
            $y = rand(0, $imgHeight);
            
            $color = imagecolorallocate($image, rand(100,255), rand(10,255), rand(20,255));
            imagesetpixel($image, $x, $y, $color);
        }
    }
?>