<?php

	namespace HS\app\models\client\items;

	class NovelStateItem
	{
		const ACTIVE = 1;
		const PAUSED = 2;
		const DROP = 3;
		const FINISHED = 4;

		public int $ID;
		public int $Count;

		public string $Name;
		public string $Icon;
		public string $Class;

		public function __construct(?int $ID = null) {
			if (!is_null($ID)) $this->ID = $ID;

			if (isset($this->ID)) {
				switch ($this->ID) {
					case NovelStateItem::ACTIVE:
						$this->Name = 'Activa';
						$this->Icon = 'play_circle_outline';
						$this->Class = 'active';
						break;
					case NovelStateItem::PAUSED:
						$this->Name = 'Pausada';
						$this->Icon = 'pause_circle_outline';
						$this->Class = 'paused';
						break;
					case NovelStateItem::DROP:
						$this->Name = 'Abandonada';
						$this->Icon = 'label_outline';
						$this->Class = 'dropped';
						break;
					case NovelStateItem::FINISHED:
						$this->Name = 'Finalizada';
						$this->Icon = 'label_outline';
						$this->Class = 'finished';
						break;
					default:
						$this->Name = '?';
						break;
				}
			}
		}
	}