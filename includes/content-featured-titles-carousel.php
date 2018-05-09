<h3><span class="glyphicon glyphicon-pencil"></span>  <?php echo $config->getCarouselTitle() ?><a href="includes/redirect-clear-carousel-cache.php?refresh" title="Refresh the carousel data."><img id="refresh-image" class="pull-right" src="../images/carousel-refresh.png"></a>
</h3>
<div id="carousel">
    <?php
    if (count($carouselBibs->carouselBibArray) == 0) {
        echo "No results.";
    } else {
        foreach ($carouselBibs->carouselBibArray as $thisBib) {
            // compose the hyperlink for this bib.  The hyperlink becomes the basis for each element of the waterwheel carousel
            echo '<span class="morph pic">';
            if ($config->getHyperlinkBibsToEncore() == 1) {
                $linkString = 'http://' . $thisBib["pacUrl"];
            } else {
                $linkString = "/bibdetail.php?bibid=" . $thisBib["bibId"];
            }
            echo '<a href="' . $linkString . '">' .
                '<img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs="' .
                ' data-src="http://' . $thisBib["imageUrl"] . '"' .
                ' title="' . $thisBib["bibTitle"] . '"' .
                ' onError="this.src=\'./images/placeholder-cover.jpg\'"' .
                ' id="' . $thisBib["bibId"] . '"' .
                '/></a>';
        }
        echo "</span>";
    }
    ?>
</div>
