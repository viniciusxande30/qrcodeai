<?php
session_start();

if (!isset($_SESSION['user_index'])) {
    header('Location: login.php');
    exit();
}

$dataFile = 'data/users.json';
$users = [];

if (file_exists($dataFile)) {
    $json = file_get_contents($dataFile);
    $users = json_decode($json, true);
}

$user = $users[$_SESSION['user_index']];


?>


<?php

$version = '5.3.5';

if (version_compare(phpversion(), '5.4', '<')) {
    exit("QRcdr requires at least PHP version 5.4.");
}

// https://stackoverflow.com/questions/11920026/replace-file-get-contents-with-curl
if (!ini_get('allow_url_fopen')) {
    exit("Please enable <code>allow_url_fopen<code>");
}
if (!function_exists('mime_content_type')) {
    exit("Please enable the <code>fileinfo</code> extension");
}
// Update this path if you have a custom relative path inside config.php
require dirname(__FILE__)."/lib/functions.php";

if (qrcdr()->getConfig('debug_mode')) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL ^ E_NOTICE);
}
$relative = qrcdr()->relativePath();
require dirname(__FILE__).'/'.$relative.'include/head.php';
?>
<!doctype html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $rtl['dir']; ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <title><?php echo qrcdr()->getString('title'); ?></title>
        <meta name="description" content="<?php echo qrcdr()->getString('description'); ?>">
        <meta name="keywords" content="<?php echo qrcdr()->getString('tags'); ?>">
        <link rel="shortcut icon" href="<?php echo $relative; ?>images/favicon.ico">
        <link href="<?php echo $relative; ?>bootstrap/css/bootstrap<?php echo $rtl['css']; ?>.min.css" rel="stylesheet">
        <link href="<?php echo $relative; ?>css/font-awesome.min.css" rel="stylesheet">
        <script src="<?php echo $relative; ?>js/jquery-3.5.1.min.js"></script>
        <?php
        $custom_page = false;
        $body_class = '';
        if (isset($_GET['p'])) {
            $load_page = dirname(__FILE__).'/'.$relative.'template/'.$_GET['p'].'.html';
            if (file_exists($load_page)) {
                $custom_page = file_get_contents($load_page);
            }
        }
        qrcdr()->loadQRcdrCSS($version);
        if (!$custom_page) {
            $body_class = 'qrcdr';
            qrcdr()->loadPluginsCss();
        }
        qrcdr()->setMainColor(qrcdr()->getConfig('color_primary'));
        ?>
    </head>
    <body class="<?php echo $body_class; ?>">


    <nav class="navbar bg-primary m-0 navbar-expand-sm navbar-dark bg-dark">
	<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#qrcdrNavbar" aria-controls="qrcdrNavbar" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="qrcdrNavbar">
		<ul class="navbar-nav ms-auto">
<!--
		<li class="nav-item">
			<a class="nav-link" href="#">Link 1</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#">Link 2</a>
		</li>
-->
			<li style="margin-top:5px; margin-right:15px;">
                <a href="profile.php"  style="color:white;text-decoration:none;font-weight:bolder">Área de Login</a>
            </li>
                <li class="nav-item">
                    <a class="nav-link"  style="color:white;text-decoration:none;font-weight:bolder" href="list_qrcodes.php">Lista de QR Codes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"  style="color:white;text-decoration:none;font-weight:bolder" href="logout.php">Sair</a>
                </li>
			<?php echo qrcdr()->langMenu('menu'); ?>
		</ul>
	</div>
</nav>

<div class="bg-primary position-relative">
  <div class="overlay-gradient"></div>
  <div class="container position-relative">
  	<div class="row py-5">
    	<div class="col">
        <h1 class="display-1" style="font-size:50px">Bem-vindo, <?php echo htmlspecialchars($user['username']); ?> </h1>
        <p>Gere um QR Code para o seu negócio<br></p>
        <p><a href="" class="btn btn-primary btn-lg shadow" role="button">Voltar para a Página Inicial &raquo;</a></p>
    </div>
    </div>
  </div>
</div>

        <?php
        
        if ($custom_page) {
            echo '<div class="container mt-4">'.$custom_page.'</div>';
        } else {
           ?>




<?php $sidebarorder = 'left' == qrcdr()->getConfig('sidebar') ? ' order-last order-lg-first' : ' order-last'; ?>
<input type="hidden" id="qrcdr-relative" value="<?php echo $relative; ?>">
<div class="container">
    <div class="row mt-3">

        <div class="col-lg-4<?php echo $sidebarorder; ?>">
        <nav class="navbar sticky-top">
    <div class="placeresult bg-light d-grid">
        <div class="form-group text-center wrapresult">
            <div class="resultholder">
                <img class="img-fluid" src="<?php echo $relative.qrcdr()->getConfig('placeholder'); ?>" />
                <div class="infopanel"></div>
            </div>
        </div>
        <div class="preloader"><i class="fa fa-cog fa-spin"></i></div>
        <input type="hidden" class="holdresult">
        <input type="hidden" class="updatedresult">
        <button class="btn btn-lg btn-block btn-primary ellipsis generate_qrcode<?php echo $rounded_btn_save; ?>" disabled><i class="fa fa-check"></i> <?php echo qrcdr()->getString('save'); ?></button>
        <div class="text-center mt-2 linksholder"></div>
    </div>
<?php
if (file_exists(dirname(dirname(__FILE__)).'/'.$relative.'template/sidebar.php')) {
    include dirname(dirname(__FILE__)).'/'.$relative.'template/sidebar.php';
}
?>
</nav>




<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['basename']) && isset($_POST['content'])) {
        $basename = basename($_POST['basename']); 
        $content = $_POST['content'];

        $filePath = __DIR__ . '/qrcodes/' . $basename . '.svg';

        if (!is_dir(__DIR__ . '/qrcodes')) {
            mkdir(__DIR__ . '/qrcodes', 0777, true);
        }

        if (file_put_contents($filePath, $content) !== false) {
            echo 'Arquivo salvo com sucesso.';
        } else {
            echo 'Erro ao salvar o arquivo.';
        }
    } else {
        echo 'Dados incompletos.';
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salvar QR Code em Minha Lista</title>
</head>
<body>

<button id="save_qrcode" class="btn btn-lg btn-block btn-primary ellipsis generate_qrcode rounded-pill" disabled>
    Salvar QR Code em Minha Lista
</button>

<input type="hidden" class="holdresult">
<input type="hidden" class="updatedresult">

<script>
// Seleciona os elementos input e o botão
const inputElement = document.querySelector('.holdresult');
const updatedResultElement = document.querySelector('.updatedresult');
const saveButton = document.querySelector('#save_qrcode');

// Função para verificar e modificar o JSON no input
const checkValueChange = () => {
    const value = inputElement.value;
    if (value) {
        try {
            // Analisa o JSON do valor do input
            const jsonValue = JSON.parse(value);

            // Verifica se 'basename' existe e modifica seu valor
            if (jsonValue.basename) {
                jsonValue.basename += '_<?php echo htmlspecialchars($user['username']); ?>'; // Adiciona '_teste' ao valor existente
            }

            // Atualiza o valor do segundo input com o JSON modificado
            updatedResultElement.value = JSON.stringify(jsonValue);
        } catch (error) {
            updatedResultElement.value = `Erro ao analisar JSON: ${error.message}`;
        }
    }
};

// Função para salvar o SVG
const saveSVG = () => {
    const jsonValue = JSON.parse(updatedResultElement.value);
    if (jsonValue && jsonValue.content) {
        // Envia o conteúdo SVG para o servidor via POST
        fetch(window.location.href, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                basename: jsonValue.basename,
                content: jsonValue.content
            })
        })
        .then(response => response.text())
        .then(result => {
            console.log(result);
            // Optional: Notifica o usuário que o arquivo foi salvo
        })
        .catch(error => {
            console.error('Erro ao enviar o SVG para o servidor:', error);
        });
    } else {
        console.log('Nenhum SVG encontrado no JSON.');
    }
};

// Adiciona um listener de clique ao botão de salvar
saveButton.addEventListener('click', saveSVG);

// Cria um MutationObserver para observar mudanças no atributo value
const observer = new MutationObserver(checkValueChange);

// Configura o observer para observar apenas o atributo value
observer.observe(inputElement, {
    attributes: true,
    attributeFilter: ['value']
});
</script>

</body>
</html>

        </div><!-- col md-4-->

        <div class="col-lg-8 pt-3 mb-3">
            <div class="row">
                <form role="form" class="qrcdr-form needs-validationNO w-100" novalidate>
                    <input type="submit" class="d-none">
                    <input type="hidden" name="section" id="getsec" value="<?php echo $getsection; ?>">
                    
                    <?php
                    /**
                     * QR CODE DATA
                     */ ?>
                    <div class="col-12 pb-2">
                        <div class="row">
                            <?php
                            require dirname(__FILE__).'/include/tabnav.php';
                            ?>
                            <div class="tab-content mt-3" id="dataTabs">
                            <?php
                            require dirname(__FILE__).'/include/tab-link.php';
                            require dirname(__FILE__).'/include/tab-text.php';
                            require dirname(__FILE__).'/include/tab-email.php';
                            require dirname(__FILE__).'/include/tab-location.php';
                            require dirname(__FILE__).'/include/tab-tel.php';
                            require dirname(__FILE__).'/include/tab-sms.php';
                            require dirname(__FILE__).'/include/tab-whatsapp.php';
                            require dirname(__FILE__).'/include/tab-skype.php';
                            require dirname(__FILE__).'/include/tab-zoom.php';
                            require dirname(__FILE__).'/include/tab-wifi.php';
                            require dirname(__FILE__).'/include/tab-vcard.php';
                            require dirname(__FILE__).'/include/tab-event.php';
                            require dirname(__FILE__).'/include/tab-paypal.php';
                            require dirname(__FILE__).'/include/tab-bitcoin.php';
                            ?>
                            </div> <!-- tab content -->

                            <?php require dirname(__FILE__).'/include/options.php'; ?>
                            </div><!-- main-col open at tabnav -->
                        </div> <!-- row -->
                    </div><!-- col-12-->
                </form>
            </div> <!-- row -->
        </div><!-- col-lg-8 -->

    </div><!-- row -->
</div><!-- containerOOO -->

<div class="alert_placeholder toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
    <div class="toast-header">
        <div class="mr-auto">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-circle" viewBox="0 0 16 16">
              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
              <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
            </svg>
        </div>
        <button type="button" class="ml-2 ms-auto mb-1 btn-close close" data-dismiss="toast" aria-label="Close">
          <span aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/></svg></span>
        </button>
    </div>
    <div class="toast-body"></div>
</div>






           <?php
        }
        qrcdr()->loadQRcdrJS($version);

        if (!$custom_page) {
            qrcdr()->loadPlugins();
        }
        if (file_exists(dirname(__FILE__).'/'.$relative.'template/footer.php')) {
            include dirname(__FILE__).'/'.$relative.'template/footer.php';
        }
        ?>
    </body>
</html>
