<?php

// Source = Görsel TMP_NAME
// DEST = Kaydedilecek Yer
// QUALİTY = 0-100 arası bir değer

static function compressImage($source, $destination, $quality) {

    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source);

    elseif ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($source);

    elseif ($info['mime'] == 'image/webp')
        $image = imagecreatefromwebp($source);

    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source);

   return imagejpeg($image, $destination, $quality);

}

static function imageSave($photoName,$photoDestination,$resize,$resizeSize = null)
{
    $fileError = $_FILES[$photoName]["error"];
    $fileName = $_FILES[$photoName]["name"];
    $fileSize = $_FILES[$photoName]["size"];
    $fileTempName = $_FILES[$photoName]["tmp_name"];
    $fileType = $_FILES[$photoName]["type"];

    $files_types = ["image/jpeg","image/png","image/jpg","","image/webp"];

    if($fileError == 0)
    {
        if($fileSize > (1024*1024*5))
        {
            helper::flashData(["CRUDno" => "Fotoğraf 5mb'dan büyük olamaz !"]);
        }
        else
        {
            if(!in_array($fileType, $files_types))
            {
                helper::flashData(["CRUDno" => "Fotoğraf uzantısı png,jpeg,jpg veya webp olmak zorundadır !"]);
            }
            else
            {
                $file1Uzanti = explode(".", $fileName);
                $file1Uzantii = $file1Uzanti[count($file1Uzanti) -1 ];
                $file1NewName = $file1Uzanti[count($file1Uzanti) -2 ].rand(0,9999).".".$file1Uzantii;

                $photoNewDestination = $photoDestination.$file1NewName;

                if($resize == 1)
                {
                    $file1Check = self::compressImage($fileTempName,$photoNewDestination,$resizeSize);
                    return ["check"=>$file1Check, "name" =>$file1NewName];
                    // return $file1Check diyerek sadece kaydedilme durumunu return edebilirsiniz
                }
                elseif($resize == 0)
                {
                    $file1Check = copy($fileTempName, $photoDestination.$file1NewName);
                    return ["check"=>$file1Check, "name" =>$file1NewName];
                }

            }
        }
    }
}


?>