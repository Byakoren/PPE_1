<!--Projet professionnel commun-->
<!--BTS SIO SLAM 2025-->
<!--Mathieu MOURAO,Thomas GOUEZ et Mehdi NAOUI-->
<main class="flex-center-col">
    <div class="flex-center-col-end">
        <div id="time-date">
            <?php
            $date = new DateTimeImmutable();

            echo "<p id=\"date\">" . $date->format("l j F o") . "</p>" ;
            //echo "<p>" . $date->format("G:i:s");
            echo "<p id=\"livetime\"></h3>"
            ?>
        </div>
    </div>
    <?php
            echo "<h2 id=\"welcome\">" . "Bonjour Maurice" . "</h2>";
        ?>
<main>