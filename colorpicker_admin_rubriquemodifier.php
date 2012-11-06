<?php
require_once 'pre.php';
require_once 'auth.php';
include_once realpath(dirname(__FILE__)) . '/../../../fonctions/authplugins.php';
autorisation('colorpicker');
require_once realpath(dirname(__FILE__)) . '/Colorpicker.class.php';

// Si aucune ref n'est transmise, ou si le produit n'existe pas, on arrête là le massacre!
if(empty($_REQUEST['id'])) return false;
$rubrique = new Rubrique();
if(!$rubrique->charger($_REQUEST['id'])) return false;

// langue
$lang=1;
if(!empty($_GET['lang'])) $lang=$_GET['lang'];

$colorpicker = new Colorpicker();
?>

<style type="text/css">
  .colorpicker_wrap p {background:#9EB0BE; margin:0 !important; padding:5px 10px !important}
</style>
<link rel="stylesheet" media="screen" type="text/css" href="../client/plugins/colorpicker/jquery.colorpicker/css/colorpicker.css" />
<script type="text/javascript" src="../client/plugins/colorpicker/jquery.colorpicker/colorpicker.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $('.colorpicker_input').each(function() {
        $this = $(this);
        $this.ColorPicker({
            onSubmit: function(hsb, hex, rgb, el) {
                $(el).val(hex);
                $(el).ColorPickerHide();
            },
            onBeforeShow: function () {
                $(this).ColorPickerSetColor(this.value);
            },
            onChange: function (hsb, hex, rgb) {
                $this.val(hex);
                $this.css('background-color', '#'+hex);
            }
        });
    });
});
</script>

<div class="entete">
    <div class="titre" style="cursor:pointer" onclick="$('#colorpicker_pliant').show('slow');">COULEUR</div>
    <div class="fonction_valider"><a href="#" onclick="javascript:document.getElementById('formulaire').submit(); return false;">VALIDER LES MODIFICATIONS</a></div>
</div>
<div class="blocs_pliants_prod colorpicker_wrap" id="colorpicker_pliant">
    <p><label for="colorpicker_value">Couleur : <span></span></label>
        #<input type="text" name="colorpicker_value" id="colorpicker_value"
                value="<?php echo $colorpicker->getColor('rubrique', $rubrique->id); ?>"
                class="colorpicker_input" style="background-color:#<?php echo $colorpicker->getColor('rubrique', $rubrique->id); ?>"
            />
    </p>
</div>