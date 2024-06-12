<?php
$output = "";
if ($_FILES['upimage'] != '') {
    if (isset($_FILES['upimage']['tmp_name']) && $_FILES['upimage']['tmp_name'] && is_uploaded_file($_FILES['upimage']['tmp_name'])) {
        $fileext = array("image/jpg", "image/jpeg", "image/png", "image/pjpeg", "image/gif", "image/x-png");
        if (!in_array($_FILES['upimage']['type'], $fileext)) {
            $typeinfo = "图片格式错误！目前仅支持jpg，gif，png格式的图片。";
        } else {
            if ($_FILES['upimage']['size'] > 1024000) {
                $typeinfo = "图片尺寸超出限制！请确保图片大小在1MB以内。";
            } else {
                if ($im = @imagecreatefrompng($_FILES['upimage']['tmp_name']) or $im = @imagecreatefromgif($_FILES['upimage']['tmp_name']) or $im = @imagecreatefromjpeg($_FILES['upimage']['tmp_name'])) {
                    $imginfo = @getimagesize($_FILES['upimage']['tmp_name']);
                    if (!is_array($imginfo)) {
                        $typeinfo = "格式转换失败！请检测源文件无误后重试。";
                    }

                    switch ($_POST['size']) {
                        case 1:
                            $resize_im = @imagecreatetruecolor(16, 16);
                            $size = 16;
                            break;
                        case 2:
                            $resize_im = @imagecreatetruecolor(32, 32);
                            $size = 32;
                            break;
                        case 3:
                            $resize_im = @imagecreatetruecolor(48, 48);
                            $size = 48;
                            break;
                        case 4:
                            $resize_im = @imagecreatetruecolor(64, 64);
                            $size = 64;
                            break;
                        case 5:
                            $resize_im = @imagecreatetruecolor(128, 128);
                            $size = 128;
                            break;
                        case 6:
                            $resize_im = @imagecreatetruecolor(256, 256);
                            $size = 256;
                            break;
                        default:
                            $resize_im = @imagecreatetruecolor(64, 64);
                            $size = 64;
                            break;
                    }

                    // 保留透明背景
                    imagealphablending($resize_im, false);
                    imagesavealpha($resize_im, true);
                    $trans_color = imagecolorallocatealpha($resize_im, 0, 0, 0, 127);
                    imagefill($resize_im, 0, 0, $trans_color);

                    imagecopyresampled($resize_im, $im, 0, 0, 0, 0, $size, $size, $imginfo[0], $imginfo[1]);

                    // phpthumb_ico是生成ICO图标的类
                    class phpthumb_ico
                    {
                        function phpthumb_ico()
                        {
                            return true;
                        }

                        function GD2ICOstring(&$gd_image_array)
                        {
                            $icANDmask = array();
                            foreach ($gd_image_array as $key => $gd_image) {
                                $ImageWidths[$key] = ImageSX($gd_image);
                                $ImageHeights[$key] = ImageSY($gd_image);
                                $bpp[$key] = ImageIsTrueColor($gd_image) ? 32 : 24;
                                $totalcolors[$key] = ImageColorsTotal($gd_image);
                                $icXOR[$key] = '';

                                for ($y = $ImageHeights[$key] - 1; $y >= 0; $y--) {
                                    for ($x = 0; $x < $ImageWidths[$key]; $x++) {
                                        $argb = $this->GetPixelColor($gd_image, $x, $y);
                                        $a = round(255 * ((127 - $argb['alpha']) / 127));
                                        $r = $argb['red'];
                                        $g = $argb['green'];
                                        $b = $argb['blue'];

                                        if ($bpp[$key] == 32) {
                                            $icXOR[$key] .= chr($b) . chr($g) . chr($r) . chr($a);
                                        } else if ($bpp[$key] == 24) {
                                            $icXOR[$key] .= chr($b) . chr($g) . chr($r);
                                        }
                                        if ($a < 128) {
                                            @$icANDmask[$key][$y] .= '1';
                                        } else {
                                            @$icANDmask[$key][$y] .= '0';
                                        }
                                    }
                                    // mask bits are 32-bit aligned per scanline
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
                                // Bitmap Info Header - 40 bytes
                                $BitmapInfoHeader[$key] = '';
                                $BitmapInfoHeader[$key] .= "\x28\x00\x00\x00"; // DWORD biSize;
                                $BitmapInfoHeader[$key] .= $this->LittleEndian2String($ImageWidths[$key], 4); // LONG biWidth;
                                // The biHeight member specifies the combined
                                // height of the XOR and AND masks.
                                $BitmapInfoHeader[$key] .= $this->LittleEndian2String($ImageHeights[$key] * 2, 4); // LONG biHeight;
                                $BitmapInfoHeader[$key] .= "\x01\x00"; // WORD biPlanes;
                                $BitmapInfoHeader[$key] .= chr($bpp[$key]) . "\x00"; // wBitCount;
                                $BitmapInfoHeader[$key] .= "\x00\x00\x00\x00"; // DWORD biCompression;
                                $BitmapInfoHeader[$key] .= $this->LittleEndian2String($biSizeImage, 4); // DWORD biSizeImage;
                                $BitmapInfoHeader[$key] .= "\x00\x00\x00\x00"; // LONG biXPelsPerMeter;
                                $BitmapInfoHeader[$key] .= "\x00\x00\x00\x00"; // LONG biYPelsPerMeter;
                                $BitmapInfoHeader[$key] .= "\x00\x00\x00\x00"; // DWORD biClrUsed;
                                $BitmapInfoHeader[$key] .= "\x00\x00\x00\x00"; // DWORD biClrImportant;
                            }

                            $icondata = "\x00\x00"; // idReserved; // Reserved (must be 0)
                            $icondata .= "\x01\x00"; // idType; // Resource Type (1 for icons)
                            $icondata .= $this->LittleEndian2String(count($gd_image_array), 2); // idCount; // How many images?
                            $dwImageOffset = 6 + (count($gd_image_array) * 16);

                            foreach ($gd_image_array as $key => $gd_image) {
                                // ICONDIRENTRY idEntries[1]; // An entry for each image (idCount of 'em)
                                $icondata .= chr($ImageWidths[$key]); // bWidth; // Width, in pixels, of the image
                                $icondata .= chr($ImageHeights[$key]); // bHeight; // Height, in pixels, of the image
                                $icondata .= chr($totalcolors[$key]); // bColorCount; // Number of colors in image (0 if >=8bpp)
                                $icondata .= "\x00"; // bReserved; // Reserved ( must be 0)
                                $icondata .= "\x01\x00"; // wPlanes; // Color Planes
                                $icondata .= chr($bpp[$key]) . "\x00"; // wBitCount; // Bits per pixel
                                $dwBytesInRes = 40 + strlen($icXOR[$key]) + strlen($icAND[$key]);
                                $icondata .= $this->LittleEndian2String($dwBytesInRes, 4); // dwBytesInRes; // How many bytes in this resource?
                                $icondata .= $this->LittleEndian2String($dwImageOffset, 4); // dwImageOffset; // Where in the file is this image?
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
                            if (!is_resource($img)) {
                                return false;
                            }
                            return @ImageColorsForIndex($img, @ImageColorAt($img, $x, $y));
                        }
                    }

                    $icon = new phpthumb_ico();
                    $gd_image_array = array($resize_im);
                    $icon_data = $icon->GD2ICOstring($gd_image_array);
                    header("Accept-Ranges: bytes");
                    header("Accept-Length: " . strlen($icon_data));
                    header("Content-type: application/octet-stream");
                    header("Content-Disposition: attachment; filename=" . 'favicon.ico');
                    echo $icon_data;
                    exit;
                } else {
                    $typeinfo = "生成ICO图标失败！请重试。";
                }
            }
        }
    } else {
        $typeinfo = "图片未上传！请选择要转换为ICO图标的文件！";
    }
}
?>