<?php

    // Inicia a sessão
    session_start();

    // Verifica se o usuário está logado
    if (!isset($_SESSION["id_usuario"]) || empty($_SESSION["id_usuario"])) {
        // Redireciona para a página de login ou exibe uma mensagem de erro
        header("Location: login.php");
        exit;
    }
    else if (time() - $_SESSION["tempo"] > 240) { // sessão iniciada há mais de 30 minutos
        session_regenerate_id(true); // muda o ID da sessão para o ID corrente e invalidar o ID antigo
        //$_SESSION["tempo"] = time();  // atualiza o tempo de criação da sessão
        header("Location: login.php");
    }

    // Verifica se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once 'valida_redifinir.php';
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="js/configuracaoRedefinir.js"></script>
    <script type="text/javascript" src="extensao/jquery-3.7.0.min.js "></script> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/estilo-login.css">
    <link rel="stylesheet" href="css/estilo_redefinir.css">
</head>
<body>
    <div id="particles-js"></div>
    <div class="container p-5 login-container">
        <form method="POST">
            <div class="row">
                <!-- Campos login -->
                <div class="col-md-12 align-self-center">
                    <div class="row text-center">
                        <h2>Refinir a senha</h2>
                    </div>
                    
                    <div class="row text-center">
                        <div class="col-md-1"></div>
                        <div class="col-md-10">
                            <input type="password" class="form-control my-3" id="novaSenha" name="novaSenha" onkeyup="verificaForcaSenha();" placeholder="Nova Senha">
                            <input type="password" class="form-control mb-3" id="repitaSenha" name="repitaSenha" onkeyup="verificaForcaSenha();" placeholder="Repita a nova senha">
                            <p><h6>A senha deve possuir:</h6></p>
                            <p id="senhas">Senhas iguais.</p>
                            <p id="tamanho">No mínimo 8 caracteres.</p>
                            <p id="especial">No mínimo 1 caracter especial(Ex.: !@#$%&).</p>
                            <p id="numerico">Caracteres numéricos.</p>
                            <p id="alfabetico">Caracteres alfabéticos.</p>
                            <button type="submit" class="btn btn-primary" style="width: 100%;" id="buttonConfirmar">Confirmar</button>
                            <?php
                                // Exibe a mensagem de erro, se houver
                                if (isset($erro)) {
                                    echo "<p style='color: red;'>$erro</p>";
                                }
                            ?>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/particles.js"></script>
    <script>

        $('#buttonConfirmar').attr("disabled", true);
        particlesJS("particles-js", {
            particles: {
                number: {
                    value: 100,
                    density: {
                        enable: true,
                        value_area: 800
                    }
                },
                color: {
                    value: ["#fff"] // Adicione aqui as cores desejadas para os círculos
                },
                shape: {
                    type: "circle",
                    stroke: {
                        width: 0,
                        color: "#000000"
                    },
                    polygon: {
                        nb_sides: 5
                    }
                },
                opacity: {
                    value: 0.8,
                    random: true,
                    anim: {
                        enable: true,
                        speed: 1,
                        opacity_min: 0.1,
                        sync: false
                    }
                },
                size: {
                    value: 3,
                    random: true,
                    anim: {
                        enable: false,
                        speed: 40,
                        size_min: 0.1,
                        sync: false
                    }
                },
                line_linked: {
                    enable: false,
                    distance: 150,
                    color: "#ffffff",
                    opacity: 0.4,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 2,
                    direction: "none",
                    random: true,
                    straight: false,
                    out_mode: "out",
                    bounce: false,
                    attract: {
                        enable: false,
                        rotateX: 600,
                        rotateY: 1200
                    }
                }
            },
            interactivity: {
                detect_on: "canvas",
                events: {
                    onhover: {
                        enable: true,
                        mode: "repulse"
                    },
                    onclick: {
                        enable: true,
                        mode: "push"
                    },
                    resize: true
                },
                modes: {
                    grab: {
                        distance: 140,
                        line_linked: {
                            opacity: 1
                        }
                    },
                    bubble: {
                        distance: 400,
                        size: 40,
                        duration: 2,
                        opacity: 8,
                        speed: 3
                    },
                    repulse: {
                        distance: 200,
                        duration: 0.4
                    },
                    push: {
                        particles_nb: 4
                    },
                    remove: {
                        particles_nb: 2
                    }
                }
            },
            retina_detect: true
        });
    </script>
</body>
</html>
