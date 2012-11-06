<?php
include_once(realpath(dirname(__FILE__)) . "/../../../classes/PluginsClassiques.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../../classes/Produit.class.php");

class Colorpicker extends PluginsClassiques{

    const TABLE = 'colorpicker';
    public $defaultColor = '8bc43f';

    /**
     * @see PluginsClassiques::init()
     */
    function init() {
        $this->query("
            CREATE TABLE IF NOT EXISTS `" .self::TABLE. "` (
              `object` varchar(15) NOT NULL,
              `object_id` int(10) unsigned NOT NULL,
              `color` varchar(6) NOT NULL COMMENT 'hexa',
              UNIQUE KEY `object` (`object`,`object_id`)
            ) DEFAULT CHARSET=utf8;
        ");
    }

    /**
     * @see PluginsClassiques::post()
     * #COLOR(produit,id_produit)
     */
    function post(){
        global $res;
        preg_match_all("`\#COLOR\(([^\,]*)\,([^\)]*)\)`", $res, $cut);
        $tab1 = "";
        $tab2 = "";
        for($i=0; $i<count($cut[0]); $i++){
            $tab1[$i] = $cut[0][$i];
            $tab2[$i] = $this->getColor($cut[1][$i], $cut[2][$i]);
        }
        $res = str_replace($tab1, $tab2, $res);
    }

    /**
     * @see PluginsClassiques::modprod()
     * @param $produit
     */
    function modprod($produit) {
        if(isset($_POST['colorpicker_value'])) $this->updateColor('produit', $produit->id, $_POST['colorpicker_value']);
    }
    function modrub($rubrique) {
        if(isset($_POST['colorpicker_value'])) $this->updateColor('rubrique', $rubrique->id, $_POST['colorpicker_value']);
    }

    public function getColor($type, $id) {
        $result = $this->defaultColor;

        $req = $this->query('
            SELECT color FROM ' . self::TABLE . '
            WHERE
                object="' . mysql_real_escape_string($type) . '"
                AND object_id=' . $id);
        $row = mysql_fetch_assoc($req);
        if(!empty($row['color'])) $result = $row['color'];
        return $result;
    }

    public function updateColor($type, $id, $color) {
        if(!preg_match('/^[0-9]{1,}$/', $id)) return false;
        if(!preg_match('/^[a-zA-Z0-9]{6}$/', $color)) $color=$this->defaultColor;

        $this->query('REPLACE INTO ' . self::TABLE . ' VALUES(
            "' . mysql_real_escape_string($type) . '",
            ' . $id . ',
            "' . mysql_real_escape_string($color) . '"
        )');
    }


}
