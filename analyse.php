<?php

$images = glob("*.jpeg");

foreach ($images as $image) {
    $image = basename($image, '.jpeg');

    // read image
    $img = imagecreatefromjpeg("$image.jpeg");

    // normalise
    // TODO if needed

    // crop the region
    $crop = 113;
    $img2 = imagecrop($img, ['x' => 13, 'y' => 13, 'width' => 774, 'height' => $crop]);
    if ($img2 === false) {
        imagedestroy($img);
        print "crop failed\n";
        exit(1);
    }
    imagejpeg($img2, "temp/{$image}_crop.jpeg");
    imagedestroy($img);

    // binarisation
    $width = imagesx($img2);
    $height = imagesy($img2);
    $img3 = imagecreatetruecolor($width, $height);
    $red = imagecolorallocate($img3, 255, 0, 0);
    $blue = imagecolorallocate($img3, 0, 0, 255);
    $white = imagecolorallocate($img3, 255, 255, 255);

    for ($i = 0; $i < $width; $i++) {
        for ($j = 0; $j < $height; $j++) {
            $rgb = imagecolorat($img2, $i, $j);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            // red
            if ($r > $g + $b) {
                imagesetpixel($img3, $i, $j, $red);
                continue;
            }

            // blue
            if ($b > $r + $g) {
                imagesetpixel($img3, $i, $j, $blue);
                continue;
            }

            imagesetpixel($img3, $i, $j, $white);
        }
    }
    imagejpeg($img3, "temp/{$image}_binary.jpeg");
    imagedestroy($img2);

    // convert
    $columns = 40;
    $rows = 6;
    $cell_width = $width / $columns;
    $cell_height = $height / $rows;
    $cells = [];
    for ($col = 0; $col < $columns; $col++) {
        $cells[$col] = [];
        for ($row = 0; $row < $rows; $row++) {
            $num_red = 0;
            $num_blue = 0;
            for ($i = 0; $i < $cell_width; $i++) {
                for ($j = 0; $j < $cell_height; $j++) {
                    $x = intval($col * $cell_width + $i);
                    $y = intval($row * $cell_height + $j);
                    $rgb = imagecolorat($img3, $x, $y);
                    $r = ($rgb >> 16) & 0xFF;
                    $g = ($rgb >> 8) & 0xFF;
                    $b = $rgb & 0xFF;
                    if ($r > 200 && $b < 100) {
                        $num_red++;
                        continue;
                    }
                    if ($r < 100 && $b > 200) {
                        $num_blue++;
                    }
                }
            }
            if ($num_red > 50) {
                $cell = 1;
            } elseif ($num_blue > 50) {
                $cell = 2;
            } else {
                $cell = 0;
            }
            $cells[$col][$row] = $cell;
        }
    }

    $file = fopen("$image.txt", 'w+');
    print "Results: \n";
    for ($row = 0; $row < $rows; $row++) {
        print " row " . ($row + 1) . " -- | ";
        for ($col = 0; $col < $columns; $col++) {
            print $cells[$col][$row] . " ";
            fprintf($file, "%d ", $cells[$col][$row]);
        }
        print "|\n";
        fprintf($file, "\n");
    }
    imagedestroy($img3);
}

