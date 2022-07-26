<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://codestick.pl/
 * @since      1.0.1
 *
 * @package    Promocodes
 * @subpackage Promocodes/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <div id="icon-themes" class="icon32"></div>  
    <h2>Plugin Name Settings</h2>
    <?php settings_errors(); ?>  
    <form action="<?php echo esc_attr('admin-post.php'); ?>" method="post">
        <table id="promocodeTable" border="1">
            <thead>
                <tr>
                    <th width="5%">LP</th>
                    <th width="20%">Kod</th>
                    <th width="60%">Opis</th>
                    <!-- <th>Kategoria</th>
                    <th>Typ</th> -->
                    <th width="15%">Usuń</th>
                </tr>
            </thead>
            <tbody>
                <?php get_promocodes_table(); ?>
            </tbody>
        </table>
        <div class="addNewCode">
            <h3>Dodaj nowy kod</h3>
            <input type="hidden" name="action" value="promocode_actions" />
            <input type="text" name="codeName" placeholder="Kod" />
            <input type="text" name="codeDesc" placeholder="Opis" />
            <!-- <select name="codeCategory">
                <option value="CLIENT">CLIENT</option>
                <option value="PARTNER">PARTNER</option>
                <option value="INFLUENCER">INFLUENCER</option>
                <option value="EVENT">EVENT</option>
                <option value="VET">VET</option>
                <option value="BLACKWEEK">BLACKWEEK</option>
                <option value="Inna">Inna</option>
            </select>
            <select name="codeType">
                <option value="Zniżka na pierwsze zamówienie">Zniżka na pierwsze zamówienie</option>
                <option value="Zniżka na dwa pierwsze zamówienia">Zniżka na dwa pierwsze zamówienia</option>
            </select> -->
            <?php submit_button('Dodaj'); ?>
        </div>
    </form>
</div>