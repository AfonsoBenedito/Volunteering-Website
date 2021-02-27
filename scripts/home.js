// Home javascript
window.onscroll = function() {scrollFunction()};

function scrollFunction() {

    let changeNumber = document.querySelectorAll('header .navBar').length; //number of .navBar on header



    if (document.body.scrollTop > 1 || document.documentElement.scrollTop > 1) {

        for(let i = 0; i < changeNumber ; i++){
        document.getElementsByClassName("navBar")[i].style.color = "rgb(0,14,20)";
        }

        document.getElementById("header").style.backgroundColor = "rgba(255, 255, 255)";
        document.getElementById("logotipoImg").src = "assets/Icons/logoPreto.png"
        document.getElementById("header").style.height = "100px";

    } else {

        for(let i = 0; i < changeNumber ; i++){
        document.getElementsByClassName("navBar")[i].style.color = "white";
        }

        document.getElementById("header").style.backgroundColor = "rgba(255, 255, 255, 0)";
        document.getElementById("logotipoImg").src = "assets/Icons/logoBranco.png"
        document.getElementById("header").style.height = "140px";
    }
}

let lista = document.querySelectorAll('.eEs li'); //.length;

console.log(lista)

for(i of lista){
    console.log(i.id)

    i.onmouseenter = function(){
        this.classList.add('elasticEffect');
    }

    i.onmouseleave = function(){
        setTimeout(function(){
        this.classList.remove('elasticEffect');
    }
    .bind(this),700)
    }
}