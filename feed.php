<?php
    namespace Lukaswhite\FeedWriter;
    require 'vendor/autoload.php';


    use Lukaswhite\FeedWriter\RSS2;
    $feed = new RSS2( );

    $channel = $feed->addChannel( );

    $channel
        ->title( 'My Blog' )
        ->description( 'My personal blog' )
        ->link( 'https://example.com' )
        ->lastBuildDate( new \DateTime( ) )
        ->pubDate( new \DateTime( ) )
        ->language( 'en-US' );

    foreach( $posts as $post ) {
        $channel->addItem( )
            ->title( $post->title )
            ->description( $post->description )
            ->link( $post->url )
            ->pubDate( $post->publishedAt )
            ->guid( $post->url, true );
    }

    print $feed;
?>
