<?php

/*
 *  This file is part of the JonathanHeilmann\JhMagnificpopup extension under GPLv2 or later.
 *
 *  For the full copyright and license information, please read the
 *  LICENSE.md file that was distributed with this source code.
 */

namespace JonathanHeilmann\JhMagnificpopup\Hooks;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/***************************************************************
 *  Copyright notice
 *
 *  Original: (c) 2009 Juergen Furrer <juergen.furrer@gmail.com>
 *				EXT:jfmulticontent
 *	 Edited: (c) 2013-2018 Jonathan Heilmann <mail@jonathan-heilmann.de>
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
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * This class implements a hook to TCEmain to ensure that IRRE data is correctly
 * inserted to pages (changes the colPos).
 *
 * @author     Jonathan Heilmann <mail@jonathan-heilmann.de>
 */
class UpdateColPosHook
{
    /**
     * Checks if the colPos will be manipulate
     *
     * @param array $incomingFieldArray
     * @param string $table
     * @param int $id
     * @param \TYPO3\CMS\Core\DataHandling\DataHandler $pObj
     * @see tx_templavoila_tcemain::processDatamap_afterDatabaseOperations()
     */
    public function processDatamap_preProcessFieldArray(array &$incomingFieldArray, $table, $id, DataHandler &$pObj)
    {
        if ($incomingFieldArray['list_type'] != 'jhmagnificpopup_pi1') {
            if (is_array($pObj->datamap['tt_content'])) {
                foreach ($pObj->datamap['tt_content'] as $key => $val) {
                    if (!is_array($val['pi_flexform'])) {
                        $val['pi_flexform'] = GeneralUtility::xml2array($val['pi_flexform']);
                    }
                    if ($val['list_type'] == 'jhmagnificpopup_pi1' && isset($val['pi_flexform']['data']['sDEF']['lDEF']['settings.contenttype']['vDEF']) && $val['pi_flexform']['data']['sDEF']['lDEF']['settings.contenttype']['vDEF'] == 'inline') {
                        // Change the colPos of the IRRE tt_content values
                        $confArr = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('jh_magnificpopup');
                        $incomingFieldArray['colPos'] = $confArr['colPosOfIrreContent'];
                    }
                }
            }
        }
    }
}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/jh_magnificpopup/Classes/Hooks/class.tx_jhmagnificpopup_tcemain.php']) {
    include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/jh_magnificpopup/Classes/Hooks/class.tx_jhmagnificpopup_tcemain.php']);
}
