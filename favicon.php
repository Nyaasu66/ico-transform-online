<?php
$typeinfo = "";

if (!empty($_FILES['upimage']['tmp_name']) && is_uploaded_file($_FILES['upimage']['tmp_name'])) {
    // 用 finfo 检测真实文件内容类型，避免被 HTTP Content-Type 欺骗
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $real_mime = finfo_file($finfo, $_FILES['upimage']['tmp_name']);
    finfo_close($finfo);

    $allowed_mime = array("image/jpeg", "image/png", "image/gif");
    if (!in_array($real_mime, $allowed_mime)) {
        $typeinfo = "图片格式错误！目前仅支持jpg，gif，png格式的图片。";
    } elseif ($_FILES['upimage']['size'] > 1024000) {
        $typeinfo = "图片尺寸超出限制！请确保图片大小在1MB以内。";
    } else {
        $imginfo = @getimagesize($_FILES['upimage']['tmp_name']);
        if (!is_array($imginfo)) {
            $typeinfo = "格式转换失败！请检测源文件无误后重试。";
        } elseif ($imginfo[0] > 4096 || $imginfo[1] > 4096) {
            // 防止图像炸弹：限制解码后的像素尺寸，避免内存耗尽
            $typeinfo = "图片像素尺寸过大！请使用4096×4096以内的图片。";
        } else {
            $im = false;
            if ($real_mime === "image/png") {
                $im = @imagecreatefrompng($_FILES['upimage']['tmp_name']);
            } elseif ($real_mime === "image/gif") {
                $im = @imagecreatefromgif($_FILES['upimage']['tmp_name']);
            } elseif ($real_mime === "image/jpeg") {
                $im = @imagecreatefromjpeg($_FILES['upimage']['tmp_name']);
            }

            if ($im === false) {
                $typeinfo = "生成ICO图标失败！请重试。";
            } else {
                switch ((int)$_POST['size']) {
                    case 1:  $size = 16;  break;
                    case 2:  $size = 32;  break;
                    case 3:  $size = 48;  break;
                    case 4:  $size = 64;  break;
                    case 5:  $size = 128; break;
                    case 6:  $size = 256; break;
                    default: $size = 64;  break;
                }

                $resize_im = imagecreatetruecolor($size, $size);

                // 保留透明背景
                imagealphablending($resize_im, false);
                imagesavealpha($resize_im, true);
                $trans_color = imagecolorallocatealpha($resize_im, 0, 0, 0, 127);
                imagefill($resize_im, 0, 0, $trans_color);

                imagecopyresampled($resize_im, $im, 0, 0, 0, 0, $size, $size, $imginfo[0], $imginfo[1]);
                imagedestroy($im);

                // phpthumb_ico 是生成 ICO 图标的类
                class phpthumb_ico
                {
                    function GD2ICOstring(&$gd_image_array)
                    {
                        $icANDmask = array();
                        foreach ($gd_image_array as $key => $gd_image) {
                            $ImageWidths[$key]  = ImageSX($gd_image);
                            $ImageHeights[$key] = ImageSY($gd_image);
                            $bpp[$key]          = ImageIsTrueColor($gd_image) ? 32 : 24;
                            $totalcolors[$key]  = ImageColorsTotal($gd_image);
                            $icXOR[$key]        = '';

                            for ($y = $ImageHeights[$key] - 1; $y >= 0; $y--) {
                                for ($x = 0; $x < $ImageWidths[$key]; $x++) {
                                    $argb = $this->GetPixelColor($gd_image, $x, $y);
                                    $a = round(255 * ((127 - $argb['alpha']) / 127));
                                    $r = $argb['red'];
                                    $g = $argb['green'];
                                    $b = $argb['blue'];

                                    if ($bpp[$key] == 32) {
                                        $icXOR[$key] .= chr($b) . chr($g) . chr($r) . chr($a);
                                    } else {
                                        $icXOR[$key] .= chr($b) . chr($g) . chr($r);
                                    }
                                    $icANDmask[$key][$y] = isset($icANDmask[$key][$y]) ? $icANDmask[$key][$y] : '';
                                    $icANDmask[$key][$y] .= ($a < 128) ? '1' : '0';
                                }
                                while (strlen($icANDmask[$key][$y]) % 32) {
                                    $icANDmask[$key][$y] .= '0';
                                }
                            }
                            $icAND[$key] = '';
                            foreach ($icANDmask[$key] as $y => $scanlinemaskbits) {
                                for ($i = 0; $i < strlen($scanlinemaskbits); $i += 8) {
                                    $icAND[$key] .= chr(bindec(str_pad(substr($scanlinemaskbits, $i, 8), 8, '0', STR_PAD_LEFT)));
                                }
                            }
                        }

                        foreach ($gd_image_array as $key => $gd_image) {
                            $biSizeImage = $ImageWidths[$key] * $ImageHeights[$key] * ($bpp[$key] / 8);
                            $BitmapInfoHeader[$key]  = "\x28\x00\x00\x00";
                            $BitmapInfoHeader[$key] .= $this->LittleEndian2String($ImageWidths[$key], 4);
                            $BitmapInfoHeader[$key] .= $this->LittleEndian2String($ImageHeights[$key] * 2, 4);
                            $BitmapInfoHeader[$key] .= "\x01\x00";
                            $BitmapInfoHeader[$key] .= chr($bpp[$key]) . "\x00";
                            $BitmapInfoHeader[$key] .= "\x00\x00\x00\x00";
                            $BitmapInfoHeader[$key] .= $this->LittleEndian2String($biSizeImage, 4);
                            $BitmapInfoHeader[$key] .= "\x00\x00\x00\x00";
                            $BitmapInfoHeader[$key] .= "\x00\x00\x00\x00";
                            $BitmapInfoHeader[$key] .= "\x00\x00\x00\x00";
                            $BitmapInfoHeader[$key] .= "\x00\x00\x00\x00";
                        }

                        $icondata  = "\x00\x00";
                        $icondata .= "\x01\x00";
                        $icondata .= $this->LittleEndian2String(count($gd_image_array), 2);
                        $dwImageOffset = 6 + (count($gd_image_array) * 16);

                        foreach ($gd_image_array as $key => $gd_image) {
                            $icondata .= chr($ImageWidths[$key]);
                            $icondata .= chr($ImageHeights[$key]);
                            $icondata .= chr($totalcolors[$key]);
                            $icondata .= "\x00";
                            $icondata .= "\x01\x00";
                            $icondata .= chr($bpp[$key]) . "\x00";
                            $dwBytesInRes = 40 + strlen($icXOR[$key]) + strlen($icAND[$key]);
                            $icondata .= $this->LittleEndian2String($dwBytesInRes, 4);
                            $icondata .= $this->LittleEndian2String($dwImageOffset, 4);
                            $dwImageOffset += strlen($BitmapInfoHeader[$key]);
                            $dwImageOffset += strlen($icXOR[$key]);
                            $dwImageOffset += strlen($icAND[$key]);
                        }

                        foreach ($gd_image_array as $key => $gd_image) {
                            $icondata .= $BitmapInfoHeader[$key];
                            $icondata .= $icXOR[$key];
                            $icondata .= $icAND[$key];
                        }
                        return $icondata;
                    }

                    function LittleEndian2String($number, $minbytes = 1)
                    {
                        $intstring = '';
                        while ($number > 0) {
                            $intstring = $intstring . chr($number & 255);
                            $number >>= 8;
                        }
                        return str_pad($intstring, $minbytes, "\x00", STR_PAD_RIGHT);
                    }

                    function GetPixelColor(&$img, $x, $y)
                    {
                        if (!is_resource($img) && !($img instanceof \GdImage)) {
                            return false;
                        }
                        return ImageColorsForIndex($img, ImageColorAt($img, $x, $y));
                    }
                }

                $icon      = new phpthumb_ico();
                $gd_image_array = array($resize_im);
                $icon_data = $icon->GD2ICOstring($gd_image_array);
                imagedestroy($resize_im);

                header("Accept-Ranges: bytes");
                header("Accept-Length: " . strlen($icon_data));
                header("Content-Type: application/octet-stream");
                header('Content-Disposition: attachment; filename="favicon.ico"');
                echo $icon_data;
                exit;
            }
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $typeinfo = "图片未上传！请选择要转换为ICO图标的文件！";
}
?>
