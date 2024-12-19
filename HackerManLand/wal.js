var video = document.getElementById("bgvid"),
    pausebtn = document.getElementById("pause"),
    mutebtn = document.getElementById("mute");
if (window.matchMedia("(prefers-reduced-motion)").matches) {
    video.removeAttribute("autoplay");
    video.pause();
    pausebtn.innerHTML = "<i class=\"fa fa-play\" aria-hidden=\"true\"></i>";
}
pausebtn.addEventListener("click", function() {
    if (video.paused) {
        video.play();
        pausebtn.innerHTML = "<i class=\"fa fa-pause\" aria-hidden=\"true\"></i>";
    } else {
        video.pause();
        pausebtn.innerHTML = "<i class=\"fa fa-play\" aria-hidden=\"true\"></i>";
    }
});
mutebtn.addEventListener("click", function() {
    if (video.muted) {
        video.muted = false;
        mutebtn.innerHTML = "<i class=\"fa fa-volume-off\" aria-hidden=\"true\"></i>";
    } else {
        video.muted = true;
        mutebtn.innerHTML = "<i class=\"fa fa-volume-up\" aria-hidden=\"true\">";
    }
});


function video() {
    video=document.getElementById('bgvid');
    video.addEventListener('ended',reproducir(),false);
   }

   function reproducir() {
    video.setAttribute('src', 'hackermanclip.mp4');
   }
   window.addEventListener('load',iniciar,false);