<?php
/* @var $this yii\web\View */
$this->title = 'Lista de Sites e Ferramentas';
?>
<div class="container mt-5">
    <h1 class="text-center mb-4"><?= $this->title ?></h1>

    <!-- Barra de pesquisa -->
    <div class="mb-4">
        <input type="text" id="searchInput" class="form-control" placeholder="Pesquise por categoria, descrição ou site...">
    </div>

    <!-- Listagem de Categorias -->
    <div id="categoriesContainer" class="row gy-4">
        <!-- As categorias serão carregadas aqui via JavaScript -->
    </div>
</div>

<!-- Offcanvas para exibir os sites de uma categoria -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="categoryOffcanvas" aria-labelledby="categoryOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 id="categoryOffcanvasLabel">Sites da Categoria</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body" id="offcanvasContent">
        <!-- Os sites da categoria selecionada serão carregados aqui -->
    </div>
</div>

<script>
    const sitesData = [{
            categoria: "Redes Sociais",
            descricao: "Plataformas para interação social e compartilhamento de conteúdo.",
            id: "social",
        },
        {
            categoria: "Ferramentas",
            descricao: "Ferramentas úteis para edições, automações, etc.",
            id: "tools",
        }
    ];

    const subcategoriasData = [{
            nome: "Rede Social Imagens",
            descricao: "Plataformas sociais focadas em imagens.",
            id: "social-images",
            categoriaId: "social"
        },
        {
            nome: "Rede Social Texto",
            descricao: "Plataformas sociais focadas em texto.",
            id: "social-text",
            categoriaId: "social"
        },
        {
            nome: "Remover Background",
            descricao: "Ferramentas para remover fundo de imagens.",
            id: "remove-background",
            categoriaId: "tools"
        },
        {
            nome: "Ferramentas de IA",
            descricao: "Ferramentas baseadas em inteligência artificial.",
            id: "ai-tools",
            categoriaId: "tools"
        }
    ];

    const sites = [{
            nome: "Facebook",
            url: "https://facebook.com",
            logo: "https://crom.live/wp-content/uploads/2023/12/cropped-cropped-logo-1.png",
            descricao: "Uma plataforma para interações sociais, onde os usuários podem compartilhar conteúdos e se conectar.",
            categorias: ["social"],
            subcategorias: ["social-images", "social-text"]
        },
        {
            nome: "WhatsApp",
            url: "https://whatsapp.com",
            logo: "https://crom.live/wp-content/uploads/2023/12/cropped-cropped-logo-1.png",
            descricao: "Aplicativo de mensagens para comunicação rápida entre amigos, familiares e grupos.",
            categorias: ["social"],
            subcategorias: ["social-text"]
        },
        {
            nome: "X.com",
            url: "https://x.com",
            logo: "https://crom.live/wp-content/uploads/2023/12/cropped-cropped-logo-1.png",
            descricao: "Plataforma de microblogging onde os usuários podem compartilhar atualizações rápidas.",
            categorias: ["social"],
            subcategorias: ["social-text"]
        },
        {
            nome: "Remove.bg",
            url: "https://remove.bg",
            logo: "https://crom.live/wp-content/uploads/2023/12/cropped-cropped-logo-1.png",
            descricao: "Ferramenta online para remover o fundo de imagens automaticamente.",
            categorias: ["tools"],
            subcategorias: ["remove-background"]
        },
        {
            nome: "DALL·E",
            url: "https://openai.com/dall-e",
            logo: "https://crom.live/wp-content/uploads/2023/12/cropped-cropped-logo-1.png",
            descricao: "Ferramenta de inteligência artificial para criar imagens a partir de descrições de texto.",
            categorias: ["tools"],
            subcategorias: ["ai-tools"]
        },
        {
            nome: "Instagram",
            url: "https://instagram.com",
            logo: "https://crom.live/wp-content/uploads/2023/12/cropped-cropped-logo-1.png",
            descricao: "Rede social para compartilhamento de fotos e vídeos com amigos e seguidores.",
            categorias: ["social"],
            subcategorias: ["social-images"]
        },
        {
            nome: "Pinterest",
            url: "https://pinterest.com",
            logo: "https://crom.live/wp-content/uploads/2023/12/cropped-cropped-logo-1.png",
            descricao: "Plataforma para descobrir ideias e inspirações, com foco em imagens e pins.",
            categorias: ["social"],
            subcategorias: ["social-images"]
        }
    ];

    // Função para gerar a lista de sites e categorias dinamicamente
    function renderCategories(sitesData, subcategoriasData, sites) {
        const container = document.getElementById('categoriesContainer');
        container.innerHTML = '';

        // Criar categorias
        sitesData.forEach(categoria => {
            const categoriaDiv = document.createElement('div');
            categoriaDiv.classList.add('col-md-6', 'col-lg-4', 'mb-4');

            categoriaDiv.innerHTML = `
                <div class="card shadow-sm border-light" onclick="openOffcanvas('${categoria.id}')">
                    <div class="card-body">
                        <h5 class="card-title">${categoria.categoria}</h5>
                        <p class="card-text">${categoria.descricao}</p>
                    </div>
                </div>
            `;
            container.appendChild(categoriaDiv);

            // Criar subcategorias
            const subcategoriasList = subcategoriasData.filter(subcategoria => subcategoria.categoriaId === categoria.id);
            subcategoriasList.forEach(subcategoria => {
                const subcategoriaDiv = document.createElement('div');
                subcategoriaDiv.classList.add('col-md-6', 'col-lg-4', 'mb-4');
                subcategoriaDiv.innerHTML = `
                    <div class="card shadow-sm border-light" onclick="openOffcanvas('${subcategoria.id}')">
                        <div class="card-body">
                            <h6 class="card-title">${subcategoria.nome}</h6>
                            <p class="card-text">${subcategoria.descricao}</p>
                        </div>
                    </div>
                `;
                container.appendChild(subcategoriaDiv);
            });
        });

        // Criar sites
        sites.forEach(site => {
            const siteDiv = document.createElement('div');
            siteDiv.classList.add('col-md-6', 'col-lg-4', 'mb-4');
            siteDiv.innerHTML = `
                <div class="card shadow-sm border-light" onclick="openOffcanvas('${site.nome}')">
                    <div class="card-body">
                        <a href="${site.url}" target="_blank" class="d-flex align-items-center">
                            <img src="${site.logo}" alt="${site.nome}" class="me-2" style="width: 30px; height: 30px;">
                            <span>${site.nome}</span>
                        </a>
                        <p class="card-text">${site.descricao}</p>
                    </div>
                </div>
            `;
            container.appendChild(siteDiv);
        });
    }

    // Função para abrir o offcanvas com os sites da categoria
    function openOffcanvas(categoriaId) {
        const offcanvasContent = document.getElementById('offcanvasContent');
        offcanvasContent.innerHTML = ''; // Limpa o conteúdo anterior

        // Buscar os sites da categoria ou subcategoria
        const selectedSites = sites.filter(site =>
            site.categorias.includes(categoriaId) || site.subcategorias.includes(categoriaId) || site.nome.toLowerCase().includes(categoriaId.toLowerCase())
        );

        // Adicionar os sites ao offcanvas
        selectedSites.forEach(site => {
            const siteCard = document.createElement('div');
            siteCard.classList.add('mb-3');
            siteCard.innerHTML = `
                <div class="card shadow-sm">
                    <div class="card-body">
                        <a href="${site.url}" target="_blank" class="d-flex align-items-center">
                            <img src="${site.logo}" alt="${site.nome}" class="me-2" style="width: 30px; height: 30px;">
                            <span>${site.nome}</span>
                        </a>
                        <p class="card-text">${site.descricao}</p>
                    </div>
                </div>
            `;
            offcanvasContent.appendChild(siteCard);
        });

        // Mostrar o offcanvas
        const offcanvas = new bootstrap.Offcanvas(document.getElementById('categoryOffcanvas'));
        offcanvas.show();
    }

    // Função para filtrar resultados da pesquisa
    function filterResults() {
        const searchQuery = document.getElementById('searchInput').value.toLowerCase();
        const filteredCategorias = sitesData.filter(categoria =>
            categoria.categoria.toLowerCase().includes(searchQuery) ||
            categoria.descricao.toLowerCase().includes(searchQuery)
        );

        const filteredSubcategorias = subcategoriasData.filter(subcategoria =>
            subcategoria.nome.toLowerCase().includes(searchQuery) ||
            subcategoria.descricao.toLowerCase().includes(searchQuery)
        );

        const filteredSites = sites.filter(site =>
            site.nome.toLowerCase().includes(searchQuery) ||
            site.descricao.toLowerCase().includes(searchQuery) ||
            site.categorias.some(categoria => categoria.toLowerCase().includes(searchQuery)) ||
            site.subcategorias.some(subcategoria => subcategoria.toLowerCase().includes(searchQuery))
        );

        renderCategories(filteredCategorias, filteredSubcategorias, filteredSites);
    }

    // Escutar mudanças no input de pesquisa
    document.getElementById('searchInput').addEventListener('input', filterResults);

    // Inicializar com todos os sites
    renderCategories(sitesData, subcategoriasData, sites);
</script>