<?php

namespace HS\libs\media;

use GdImage;
use HS\config\LogFile;
use HS\libs\helpers\IOUtils;
use HS\libs\helpers\Json;
use HS\libs\helpers\Logger;
use HS\libs\helpers\MimeType;
use HS\libs\io\Path;
use HS\libs\net\HttpResponse;
use const HS\APP_FILE_MODE;

class Image
{
    const FILE_LOG = 'img';

    private GdImage $Handle;
    private string $FileName;

    private function __construct(GdImage $resource, string $path)
    {
        $this->Handle = $resource;
        $this->FileName = $path;

        //Si el formato es PNG, establecer trasparencia en la imagen.
        if (pathinfo($path, PATHINFO_EXTENSION) === ImageFormat::PNG->value)
            $this->SetTransparency();
    }

    public function __destruct()
    {
        imagedestroy($this->Handle);
        unset($this->Handle);
    }

    /**
     * @throws ImageException
     */
    public static function FromFile(string $path): Image
    {
        //Verificando que la imagen exista.
        if (!file_exists($path))
            throw new ImageException($path, ImageException::NOT_FOUND);

        //Obteniendo instancia de la imagen
        $img = match (exif_imagetype($path)) {
            IMAGETYPE_BMP => imagecreatefrombmp($path),
            IMAGETYPE_GIF => imagecreatefromgif($path),
            IMAGETYPE_JPEG, IMAGETYPE_JPEG2000 => imagecreatefromjpeg($path),
            IMAGETYPE_PNG => imagecreatefrompng($path),
            default => false,
        };

        //Verificando si la imagen se abrió correctamente.
        if ($img === false) throw new ImageException($path, ImageException::UNSUPPORTED);

        //Devolviendo imagen.
        return new Image($img, $path);
    }

    public function GetWidth(): int
    {
        if (($width = imagesx($this->Handle)) === false) return 0;
        return $width;
    }

    public function GetHeight(): int
    {
        if (($height = imagesy($this->Handle)) === false) return 0;
        return $height;
    }

    /**
     * @throws ImageException
     */
    public function GetThumbnail(int $width, int $height, ?string $cacheDir): Image
    {
        //Verificando si se necesita miniatura.
        if ($width <= 0 && $height <= 0)
            throw new ImageException($this->FileName, ImageException::THUMB_NOT_NEEDED);

        //Obteniendo ancho y alto original.
        $source_width = $this->GetWidth();
        $source_height = $this->GetHeight();

        //Si se estableció un ancho y un alto sugerido.
        if ($width > 0 && $height > 0) {
            if ($source_width > $width)
                $height = self::CalcProportionalHeight($source_height, $source_width, $width);

            if ($source_height > $height)
                $width = self::CalcProportionalWidth($source_height, $source_width, $height);
        } //Si no se estableció un ancho o un alto.
        else {
            //Calculando ancho de la miniatura
            if ($width <= 0)
                $width = self::CalcProportionalWidth($source_height, $source_width, $height);

            //Calculando alto de la miniatura
            if ($height <= 0)
                $height = self::CalcProportionalHeight($source_height, $source_width, $width);
        }

        //Si la miniatura es más grande que la original, no es necesaria miniatura.
        if ($width >= $source_width && $height >= $source_height)
            throw new ImageException($this->FileName, ImageException::THUMB_NOT_NEEDED);

        //Construyendo ruta.
        $extension = pathinfo($this->FileName, PATHINFO_EXTENSION);

        //if (empty($cacheDir)) $cacheDir = pathinfo($this->FileName, PATHINFO_DIRNAME);
        if (!empty($cacheDir)) {
            $cacheName = Path::Combine($cacheDir, pathinfo($this->FileName, PATHINFO_FILENAME));
            $cacheName .= " ($width x $height)." . $extension;

            //Si existe la miniatura en el directorio
            try {
                return Image::FromFile($cacheName); //Devolverla
            } catch (ImageException $ex) {
                Logger::WriteException(LogFile::IMG, $ex);
            }
        }

        //Creando contenedor para la miniatura
        $thumb = imagecreatetruecolor($width, $height);

        //Si es una imagen png, arreglar transparencia.
        if ($extension == ImageFormat::PNG->value) $this->SetTransparency($thumb);

        //Generando miniatura
        imagecopyresampled($thumb, $this->Handle, 0, 0, 0, 0, $width, $height, $source_width, $source_height);

        //Creando imagen de la miniatura.
        $image = new Image($thumb, $cacheName ?? $this->FileName);

        //Guardando miniatura si no existe.
        if (!empty($cacheDir) && !file_exists($cacheName))
            $image->SaveTo($cacheDir, true);

        //Regresando la miniatura.
        return $image;
    }

    /**
     * @throws ImageException
     */
    public function Print(): void
    {
        $this->Save(null);
    }

    /**
     * @throws ImageException
     */
    public function SaveTo(string $dest_dir, bool $overwrite): void
    {
        //Creando directorio temporal si no existiera.
        if (!file_exists($dest_dir) && !is_dir($dest_dir)) {
            if (!mkdir($dest_dir, APP_FILE_MODE, true))
                throw new ImageException($dest_dir, ImageException::DIR_NOT_CREATED);
        }

        //Construyendo ruta.
        $path = Path::Combine($dest_dir, pathinfo($this->FileName, PATHINFO_BASENAME));

        //Guardando imagen si no existe.
        if ($overwrite || !file_exists($path))
            $this->Save($path);
    }

    //Métodos privados.

    /**
     * @throws ImageException
     */
    private function Save($filename): void
    {
        switch (ImageFormat::tryFrom(strtolower(pathinfo($filename ?? $this->FileName, PATHINFO_EXTENSION)))) {
            case ImageFormat::BMP:
                imagebmp($this->Handle, $filename, true);
                break;
            case ImageFormat::GIF:
                imagegif($this->Handle, $filename);
                break;
            case ImageFormat::JPG:
            case ImageFormat::JPEG:
                imagejpeg($this->Handle, $filename, 80);
                break;
            case ImageFormat::PNG:
                imagepng($this->Handle, $filename, 9);
                break;
            default:
                throw new ImageException($filename, ImageException::UNSUPPORTED);
        }
    }

    private function SetTransparency(GdImage $resource = null): void
    {
        if ($resource == null) $resource = $this->Handle;

        imagealphablending($resource, false);
        imagesavealpha($resource, true);
        $alpha = imagecolorallocatealpha($resource, 0, 0, 0, 127);
        imagefill($resource, 0, 0, $alpha);
    }

    //Métodos estáticos.
    public static function CalcProportionalWidth(int $sourceHeight, int $sourceWidth, int $height): int
    {
        //Calculando ancho de la miniatura
        $ratio = round(($height * 100) / $sourceHeight, 2, PHP_ROUND_HALF_UP);
        return round(($sourceWidth * $ratio) / 100, 0, PHP_ROUND_HALF_UP);
    }

    public static function CalcProportionalHeight(int $sourceHeight, int $sourceWidth, int $width): int
    {
        //Calculando ancho de la miniatura
        $ratio = round(($width * 100) / $sourceWidth, 2, PHP_ROUND_HALF_UP);
        return round(($sourceHeight * $ratio) / 100, 0, PHP_ROUND_HALF_UP);
    }

    public static function HTTPUploadTo(array $file, string $dirDest, string $newFileName): void
    {
        HttpResponse::SetContentType(MimeType::Json);

        //Error al subir archivo.
        if ($file['error'] !== 0) {
            HttpResponse::C500_INTERNAL_SERVER_ERROR->SetCode();
            die(Json::GetJsonError(1, 'Ha ocurrido un error al subir la imagen.'));
        } //Extensión del archivo no permitida.
        else if (!in_array(ImageFormat::tryFrom(strtolower(pathinfo($file['name'], PATHINFO_EXTENSION))), ImageFormat::cases())) {
            HttpResponse::C500_INTERNAL_SERVER_ERROR->SetCode();
            die(Json::GetJsonWarning(1, 'Formato de imagen no permitido.'));
        } //El tipo de archivo de imagen no es permitido.
        else if (!in_array(exif_imagetype($file['tmp_name']), [IMAGETYPE_BMP, IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_JPEG2000, IMAGETYPE_PNG])) {
            HttpResponse::C500_INTERNAL_SERVER_ERROR->SetCode();
            die(Json::GetJsonWarning(2, 'Formato de imagen no permitido.'));
        } else {
            try {
                //Abriendo archivo de imagen.
                $image = Image::FromFile($file['tmp_name']);
            } catch (ImageException $ex) {
                if ($ex->getCode() == ImageException::NOT_FOUND) {
                    //El archivo temporal de imagen no existe.
                    HttpResponse::C404_NOTFOUND->SetCode();
                    die(Json::GetJsonError(2, 'Ha ocurrido un error, la imagen no se subió correctamente.'));
                } else {
                    //Error desconocido al abrir imagen.
                    HttpResponse::C500_INTERNAL_SERVER_ERROR->SetCode();
                    die(Json::GetJsonError(3, 'Ha ocurrido un error, la imagen subida no tiene el formato correcto.'));
                }
            }

            try {
                //Creando directorio si no existe.
                IOUtils::CreateDirectory($dirDest, true);
            } catch (\Exception $ex) {
                //Error al crear el directorio para almacenar imágenes.
                HttpResponse::C500_INTERNAL_SERVER_ERROR->SetCode();
                die(Json::GetJsonError(4, 'Ha ocurrido un error interno, intente subir la imagen mas tarde.'));
            }

            try {
                //Generando imagen optimizada.
                $thumb = $image->GetThumbnail(2048, 2048, null);
                $thumb->Save(Path::Combine($dirDest, $newFileName));
            } catch (ImageException $ex) {
                if ($ex->getCode() == ImageException::THUMB_NOT_NEEDED) {
                    try {
                        $image->Save(Path::Combine($dirDest, $newFileName));
                    } catch (ImageException $ex) {
                        HttpResponse::C500_INTERNAL_SERVER_ERROR->SetCode();
                        die(Json::GetJsonError(5, 'Ha ocurrido un error interno, intente subir la imagen mas tarde.'));
                    }
                } else {
                    HttpResponse::C500_INTERNAL_SERVER_ERROR->SetCode();
                    die(Json::GetJsonError(6, 'Ha ocurrido un error interno, intente subir la imagen mas tarde.'));
                }
            }
        }
    }
}