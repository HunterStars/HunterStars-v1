<?php

	namespace HS\libs\view;

	use HS\config\enums\SubDomains;
	use HS\libs\collection\PropertyCollection;
	use HS\libs\helpers\HTML;

	class ViewLayout extends PropertyCollection
	{
		public string $Title = '';

		public bool $ShowExtraMenu = true;
		public bool $ShowLateralMenu = true;
		public bool $ShowNotificationPanel = true;
		public PropertyCollection $Sections;

		//Privados.
		private array $scripts = [];
		private array $styles = [];
		private array $vendorStyles = [];

		//Constructor.
		public function __construct() {
			parent::__construct();

			$this->Sections = new PropertyCollection();
		}

		public function SetTitle(string $title): ViewLayout {
			$this->Title = $title;
			return $this;
		}

		public function AddScript(string|array $filename, SubDomains $domain = null): ViewLayout {
			$this->scripts[] = HTML::Scripts($filename, $domain);
			return $this;
		}

		public function AddModuleScript(string $filename, SubDomains $domain = null): ViewLayout {
			$this->scripts[] = HTML::ModuleScripts($filename, $domain);
			return $this;
		}

		public function AddVendorScript(string|array $filename, SubDomains $domain = null): ViewLayout {
			$this->scripts[] = HTML::VendorScripts($filename, $domain);
			return $this;
		}

		public function AddStyle(string|array $filename, SubDomains $domain = null): ViewLayout {
			$this->styles[] = HTML::Styles($filename, $domain);
			return $this;
		}

		public function AddVendorStyle(string|array $filename, SubDomains $domain = null): ViewLayout {
			$this->vendorStyles[] = HTML::VendorStyles($filename, $domain);
			return $this;
		}

		public function GetAllScripts(): string {
			return implode("\n", $this->scripts);
		}

		public function GetStyles(): string {
			return implode("\n", $this->styles);
		}

		public function GetVendorStyles(): string {
			return implode("\n", $this->vendorStyles);
		}

		public function AddSection(string $name, string $path): ViewLayout {
			$this->Sections->$name = $path;
			return $this;
		}

		public function HideLateralMenu(): ViewLayout {
			$this->ShowLateralMenu = false;
			return $this;
		}

		public function HideExtraMenu(): ViewLayout {
			$this->ShowExtraMenu = false;
			return $this;
		}

		public function HideNotificationPanel(): ViewLayout {
			$this->ShowNotificationPanel = false;
			return $this;
		}
	}