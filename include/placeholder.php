

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
    <title>Salvar QR Code</title>
</head>
<body>

<button id="save_qrcode" class="btn btn-lg btn-block btn-primary ellipsis generate_qrcode rounded-pill" disabled>
    Salvar QR Code
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
                jsonValue.basename += '_teste'; // Adiciona '_teste' ao valor existente
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

























<!-- <button id="save_qrcode" class="btn btn-lg btn-block btn-primary ellipsis generate_qrcode rounded-pill" disabled>
    Salvar QR Code
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
                jsonValue.basename += '_teste'; // Adiciona '_teste' ao valor existente
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
        // Cria um link temporário para baixar o SVG
        const link = document.createElement('a');
        link.href = 'data:image/svg+xml;base64,' + btoa(jsonValue.content);
        link.download = jsonValue.basename + '.svg';
        link.click();
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
</script> -->












<!-- <input type="hidden" class="holdresult">
<input type="hidden" class="updatedresult">

<script>
// Seleciona o primeiro elemento input com a classe holdresult
const inputElement = document.querySelector('.holdresult');

// Seleciona o segundo elemento input onde o resultado atualizado será armazenado
const updatedResultElement = document.querySelector('.updatedresult');

// Função para verificar e modificar o JSON no input
const checkValueChange = () => {
    const value = inputElement.value;
    if (value) {
        try {
            // Analisa o JSON do valor do input
            const jsonValue = JSON.parse(value);

            // Verifica se 'basename' existe e modifica seu valor
            if (jsonValue.basename) {
                jsonValue.basename += '_teste'; // Adiciona '_teste' ao valor existente
            }

            // Atualiza o valor do segundo input com o JSON modificado
            updatedResultElement.value = JSON.stringify(jsonValue);
        } catch (error) {
            updatedResultElement.value = `Erro ao analisar JSON: ${error.message}`;
        }
    }
};

// Cria um MutationObserver para observar mudanças no atributo value
const observer = new MutationObserver(checkValueChange);

// Configura o observer para observar apenas o atributo value
observer.observe(inputElement, {
    attributes: true,
    attributeFilter: ['value']
});

// Exemplo de alteração no valor (remova isso para uso real)
setTimeout(() => {
    inputElement.value = '{"basename":"novoValor"}';
    inputElement.setAttribute('value', inputElement.value); // Necessário para disparar a observação
}, 2000);
</script> -->












