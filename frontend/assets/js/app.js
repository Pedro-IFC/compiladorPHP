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
    document.getElementById('copy-result').addEventListener('click', function() {
        var textarea = document.getElementById('result');
    
        // Habilita temporariamente o textarea para permitir a seleção
        textarea.removeAttribute('disabled');
        textarea.select();
        textarea.setSelectionRange(0, 99999); // Para dispositivos móveis
    
        try {
            document.execCommand('copy'); // Copia o conteúdo para a área de transferência
            alert('Texto copiado com sucesso!');
        } catch (err) {
            alert('Erro ao copiar o texto.');
        }
    
        // Restaura o estado desabilitado do textarea
        textarea.setAttribute('disabled', true);
    });
    
    const textarea = document.getElementById("string_txt");
    const lineNumbers = document.querySelector(".line-numbers");

    textarea.addEventListener("input", updateLineNumbers);
    textarea.addEventListener("scroll", syncScroll);

    function updateLineNumbers() {
        const lines = textarea.value.split("\n").length;
        lineNumbers.innerHTML = Array.from({ length: lines }, (_, i) => i + 1).join("<br>");
    }

    function syncScroll() {
        lineNumbers.scrollTop = textarea.scrollTop;
    }
    updateLineNumbers();
});