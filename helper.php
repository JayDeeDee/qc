<?php
/**
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();
require_once(DOKU_INC.'inc/plugin.php');

class helper_plugin_qc extends DokuWiki_Plugin {

    function tpl(){
        global $ACT,$INFO,$ID;
        if (!function_exists('gd_info')) {
            msg('You have to install php-gd lib to use the QC plugin.');
            return;
        }
        if($ACT != 'show' || !$INFO['exists']) return;
        if(p_get_metadata($ID, 'relation qcplugin_disabled')) return;
        if ($this->getConf('adminonly')) {
            if (!isset($_SERVER['REMOTE_USER']) || !auth_isadmin())
                return;
        }
        echo '<div id="plugin__qc__wrapper">';
        echo '<img src="'.DOKU_BASE.'lib/plugins/qc/icon.php?id='.$ID.'" width="600" height="25" alt="" id="plugin__qc__icon" />';
        echo '<div id="plugin__qc__out" style="display:none"></div>';
        echo '</div>';
    }


    function getQCData($theid){
        global $ID;
        $oldid = $ID;
        $ID = $theid;
        require_once DOKU_INC.'inc/parserutils.php';
        $data = unserialize(p_cached_output(wikiFN($ID), 'qc', $ID));
        $ID = $oldid;
        return $data;
    }

    /**
     * same function as tpl(), built markup contains additional data-attribute data-errors, which shows the current
     * error count
     */
    function tplErrorCount(){
        global $ACT,$INFO,$ID;
        if (!function_exists('gd_info')) {
            msg('You have to install php-gd lib to use the QC plugin.');
            return;
        }
        if($ACT != 'show' || !$INFO['exists']) return;
        if(p_get_metadata($ID, 'relation qcplugin_disabled')) return;
        if ($this->getConf('adminonly')) {
            if (!isset($_SERVER['REMOTE_USER']) || !auth_isadmin())
                return;
        }
        $qc_data =  $this->getQCData($ID);
        if($qc_data){
            $num = $qc_data[score];
        }
        echo '<div id="plugin__qc__wrapper" data-errors='.$num .'>';
        echo '<img src="'.DOKU_BASE.'lib/plugins/qc/icon.php?id='.$ID.'" width="600" height="25" alt="" id="plugin__qc__icon" />';
        echo '<div id="plugin__qc__out"></div>';
        echo '</div>';
    }

}
// vim:ts=4:sw=4:et:enc=utf-8:
