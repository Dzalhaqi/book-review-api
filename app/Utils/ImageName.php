<?php

namespace App\Utils;


class ImageName
{

  public static function generate($imageFile, $bookTitle)
  {
    $title = strtolower(str_replace(' ', '-', $bookTitle));
    $hashImageName = hash('sha256', auth()->id() . uniqid() . time() . $imageFile->getFilename());
    $imageName = "book-cover-{$title}-{$hashImageName}.{$imageFile->extension()}";

    return $imageName;
  }
}
