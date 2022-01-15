<?php
    namespace Lukaswhite\FeedWriter;
    require 'vendor/autoload.php';
    use Lukaswhite\FeedWriter\Itunes;

    $BEARER = getenv("BEARER");
    $spotify_show_id="4rOoJ6Egrf8K2IrywzwOMk";

    $ch = curl_init('https://api.spotify.com/v1/shows/4rOoJ6Egrf8K2IrywzwOMk/episodes?limit=3');
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

    foreach($json_decoded_items as $item)
    {
        echo $item["href"]."\n";
    }

    //Podcast Information
    $ch = curl_init('https://api.spotify.com/v1/shows/4rOoJ6Egrf8K2IrywzwOMk');
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
        ->subtitle($json_show["description"])
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






    /*
    foreach($data['league'] as $key=>$val){// this can be ommited if only 0 index is there after 
        //league and $data['league'][0]['events'] can be used in below foreach instead of $val['events'].
        foreach($val['events'] as $keys=>$value){
            echo $value['home'].' v '.$value['away'].'<br>;
        }  
    }


    
    
    
    
    
    
    
    
    foreach($json_decoded as $element){
        var_dump($element->duration_ms);
        echo "\n";
    }
    */
    $output =  '<html><body><pre>' . var_export($json_decoded_items, true) . '</pre></body></html>';
    file_put_contents("feed.html", $output);



    


    $channel->addItem( )
        ->title( 'Shake Shake Shake Your Spices' )
        ->author( 'John Doe' )
        ->subtitle( 'A short primer on table spices' )
        ->duration( '07:04' )
        ->summary( 'This week we talk about <a href="https://itunes/apple.com/us/book/antique-trader-salt-pepper/id429691295?mt=11">salt and pepper shakers</a>, comparing and contrasting pour rates, construction materials, and overall aesthetics. Come and join the party!' )
        ->pubDate( new \DateTime( '2016-03-08 12:00' ) )
        ->guid( 'http://example.com/podcasts/archive/aae20140615.m4a' )
        ->explicit( 'no' )
        ->addEnclosure( )
            ->url( 'http://example.com/podcasts/everything/AllAboutEverythingEpisode3.m4a' )
            ->length( 8727310 )
            ->type( 'audio/x-m4a' );

    //print $feed;
?>
