<?php
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Chamados - Suporte TI</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- ... outros links CSS ... -->
    <style>
        /* Estilos atualizados incluindo responsividade */
        :root {
            --color-light: #fff97b;
            --color-light-accent: #fff44f;
            --color-medium: #2c2c2c;
            --color-dark: #1a1a1a;
            --color-darkest: #000000;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
        }

        /* Navbar atualizada */
        .navbar {
            background-color: var(--color-darkest) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 0.5rem 1rem;
        }

        .navbar-brand {
            font-weight: 600;
            color: var(--color-light) !important;
            font-size: 1.5rem;
        }

        /* Dashboard Cards - Ajustes */
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin: 1.5rem;
            padding: 1rem;
        }

        .stat-card {
            background: var(--color-darkest);
            color: var(--color-light);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
            min-height: 160px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            letter-spacing: -1px;
        }

        .stat-label {
            font-size: 1.1rem;
            font-weight: 500;
            opacity: 0.95;
        }

        /* Chamados Grid */
        .chamados-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            padding: 1rem;
        }

        .chamado-card {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            border-left: 4px solid var(--color-light);
            transition: all 0.3s ease;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .dashboard-stats {
                grid-template-columns: 1fr;
                margin: 1rem;
                gap: 1rem;
            }

            .stat-card {
                padding: 1.5rem;
                min-height: 130px;
            }

            .stat-number {
                font-size: 2.5rem;
            }

            .stat-label {
                font-size: 1rem;
            }

            .chamados-grid {
                grid-template-columns: 1fr;
                padding: 0.5rem;
            }

            .filtro-chamados {
                flex-direction: column;
                gap: 0.5rem;
                margin: 1rem;
                padding: 1.25rem;
            }

            .filtro-chamados select,
            .filtro-chamados input {
                padding: 0.75rem;
                font-size: 1rem;
            }

            .navbar-brand {
                font-size: 1.2rem;
            }

            .container-fluid {
                padding: 0.5rem;
            }
        }

        /* Ajustes específicos para telas muito pequenas */
        @media (max-width: 375px) {
            .stat-number {
                font-size: 2.2rem;
            }

            .stat-card {
                padding: 1.25rem;
                min-height: 120px;
            }
        }

        /* Filtros responsivos */
        .filtro-chamados {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            background: var(--color-dark);
            padding: 1rem;
            border-radius: 10px;
            margin: 1rem;
        }

        .filtro-chamados select,
        .filtro-chamados input {
            flex: 1;
            min-width: 200px;
            padding: 0.5rem;
            border-radius: 5px;
            border: 1px solid var(--color-light);
            background: var(--color-darkest);
            color: var(--color-light);
        }
    </style>
</head>
<body>
    <!-- Navbar atualizada -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Suporte TI</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- ... seus itens de menu ... -->
            </div>
        </div>
    </nav>

    <!-- Dashboard Stats -->
    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-number">25</div>
            <div class="stat-label">Chamados Abertos</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">15</div>
            <div class="stat-label">Em Andamento</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">45</div>
            <div class="stat-label">Concluídos</div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="filtro-chamados">
        <select class="form-select">
            <option>Status</option>
            <!-- ... opções ... -->
        </select>
        <select class="form-select">
            <option>Prioridade</option>
            <!-- ... opções ... -->
        </select>
        <input type="text" class="form-control" placeholder="Pesquisar...">
    </div>

    <!-- Grid de Chamados -->
    <div class="chamados-grid">
        <!-- ... seus cards de chamados ... -->
    </div>
</body>
</html>