<?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imagem = $_FILES['imagem'];
    $destino = 'imagem/logo.png';

    if (move_uploaded_file($imagem['tmp_name'], $destino)) {
      echo 'Upload da imagem concluído com sucesso!';
    } else {
      echo 'Erro ao fazer o upload da imagem.';
    }
  }
?>