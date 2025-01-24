<?php include './frontend/componentes/header.php'; ?>
    <div class="home">
        <div class="al-container">
            <div class="row">
                <div class="left">
                    <h2>Analisador Semântico</h2>
                    <?php include "./frontend/componentes/semantic/gerador_analise_semantic.php" ?>
                </div>
                <div class="right">
                    <div class="row">
                        <?php include "./frontend/componentes/semantic/documents.php" ?>
                    </div>
                </div>
            </div>
        </div>
    </div>    
<?php include './frontend/componentes/footer.php'; ?>