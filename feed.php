<?php
    namespace Lukaswhite\FeedWriter;
    require 'vendor/autoload.php';
    use Lukaswhite\FeedWriter\Itunes;

    $BEARER = getenv("BEARER");
    echo $BEARER;
    $spotify_show_id="4rOoJ6Egrf8K2IrywzwOMk";

    $ch = curl_init('https://api.spotify.com/v1/shows/' . $spotify_show_id . '/episodes?limit=50');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       'Accept: application/json',
       'Content-Type: application/json',
       'Authorization: Bearer ' . $BEARER
       ));

    $json = curl_exec($ch);
    $info = curl_getinfo($ch);

    var_dump($json);

    $json_decoded = json_decode($json,true);
    $json_decoded_items = $json_decoded['items'];

    foreach($json_decoded_items as $item)
    {
        //echo $item["href"]."\n";
    }

    //Podcast Information
    $ch = curl_init('https://api.spotify.com/v1/shows/' . $spotify_show_id."?market=US");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       'Accept: application/json',
       'Content-Type: application/json',
       'Authorization: Bearer ' . $BEARER
       ));

    $json = curl_exec($ch);
    $info = curl_getinfo($ch);

    curl_close($ch);

    $json_show = json_decode($json,true);

    $feed = new Itunes( );
    $channel = $feed->addChannel( );
    $channel->title($json_show["name"])
        ->subtitle(htmlspecialchars($json_show["description"]))
        ->description($json_show["description"])
        ->summary($json_show["description"])
        ->link("https://open.spotify.com/show/" . $spotify_show_id)
        ->image( $json_show["images"][0]["url"] )
        ->author($json_show["publisher"])
        ->owner($json_show["publisher"])
        ->explicit($json_show["explicit"])
        ->copyright($json_show["publisher"])
        ->generator("iTunes")
        ->block("true")
        ->ttl( 600 );

    $channel->addCategory()->term('News');

    foreach($json_decoded_items as $episode)
    {
        //echo $item["href"]."\n";
        //$temp_release_date = explode($json_show["release_date"],"-");
        //$release_date = $temp_release_date[2]."-".$temp_release_date[
        $channel->addItem( )
            ->title(htmlspecialchars($episode["name"]))
            ->author(htmlspecialchars($json_show["publisher"]))
            ->subtitle(htmlspecialchars($episode["description"]))
            ->duration( sec2hms(substr_replace($episode["duration_ms"] ,"", -3)))
            ->summary(htmlspecialchars($episode["description"]))
            ->pubDate( new \DateTime( $episode["release_date"] ) )
            ->guid( "https://open.spotify.com/episode/".$episode["id"] )
            ->explicit(htmlspecialchars($episode["explicit"]))
            ->addEnclosure( )
                ->url( 'http://example.com/show/'.$spotify_show_id.'/'.$episode["id"].'.m4a' )
                ->length( 8727310 )
                ->type( 'audio/x-m4a' );

    }

    echo $feed->toString();   
    file_put_contents("feed2.rss", $feed->toString());

    function sec2hms ($sec, $padHours = false) {
 
        $hms = "";

        // there are 3600 seconds in an hour, so if we
        // divide total seconds by 3600 and throw away
        // the remainder, we've got the number of hours
        $hours = intval(intval($sec) / 3600); 

        // add to $hms, with a leading 0 if asked for
        $hms .= ($padHours) 
              ? str_pad($hours, 2, "0", STR_PAD_LEFT). ':'
              : $hours. ':';

        // dividing the total seconds by 60 will give us
        // the number of minutes, but we're interested in 
        // minutes past the hour: to get that, we need to 
        // divide by 60 again and keep the remainder
        $minutes = intval(($sec / 60) % 60); 

        // then add to $hms (with a leading 0 if needed)
        $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';

        // seconds are simple - just divide the total
        // seconds by 60 and keep the remainder
        $seconds = intval($sec % 60); 

        // add to $hms, again with a leading 0 if needed
        $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

        return $hms;
    }

?>
