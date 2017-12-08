<?php
/**
 * File: CernBoxAuthHooks.php
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
namespace OCA\CernBoxAuth\Hooks;

use Firebase\JWT\JWT;



class CernBoxAuthHooks {
	private $signKey;
	private $cookieName;
	private $userSession;

	public function __construct($userSession) {
		$this->userSession = $userSession;
		$this->signKey = \OC::$server->getConfig()->getSystemValue("cbox.auth.signkey", "changeme");
		$this->cookieName = \OC::$server->getConfig()->getSystemValue("cbox.auth.cookiename", 'cbox_auth_token');
	}

	public function register() {
			$callback = function ($user) {
				$iat = time();
				$expires = $iat + \OC::$server->getConfig()->getSystemValue('remember_login_cookie_lifetime', 60 * 60 * 24 * 15);

				$token = array(
				    "iss" => 'cernbox.cern.ch',
				    "iat" => $iat,
					"exp" => $expires,
					"username" => $user->getUID(),
				);

				$jwt = JWT::encode($token, $this->signKey);

				$secureCookie = \OC::$server->getRequest()->getServerProtocol() === 'https';
				setcookie($this->cookieName, $jwt, $expires, '/', '', $secureCookie, true);
			};
			$this->userSession->listen('\OC\User', 'postLogin', $callback);
	}
}
