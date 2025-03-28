<div class="flex-center-col-end">
        <div id="time-date">
            <?php
            $date = new DateTimeImmutable();

            echo "<p id=\"date\">" . $date->format("l j F o") . "</p>" ;
            //echo "<p>" . $date->format("G:i:s");
            echo "<p id=\"livetime\"></p>"
            ?>
        </div>