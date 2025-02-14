/*
Projet professionnel commun
BTS SIO SLAM 2025
Mathieu MOURAO,Thomas GOUEZ et Mehdi NAOUI
*/
const timer = document.getElementById("livetime");


const formattime = (time) => String(time).padStart(2,'0');

//Affiche l'heure à la seconde près.
const refreshtime = () => {

    const time = new Date(Date.now());
    
    const [hours,minutes,seconds]=[
        time.getHours(),
        time.getMinutes(),
        time.getSeconds()
    ]
    
    timer.innerText = `${formattime(hours)}:${formattime(minutes)}:${formattime(seconds)}`;
}


setInterval(refreshtime,1)

