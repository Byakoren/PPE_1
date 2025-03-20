<!--Projet professionnel commun-->
<!--BTS SIO SLAM 2025-->
<!--Mathieu MOURAO,Thomas GOUEZ et Mehdi NAOUI-->
<DOCTYPE html>
<html lang=en>
    <?php
        require "head.php";
    ?>
 <body>
    <header>
        <?php
        include "menu.php";
        ?>
    </header>
    <main id="main-section">
    <main class="flex-center-col">
    <?php    
    include "time.php";
    ?>
    </div>
    <?php
            echo "<h2 id=\"welcome\">" . "Bonjour Maurice" . "</h2>";
        ?>
    </header>
    <main>
        
        <script>
            document.addEventListener("DOMContentLoaded", ()=>{
                var calendarEl =document.getElementById("calendar");
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'timeGridDay'
                });
                calendar.render();
            });
            
        </script>
        <div id="calendar"></div>
    </main>

    <script src="script.js"></script>
</body>


