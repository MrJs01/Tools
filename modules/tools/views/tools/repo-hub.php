<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'RepoHub - Repositório de Templates Gratuitos';
?>

<!-- Página de templates -->
<div class="container my-5">
    <h1 class="text-center mb-4"><?= Html::encode($this->title) ?></h1>

    <!-- Área de Pesquisa -->
    <div class="row mb-4">
        <div class="col-12 col-md-8 offset-md-2">
            <div class="input-group">
                <input type="text" class="form-control" id="searchInput" placeholder="Pesquise por nome, descrição ou categoria">
                <button class="btn btn-outline-secondary" type="button" id="searchBtn">Pesquisar</button>
            </div>
        </div>
    </div>

    <!-- Filtros por categoria -->
    <div class="row mb-4">
        <div class="col-12 col-md-8 offset-md-2">
            <select class="form-select" id="categoryFilter">
                <option value="">Filtrar por categoria</option>
                <option value="CRM">CRM</option>
                <option value="Cloud">Cloud</option>
                <option value="Frontend">Frontend</option>
                <option value="Backend">Backend</option>
                <option value="Fullstack">Fullstack</option>
            </select>
        </div>
    </div>

    <!-- Cards com Templates -->
    <div class="row" id="templateList">
        <!-- Os cards serão injetados aqui pelo JavaScript -->
    </div>
</div>

<!-- Modal de Descrição Completa -->
<div class="modal fade" id="templateModal" tabindex="-1" aria-labelledby="templateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="templateModalLabel">Detalhes do Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Imagem do Template -->
                <div class="row mb-3">
                    <div class="col-12 text-center">
                        <img id="modalImage" src="" alt="Imagem do template" class="img-fluid" style="max-width: 100%; height: auto;" />
                    </div>
                </div>

                <!-- Nome do Template -->
                <h3 id="modalName" class="text-center mb-3"></h3>

                <!-- Descrição Completa -->
                <div id="modalDescription" class="mb-3"></div>

                <!-- Categorias -->
                <div id="modalCategories" class="mb-3"></div>

                <!-- Link para GitHub -->
                <a href="#" id="modalLink" class="btn btn-primary" target="_blank">Acessar no GitHub</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

<script>
    const templates = [{
            "name": "Template CRM",
            "shortDescription": "Template para sistemas de CRM.",
            "longDescription": "Este é um template completo para a criação de sistemas de CRM. Contém todas as funcionalidades para gerenciar clientes, vendas e relatórios.",
            "image": "https://via.placeholder.com/600x400",
            "categories": ["CRM", "Backend"],
            "githubLink": "https://github.com/usuario/template-crm"
        },
        {
            "name": "Cloud Dashboard",
            "shortDescription": "Template para dashboard em Cloud.",
            "longDescription": "Este template é perfeito para construir dashboards de monitoramento de servidores e serviços em Cloud. Inclui gráficos e relatórios.",
            "image": "https://via.placeholder.com/600x400",
            "categories": ["Cloud", "Frontend"],
            "githubLink": "https://github.com/usuario/cloud-dashboard"
        },
        {
            "name": "Website Portfolio",
            "shortDescription": "Template para portfólio pessoal.",
            "longDescription": "Template ideal para portfólio pessoal, com design clean e adaptado para dispositivos móveis. Inclui seções para projetos, habilidades e contato.",
            "image": "https://via.placeholder.com/600x400",
            "categories": ["Frontend"],
            "githubLink": "https://github.com/usuario/portfolio-website"
        },
        {
            "name": "E-commerce Dashboard",
            "shortDescription": "Template para painel de controle de e-commerce.",
            "longDescription": "Este template foi desenvolvido para sistemas de e-commerce, com funcionalidades de gerenciamento de produtos, vendas, e relatórios financeiros.",
            "image": "https://via.placeholder.com/600x400",
            "categories": ["Backend", "Fullstack"],
            "githubLink": "https://github.com/usuario/ecommerce-dashboard"
        },
        {
            "name": "Admin Panel Template",
            "shortDescription": "Template para painel de administração.",
            "longDescription": "Este é um template robusto para criar painéis de administração com diversas funcionalidades, como gerenciamento de usuários, permissões e logs.",
            "image": "https://via.placeholder.com/600x400",
            "categories": ["Frontend", "Backend"],
            "githubLink": "https://github.com/usuario/admin-panel"
        }
    ];

    // Função para criar um card de template
    function createTemplateCard(template) {
        const categories = template.categories.map(cat => `<span class="badge bg-secondary">${cat}</span>`).join(' ');
        const card = `
        <div class="col-12 mb-4 template-card mb-4" data-categories="${template.categories.join(',').toLowerCase()}">
            <div class="card h-100 d-flex flex-row">
                <img src="${template.image}" class="card-img-top" alt="${template.name}">
                <div class="card-body w-50">
                    <h5 class="card-title">${template.name}</h5>
                    <p class="card-text">${template.shortDescription}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>${categories}</div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#templateModal" onclick="openModal('${template.name}')">Abrir</button>
                    </div>
                </div>
            </div>
        </div>
        `;
        return card;
    }


    // Função para abrir o modal com a descrição completa
    function openModal(templateName) {
        const template = templates.find(t => t.name === templateName);
        document.getElementById('modalName').innerText = template.name;
        document.getElementById('modalDescription').innerHTML = marked.parse(template.longDescription);
        document.getElementById('modalLink').setAttribute('href', template.githubLink);
        document.getElementById('modalImage').setAttribute('src', template.image);

        // Exibir categorias como tags
        const categoriesHTML = template.categories.map(category =>
            `<span class="badge bg-info text-dark">${category}</span>`
        ).join(' ');
        document.getElementById('modalCategories').innerHTML = categoriesHTML;
    }

    // Função para filtrar templates
    function filterTemplates() {
        const searchValue = $('#searchInput').val().toLowerCase();
        const categoryFilter = $('#categoryFilter').val().toLowerCase();

        const filteredTemplates = templates.filter(template => {
            const matchesSearch = template.name.toLowerCase().includes(searchValue) || template.shortDescription.toLowerCase().includes(searchValue);
            const matchesCategory = categoryFilter === '' || template.categories.some(category => category.toLowerCase().includes(categoryFilter));
            return matchesSearch && matchesCategory;
        });

        renderTemplateList(filteredTemplates);
    }

    // Função para renderizar a lista de templates
    function renderTemplateList(templateList) {
        const templateContainer = document.getElementById('templateList');
        templateContainer.innerHTML = '';
        templateList.forEach(template => {
            templateContainer.innerHTML += createTemplateCard(template);
        });
    }

    // Inicializa a página com todos os templates
    renderTemplateList(templates);

    // Evento de pesquisa
    $('#searchBtn').on('click', filterTemplates);

    // Evento de filtro por categoria
    $('#categoryFilter').on('change', filterTemplates);

   
</script>

<!-- CSS opcional para ajustes visuais -->
<style>
    .modal-body {
        font-family: 'Arial', sans-serif;
    }

    .badge {
        font-size: 0.9rem;
        margin: 0.2rem;
    }

    .card-img-top {
        object-fit: cover;
        max-height: 200px;
    }
</style>