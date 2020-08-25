<?php
include __DIR__ . '/../../../wp-load.php';
require __DIR__ . '/vendor/autoload.php';

use Vimeo\Vimeo;

?>
<div id="player"></div>
<div id="list">
    <script>

        url = "";
        jQuery(document).ready(function () {
            url = window.location.pathname;
            jQuery('.groep').click(function () {
                var idvideo = jQuery(this).attr('id');
                jQuery('#list').html('<center><img src="<?= plugin_dir_path( __FILE__ ); ?>images/loading.gif" /></center>');
                jQuery.get("<?php echo plugin_dir_path( __FILE__ ); ?>gemistgroup.php", {id: idvideo}, function (data) {
                    history.pushState('data', '', '?groep=' + idvideo);
                    jQuery('#list').html(data);
                });
            });

            var id = getUrlParameter("dienst");
            if (typeof id != "undefined") {
                showVideo(id);
            }
        });

        function showVideo(id) {
            data = '<iframe src="https://player.vimeo.com/video/' + id + '?byline=0&amp;portrait=0&amp;autoplay=1;api=1;player_id=test" width="510" height="382,5" frameborder="0"></iframe><div id="div_download">';
            jQuery("#player").html(data);
            jQuery("#player").fadeIn(1);
            jQuery("#div_download").show();
        }

        function getUrlParameter(sParam) {
            var sPageURL = window.location.search.substring(1);
            var sURLVariables = sPageURL.split('&');
            for (var i = 0; i < sURLVariables.length; i++) {
                var sParameterName = sURLVariables[i].split('=');
                if (sParameterName[0] == sParam) {
                    return sParameterName[1];
                }
            }
        }
    </script>
    <?php
    $error = 0;
    echo "<table width=\"100%\">";

    function getData($page)
    {
        $return = 0;
        $username = esc_attr(get_option('vimeo_username'));
        $userid = esc_attr(get_option('vimeo_user_id'));

        $client_id = esc_attr(get_option('vimeo_client_id'));
        $client_secret = esc_attr(get_option('vimeo_client_secret'));
        $access_token = esc_attr(get_option('vimeo_access_token'));

        $client = new Vimeo($client_id, $client_secret, $access_token);

        $response = $client->request('/users/' . $userid . '/groups', ["page" => $page], 'GET');
        $groups = $response["body"]["data"];
        //Draw Table header
        echo '<tr>';
        echo '<td></td>';
        echo '<td>Groep</td>';
        echo '<td>Aantal opnames</td>';
        echo '</tr>';
        foreach ($groups as $group) {
            $uriparts = explode("/", $group["uri"]);
            echo '<tr>';
            echo '<td><img src="' . $group["pictures"]["sizes"][0]["link"] . '" /></td>'
                . '<td><a class="groep" href="javascript:void(0)" id="' . end($uriparts) . '">' . $group["name"] . '</a></td>'
                . '<td>' . $group["metadata"]["connections"]["videos"]["total"] . '</td>';
            echo '</tr>';
        }

        return $return;
    }

    $error = getData(1);
    $error = getData(2);
    $error = getData(3);
    echo '</table>';

    if ($error > 0) {
        echo '<div class="errorbox">Geen diensten gevonden</div>';
    }
    ?>
</div>