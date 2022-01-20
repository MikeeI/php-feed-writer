<?php
    namespace Lukaswhite\FeedWriter;
    require 'vendor/autoload.php';
    use Lukaswhite\FeedWriter\Itunes;

    $spotify_show_id="4rOoJ6Egrf8K2IrywzwOMk";
    $limit = 50;

    $json_show = getShowInformation($spotify_show_id);
    $json_show_episodes = [];
    
    $episode_count = $json_show["total_episodes"];
    $loop_count = intdiv($episode_count, $limit)+1;

    echo "Episode-Count: ".$episode_count."\n";
    echo "Loop-Count: ".$loop_count."\n";


    for ($i = 0; $i < $loop_count; $i++) {
        echo "Page: " . $i . "\n";
        if($i==0)
        {
            echo "First Run\n";
            $json_show_episodes = getEpisodes($spotify_show_id , $limit , 0 );
        }
        else
        {
            echo "$i Run\n";
            $temp = getEpisodes($spotify_show_id , $limit , $loop_count * $limit )
            $json_show_episodes = array_merge($json_show_episodes, $temp);
        }

    }   
    
    //$json_show_episodes = getEpisodes($spotify_show_id , 10 , 0 );
    //$json_show_episodes = array_merge($json_show_episodes,getEpisodes($spotify_show_id , 10 , 10 ));

    //var_dump($json_show_episodes);
       
    //echo '<pre>' . var_export($json_show_episodes, true) . '</pre>';
    

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

    foreach($json_show_episodes as $episode)
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
                ->url( 'https://github.com/JohnMeier/space/releases/download/podcast/' . $spotify_show_id . "-" . $episode["release_date"] . "-" .$episode["id"] . '.m4a' )
                //->length( 8727310 )
                ->type( 'audio/x-m4a' );

    }

    //echo $feed->toString();   
    file_put_contents("feed2.rss", $feed->toString());
    
    function getEpisodes($spotify_show_id, $limit,$offset)
    {
        $BEARER = getenv("BEARER");
        
        $ch = curl_init('https://api.spotify.com/v1/shows/' . $spotify_show_id . '/episodes?limit=' . $limit . '&market=es&offset=' . $offset);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
           'Accept: application/json',
           'Content-Type: application/json',
           'Authorization: Bearer ' . $BEARER
           ));

        $json = curl_exec($ch);
        $info = curl_getinfo($ch);

        $json_decoded = json_decode($json,true);
        $json_decoded_items = $json_decoded['items'];
        return $json_decoded_items;
    }

    function getShowInformation($spotify_show_id)
    {
        //Podcast Information
        $BEARER = getenv("BEARER");
        
        $ch = curl_init('https://api.spotify.com/v1/shows/' . $spotify_show_id."?market=es");
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
        
        return $json_show;
    }

    function sec2hms ($sec, $padHours = false) {
        $hms = "";
        $hours = intval(intval($sec) / 3600); 
        $hms .= ($padHours) 
              ? str_pad($hours, 2, "0", STR_PAD_LEFT). ':'
              : $hours. ':';
        $minutes = intval(($sec / 60) % 60); 
        $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';
        $seconds = intval($sec % 60); 
        $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);
        return $hms;
    }

?>
