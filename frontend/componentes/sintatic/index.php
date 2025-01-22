<?php include './frontend/componentes/header.php'; ?>
    <div class="home">
        <div class="al-container">
            <div class="row">
                <div class="left">
                    <h2>Analisador Sintatico Preditivo</h2>
                    <?php include "./frontend/componentes/sintatic/slr/gerador_analise_slr.php" ?>
                </div>
                <div class="right">
                    <div class="row">
                        <?php include "./frontend/componentes/sintatic/slr/documents.php" ?>
                    </div>
                </div>
            </div>
        </div>
    </div>    
<?php include './frontend/componentes/footer.php'; ?>