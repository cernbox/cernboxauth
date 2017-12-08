<?php
/**
 * File: Application.php
 * Author: labkode - CERN
 *
 */

/**
 * CERNBox - CERN Cloud Sync and Share Platform
 * Copyright (C) 2017  CERN
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU Affero General Public License as
 *   published by the Free Software Foundation, either version 3 of the
 *   License, or (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU Affero General Public License for more details.
 *
 *   You should have received a copy of the GNU Affero General Public License
 *   along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */
namespace OCA\CernBoxAuth\AppInfo;

use OCA\CernBoxAuth\Hooks\CernBoxAuthHooks;
use OCP\AppFramework\App;

class Application extends App {
	public function __construct($appName, array $urlParams = array()) {
		parent::__construct($appName, $urlParams);
		$container = $this->getContainer();
		$container->registerService('CernBoxAuthHooks', function($c) {
			return new CernBoxAuthHooks($c->query('ServerContainer')->getUserSession());
		});
	}
}