<?php include './frontend/componentes/header.php'; ?>
    <div class="home">
        <div class="al-container">
            <div class="row">
                <div class="left">
                    <h2>Analisador léxico</h2>
                    <?php include "./frontend/componentes/lexical/gerador_analise_lexica.php" ?>
                </div>
                <div class="right">
                    <div class="row">
                        <?php include "./frontend/componentes/lexical/documents.php" ?>
                    </div>
                </div>
            </div>
        </div>
    </div>    
<?php include './frontend/componentes/footer.php'; ?>