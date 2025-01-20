window.addEventListener("load", (event) => {
    if(document.querySelector("#string_txt")){
        document.querySelector("#string_txt").style.minHeight = document.querySelector("#string_txt").value.split('\n').length*14 + 24  + "px"
        document.querySelector("#string_txt").addEventListener("keyup", function(){
            document.querySelector("#string_txt").style.minHeight = document.querySelector("#string_txt").value.split('\n').length*14 + 24 + "px"
        });
        document.querySelector("#string_txt").addEventListener("keydown", function(){
            document.querySelector("#string_txt").style.minHeight = document.querySelector("#string_txt").value.split('\n').length*14 + 24 + "px"
        });
    }
});