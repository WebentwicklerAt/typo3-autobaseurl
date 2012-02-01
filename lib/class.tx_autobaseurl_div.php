<?php
/***************************************************************
*  Copyright notice
*
*  (c) Gernot Leitgab <leitgab@gmail.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

class tx_autobaseurl_div {
	protected $extKey = 'autobaseurl';
	protected $conf = array();
	
	public function __construct() {
		$this->conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);
	}
	
	public function setDefaultDomain($domain) {
		$this->conf['defaultDomain'] = $domain;
	}
	
	public function writeConstants() {
		$domains = $this->getDomainRecords();
		$constants = $this->getConstants($domains);
		t3lib_div::writeFileToTypo3tempDir(PATH_site . 'typo3temp/' . $this->extKey . '/constants.txt', $constants);
	}
	
	protected function getDomainRecords() {
		$domains = array();
		
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'domainName',
			'sys_domain',
			'hidden = 0',
			'',
			'sorting'
		);
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))	{
			$domains[] = $row['domainName'];
		}
		
		return $domains;
	}
	
	protected function getConstants($domains) {
		$constants = array();
		
		if (!empty($this->conf['defaultDomain'])) {
			// HTTP
			$constants[] = 'baseUrl = http://' . $this->conf['defaultDomain'] . '/';
			
			// HTTPS
			$constants[] = '[globalString = _SERVER|HTTPS = on]';
			$constants[] = '	baseUrl = https://' . $this->conf['defaultDomain'] . '/';
		}
		
		foreach ($domains as $domain) {
			// HTTP
			$constants[] = '[globalString = ENV:HTTP_HOST = ' . $domain . ']';
			$constants[] = '	baseUrl = http://' . $domain . '/';
			
			// HTTPS
			$constants[] = '[globalString = ENV:HTTP_HOST = ' . $domain . '] && [globalString = _SERVER|HTTPS = on]';
			$constants[] = '	baseUrl = https://' . $domain . '/';
		}
		
		if (count($constants)) {
			$constants[] = '[global]';
		}
		
		return implode("\n", $constants);
	}
	
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/autobaseurl/lib/class.tx_autobaseurl_div.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/autobaseurl/lib/class.tx_autobaseurl_div.php']);
}
