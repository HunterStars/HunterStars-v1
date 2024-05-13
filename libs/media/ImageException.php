<?php

	namespace HS\libs\media;

	use Throwable;

	class ImageException extends \Exception
	{
		//Constants
		const UNKNOWN = 0;
		const NOT_FOUND = 1;
		const UNSUPPORTED = 2;
		const THUMB_NOT_NEEDED = 3;
		const DIR_NOT_CREATED = 4;

		//public
		public function __construct(string $path, $code = 0, Throwable $previous = NULL)
		{
			$message = match ($code) {
				self::NOT_FOUND => "La imagen no existe o la ruta no es valida.",
				self::UNSUPPORTED => 'La imagen no pudo ser abierta, probablemente esta no posea una extension soportada por la aplicacion.',
				self::THUMB_NOT_NEEDED => 'La miniatura de la imagen no fue creada, ya que no era necesario.',
				self::DIR_NOT_CREATED => 'El directorio en donde se guardaria la imagen no existe y no pudo ser creado.',
				default => 'Ha ocurrido un error al trabajar con la imagen, aunque se desconoce cual sea.',
			};

			$message .= " $path";

			parent::__construct($message, $code, $previous);
		}
	}