<div class="pt-3">
  <div class="container">
    <hr>
  	
    <div class="row py-2 small mb-3">
      <div class="col-6">Feito com Carinho por RS Web</div>
      <div class="col-6">
        <?php
        if (file_exists(dirname(dirname(__FILE__)).'/'.$relative.'template/modals.php')) {
            include dirname(dirname(__FILE__)).'/'.$relative.'template/modals.php';
        }
        ?>
      </div>
    </div>
  </div>
</div>
