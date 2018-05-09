<?php
switch (true) {
    case (isset($_SESSION["holdsCanceled"])) : {
        $alertMessage = $_SESSION["holdsCanceled"] . " hold(s) canceled.";
        echo '<div class="alert alert-success" role="alert">' .
            '<a href="#" class="close" data-dismiss="alert">&times;</a>' .
            $alertMessage .
            '</div>';
        unset($_SESSION["holdsCanceled"]);
        break;
    }
    case (isset($_SESSION["holdsFrozen"])) : {
        $alertMessage = $_SESSION["holdsFrozen"] . " hold(s) frozen. <small><a href='#' data-toggle='tooltip' data-placement='right' title='There are a number of conditions that would prevent a hold from being frozen. Essentially, if an item is already on its way to you or if you are aligned to get it very soon, the system will block your attempt to freeze the request.'>more...</a></small>";
        echo '<div class="alert alert-success" role="alert">' .
            '<a href="#" class="close" data-dismiss="alert">&times;</a>' .
            $alertMessage .
            '</div>';
        unset($_SESSION["holdsFrozen"]);
        break;
    }
    case (isset($_SESSION["holdsUnfrozen"])) : {
        $alertMessage = $_SESSION["holdsUnfrozen"] . " hold(s) unfrozen.";
        echo '<div class="alert alert-success" role="alert">' .
            '<a href="#" class="close" data-dismiss="alert">&times;</a>' .
            $alertMessage .
            '</div>';
        unset($_SESSION["holdsUnfrozen"]);
        break;
    }
    case (isset($_SESSION["locationsChanged"])) : {
        $alertMessage = $_SESSION["locationsChanged"] . " pickup location(s) changed.";
        echo '<div class="alert alert-success" role="alert">' .
            '<a href="#" class="close" data-dismiss="alert">&times;</a>' .
            $alertMessage .
            '</div>';
        unset($_SESSION["locationsChanged"]);
        break;
    }
}
