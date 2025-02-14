<!--Projet professionnel commun-->
<!--BTS SIO SLAM 2025-->
<!--Mathieu MOURAO,Thomas GOUEZ et Mehdi NAOUI-->
<DOCTYPE html>
<html lang=en>
    <head>
        
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link  href="styles.css" rel="stylesheet">
        <title>Emargement Gefor</title>
        <script src="https://kit.fontawesome.com/b90a9ecdfa.js" crossorigin="anonymous"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    </head>
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


