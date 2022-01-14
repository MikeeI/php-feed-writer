<?php
    namespace Lukaswhite\FeedWriter;
    require 'vendor/autoload.php';


    use Lukaswhite\FeedWriter\Itunes;
    $feed = new Itunes( );

    $channel = $feed->addChannel( );

    $channel->title( 'All About Everything' )
        ->subtitle( 'A show about everything' )
        ->description( 'All About Everything is a show about everything. Each week we dive into any subject known to man and talk about it as much as we can. Look for our podcast in the Podcasts app or in the iTunes Store' )
        ->summary( 'All About Everything is a show about everything. Each week we dive into any subject known to man and talk about it as much as we can. Look for our podcast in the Podcasts app or in the iTunes Store' )
        ->link( 'http://www.example.com/podcasts/everything/index.html' )
        ->image( 'http://example.com/podcasts/everything/AllAboutEverything.jpg' )
        ->author( 'John Doe' )
        ->owner( 'John Doe', 'john.doe@example.com' )
        ->explicit( 'no' )
        ->copyright( '&#x2117; &amp; &#xA9; 2014 John Doe &amp; Family' )
        ->generator( 'Feed Writer' )
        ->ttl( 60 )
        ->lastBuildDate( new \DateTime( '2016-03-10 02:00' ) );

    $channel->addCategory()
        ->term('Technology');

    $channel->addCategory()
        ->term('Sports')
        ->children('Football', 'Soccer');

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

    print $feed;
?>
