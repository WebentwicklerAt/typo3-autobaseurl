<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

  ## Registering hooks
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/mod/tools/em/index.php']['tsStyleConfigForm'][] = 'tx_autobaseurl_extconf->tsStyleConfigForm';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'tx_autobaseurl_tcemainprocdm';
