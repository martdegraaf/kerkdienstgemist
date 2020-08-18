<?php
include __DIR__ . '/../../../wp-load.php';
require __DIR__ . '/vendor/autoload.php';

use Vimeo\Vimeo;

//function plugins_url(){
//    return "localhost";
//}
?>
    <script>
        jQuery(document).ready(function () {
            console.log(url);
            jQuery(".play").click(function () {
                var random = Math.random();
                id = jQuery(this).attr("id");
                history.pushState('data', '', '?dienst=' + id);
                data = '<iframe src="https://player.vimeo.com/video/' + id + '?&autoplay=1;api=1;player_id=test" width="100%" height="382,5" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe><div id="div_download">';

                jQuery("#player").html(data);

                // $("#div_download").show();
                //
                // //aantal weergave
                // $.getJSON("https://vimeo.com/api/v2/video/"+id+".json", function(data){
                //     $("#div_download").html("Aantal hits: "+data[0].stats_number_of_plays);
                // });
            });

            //terugknop
            jQuery(".back").click(function () {
                jQuery('#list').html('<center><img src="/wp-content/plugins/kerkdienstgemist/images/loading.gif" /></center>');
                jQuery.get("/wp-content/plugins/kerkdienstgemist/gemist.snippet.php", function (data) {
                    history.pushState('data', '', url);
                    jQuery('#list').html(data);
                });
            });
        });

    </script>


<?php
$client_id = esc_attr(get_option('vimeo_client_id'));
$client_secret = esc_attr(get_option('vimeo_client_secret'));
$access_token = esc_attr(get_option('vimeo_access_token'));

$client = new Vimeo($client_id, $client_secret, $access_token);

$groupid = $_GET["id"];

$groupResponse = $client->request('/groups/' . $groupid, [], 'GET');


//Draw header
echo '<div id="download" style="display:none;"></div><br />';
echo '<img class="back" src="/wp-content/plugins/kerkdienstgemist/images/button_back.png" /> <b>' . $groupResponse["body"]["name"] . '</b>';

echo "<table width=\"100%\">
                        <tr>
                            <td></td>
                            <td><strong>Titel dienst:</strong></td>
                            <td><strong>Opties:</strong></td>
                        </tr>";

$videoResponse = $client->request('/groups/' . $groupid . '/videos', ["per_page" => 100], 'GET');

foreach ($videoResponse["body"]["data"] as $dienst) {
    $uriparts = explode("/", $dienst["uri"]);
    echo '<tr class="play" id="' . end($uriparts) . '">';
    echo '<td><img src="' . $dienst["pictures"]["sizes"][1]["link"] . '" /></td>';
    echo '<td>' . $dienst["name"] . '</td>';

    if ($dienst["duration"] > 0) {
        echo '<td><img alt="Afspelen" src="/wp-content/plugins/kerkdienstgemist/images/play.png" width="15px" height="15px"/></a></td>';
    } else {
        echo '<td class="procent" video="' . $dienst->id . '"><i>Binnenkort beschikbaar</i></td>';
    }
    echo '<td>' . $dienst["stats"]["plays"] . '</td>';
    echo '</tr>';
}
echo '</table>';
?>