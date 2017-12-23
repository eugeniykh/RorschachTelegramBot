<?php

$json = json_decode(file_get_contents('php://input'));

require 'src/Telegram.php';

$token = '<!--- HERE YOUR TOKEN TELEGRAM LOCATED -->'; // HERE YOUR TOKEN
// You will need a file called quotes (without extension)
// from there it will take the quotes and send them to your subscribers :)

$Telegram = new telegramBot($token);

header('Content-type: application/json');

$Telegram->sendChatAction($json->chat_id, "upload_photo");

$x = 450;
$y = 450;

$image = array();

for ($t = 0; $t < 8; $t++) {
    for ($i = $x; $i >= 0; $i--) {
        for ($j = 0; $j <= $y; $j++) {
            if ($t % 2 == 1) {
                if ($i == $x / 2 && $j == $y / 2) {
                    $image[$i][$j] = 0;
                } else {
                    if (isset($image[$i][$j]) && $image[$i][$j] == 0) {

                    } else {
                        if (isset($image[$i][$j - 1]) && $image[$i][$j - 1] == 0) {
                            $image[$i][$j] = (rand(0, 350) < 200) ? 1 : 0;
                        } elseif (isset($image[$i - 1][$j]) && $image[$i - 1][$j] == 0) {
                            $image[$i][$j] = (rand(0, 350) < 200) ? 1 : 0;
                        } elseif (isset($image[$i][$j + 1]) && $image[$i][$j + 1] == 0) {
                            $image[$i][$j] = (rand(0, 350) < 200) ? 1 : 0;
                        } elseif (isset($image[$i + 1][$j]) && $image[$i + 1][$j] == 0) {
                            $image[$i][$j] = (rand(0, 350) < 200) ? 1 : 0;
                        } elseif (isset($image[$i - 1][$j - 1]) && $image[$i - 1][$j - 1] == 0) {
                            $image[$i][$j] = (rand(0, 350) < 200) ? 1 : 0;
                        } elseif (isset($image[$i - 1][$j + 1]) && $image[$i - 1][$j + 1] == 0) {
                            $image[$i][$j] = (rand(0, 350) < 200) ? 1 : 0;
                        } elseif (isset($image[$i + 1][$j - 1]) && $image[$i + 1][$j - 1] == 0) {
                            $image[$i][$j] = (rand(0, 350) < 200) ? 1 : 0;
                        } elseif (isset($image[$i + 1][$j + 1]) && $image[$i + 1][$j + 1] == 0) {
                            $image[$i][$j] = (rand(0, 350) < 200) ? 1 : 0;
                        } else {
                            $image[$i][$j] = 1;
                        }
                    }
                }
            } else {
                if ($i == $x / 2 && $j == $y / 2) {
                    $image[$j][$i] = 0;
                } else {
                    if (isset($image[$j][$i]) && $image[$j][$i] == 0) {

                    } else {
                        if (isset($image[$j][$i - 1]) && $image[$j][$i - 1] == 0) {
                            $image[$j][$i] = (rand(0, 350) < 200) ? 1 : 0;
                        } elseif (isset($image[$j - 1][$i]) && $image[$j - 1][$i] == 0) {
                            $image[$j][$i] = (rand(0, 350) < 200) ? 1 : 0;
                        } elseif (isset($image[$j][$i + 1]) && $image[$j][$i + 1] == 0) {
                            $image[$j][$i] = (rand(0, 350) < 200) ? 1 : 0;
                        } elseif (isset($image[$j + 1][$i]) && $image[$j + 1][$i] == 0) {
                            $image[$j][$i] = (rand(0, 350) < 200) ? 1 : 0;
                        } elseif (isset($image[$j - 1][$i - 1]) && $image[$j - 1][$i - 1] == 0) {
                            $image[$j][$i] = (rand(0, 350) < 200) ? 1 : 0;
                        } elseif (isset($image[$j - 1][$i + 1]) && $image[$j - 1][$i + 1] == 0) {
                            $image[$j][$i] = (rand(0, 350) < 200) ? 1 : 0;
                        } elseif (isset($image[$j + 1][$i - 1]) && $image[$j + 1][$i - 1] == 0) {
                            $image[$j][$i] = (rand(0, 350) < 200) ? 1 : 0;
                        } elseif (isset($image[$j + 1][$i + 1]) && $image[$j + 1][$i + 1] == 0) {
                            $image[$j][$i] = (rand(0, 350) < 200) ? 1 : 0;
                        } else {
                            $image[$j][$i] = 1;
                        }
                    }
                }
            }
        }
    }
}

$im = imagecreatetruecolor($x, $y);
for ($i = $x; $i >= 0; $i--) {
    for ($j = 0; $j <= $y; $j++) {
        $colorImage = $image[$i][$j] * 255;
        $color = imagecolorallocate($im, $colorImage, $colorImage, $colorImage);
        imagesetpixel($im, $x - $i, $x - $j, $color);
        imagesetpixel($im, $i, $y - $j, $color);
    }
}

imagejpeg($im, "image.jpg");

imagedestroy($im);

$bot_url = "https://api.telegram.org/bot{$token}/";
$url = $bot_url . "sendPhoto?chat_id=" . $json->chat_id;

$post_fields = array('chat_id' => $json->chat_id,
    'photo' => new CURLFile(realpath(__DIR__ . "/image.jpg"))
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type:multipart/form-data"
));
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
echo curl_exec($ch);
