<style>
    :root {
        --dark-bg: #1a1a1a;
        --darker-bg: #0f0f0f;
        --accent: #007bff;
    }

    #container_tools {
        margin: 0 !important;
        max-width: 100% !important;
        padding: 0 !important;
    }

    #workspace {
        height: calc(100vh - 60px);
        background-color: black;
        position: relative;
        overflow: hidden;
        transform-origin: 0 0;
        width: 100%;
        min-width: 100%;
        min-height: calc(100vh - 60px);
    }

    .mind-node {
        position: absolute;
        background: #0f0f0f;
        border-radius: 8px;
        padding: 15px;
        min-width: 150px;
        cursor: move;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        z-index: 2;
        color: white ;

    }

    .card-node {
        position: absolute;
        background: #0f0f0f;
        border-radius: 8px;
        padding: 15px;
        width: 250px;
        cursor: move;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        z-index: 2;
        color: white ;
    }

    .card-node img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 4px;
        margin-bottom: 10px;
    }

    .mind-node:hover,
    .card-node:hover {
        border-color: var(--accent);
        box-shadow: 0 0 15px rgba(0, 123, 255, 0.3);
    }

    .node-menu {
        position: absolute;

        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 4px;
        padding: 10px;
        display: none;
        z-index: 1000;
    }

    .node-menu button {
        display: block;
        width: 100%;
        margin-bottom: 5px;
        text-align: left;
    }

    .connector {
        position: absolute;
        width: 15px;
        height: 15px;
        background: var(--accent);
        border-radius: 50%;
        cursor: pointer;
        z-index: 3;
        opacity: 0;
        transition: opacity 0.3s ease;

    }


    .mind-node:hover .connector,
    .card-node:hover .connector,
    .connector.active {

        opacity: 1;

    }

    .connection-line {
        position: absolute;
        height: 2px;
        background: var(--accent);
        transform-origin: left center;
        pointer-events: none;
        z-index: 1;
    }

    .toolbar {

        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 10px;
    }

    .btn-custom {
        background: var(--accent);
        color: white;
        border: none;
        padding: 5px 15px;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .btn-custom:hover {
        background: #0056b3;
    }

    #sidebar {
        border-left: 1px solid rgba(255, 255, 255, 0.1);
        padding: 20px;
        transition: transform 0.3s ease;
    }

    #sidebar.hidden {
        transform: translateX(100%);
    }

    .form-control {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
    }

    .form-control:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: var(--accent);
        color: white;
        box-shadow: none;
    }

    .connector.active {
        background-color: #00ff00;
    }

    .style-controls {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .col-9 {
        transition: width 0.3s ease;
        height: calc(100vh - 60px);
        overflow: hidden;
    }

    .col-9.expanded {
        width: 100%;
        height: calc(100vh - 60px);
        overflow: hidden;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsPlumb/2.15.6/js/jsplumb.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>


<div class="toolbar">
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="m-0">Mapa Mental</h4>
        <div class="position-relative">
            <button class="btn" id="exportJson" style="padding: 5px 10px;">Exportar Json</button>
            <button class="btn" id="importJson" style="padding: 5px 10px;">Importar Json</button>
            <button class="btn-custom" id="addNode">Adicionar Nó</button>
            <div class="node-menu">
                <button class="btn-custom mb-2" id="addSimpleNode">Nó Simples</button>
                <button class="btn-custom mb-2" id="addCardNode">Card com Imagem</button>
            </div>
        </div>
    </div>
</div>

<div class="row m-0 flex-grow-1">
    <div class="col-9 p-0">
        <div id="workspace"></div>
    </div>
    <div class="col-3 p-3 bg-dark text-white" id="sidebar">
        <h5>Editar Nó</h5>
        <form id="nodeEditor" class="mt-3">
            <div class="mb-3">
                <label class="form-label">Título</label>
                <input type="text" class="form-control" id="nodeTitle">
            </div>
            <div class="mb-3">
                <label class="form-label">Descrição</label>
                <textarea class="form-control" id="nodeDesc" rows="3"></textarea>
            </div>
            <div class="mb-3" id="imageUrlField" style="display: none;">
                <label class="form-label">URL da Imagem</label>
                <input type="text" class="form-control" id="nodeImage">
            </div>

            <div class="style-controls">
                <div class="mb-3">
                    <label class="form-label">Cor do Texto</label>
                    <input type="color" class="form-control form-control-color" id="textColor">
                </div>
                <div class="mb-3">
                    <label class="form-label">Cor do Fundo</label>
                    <input type="color" class="form-control form-control-color" id="bgColor">
                </div>
                <div class="mb-3">
                    <label class="form-label">Fonte</label>
                    <select class="form-control" id="fontFamily">
                        <option value="'Segoe UI', sans-serif">Segoe UI</option>
                        <option value="Arial, sans-serif">Arial</option>
                        <option value="'Times New Roman', serif">Times New Roman</option>
                        <option value="'Courier New', monospace">Courier New</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn-custom">Salvar</button>
            <button type="button" class="btn-custom" id="deleteNode">Excluir</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        let selectedNode = null;
        let nodeCount = 0;
        let connectionStart = null;
        let connections = [];
        let scale = 1;
        let settings_draggable = {
            containment: "parent",
            start: function() {
                $(this).css('z-index', 1000);
            },
            drag: function(event, ui) {
                // Adjust the position based on the current scale
                ui.position.left = ui.position.left / scale;
                ui.position.top = ui.position.top / scale;
                updateConnections();
            },
            stop: function() {
                $(this).css('z-index', '');
                updateConnections();
                setTimeout(() => {
                    updateConnections();
                }, 500);
                setTimeout(() => {
                    updateConnections();
                }, 1000);
            }
        };

        const Toast = Swal.mixin({
            toast: true,
            position: "bottom-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        // Inicialmente esconde o sidebar
        $('#sidebar').addClass('hidden');

        // Zoom com ctrl + scroll
        $('#workspace').on('wheel', function(e) {
            if (e.ctrlKey) {
                e.preventDefault();
                const delta = e.originalEvent.deltaY;
                const zoomFactor = delta > 0 ? 0.9 : 1.1;
                const oldScale = scale;
                scale *= zoomFactor;
                scale = Math.min(Math.max(0.5, scale), 3); // Limita o zoom entre 0.5x e 3x

                // Ajusta a posição para o zoom em torno da posição do mouse
                const mouse = {
                    x: e.pageX - $(this).offset().left,
                    y: e.pageY - $(this).offset().top
                };

                const zoom = scale / oldScale;
                const rect = this.getBoundingClientRect();

                // Aplica a transformação de escala mantendo o tamanho
                $(this).css({
                    'transform': `scale(${scale})`,
                    'width': `${100 / scale}%`, // Compensar pela escala
                    'height': `${(100 * ($(window).height() - 60) / $(window).height()) / scale}vh` // Compensar pela escala
                });

                // Atualiza as conexões com o fator de escala
                updateConnections(scale);
            }
        });

        // Desselecionar ao clicar no workspace
        $('#workspace').on('click', function(e) {
            if (e.target === this) {
                selectedNode = null;
                $('.mind-node, .card-node').css('border-color', 'transparent');
                toggleSidebar(false);
            }
        });

        $('#addNode').hover(function() {
            $('.node-menu').show();
        });

        $('.node-menu').hover(
            function() {
                $(this).show();
            },
            function() {
                $(this).hide();
            }
        );

        $('#addSimpleNode').click(function() {
            nodeCount++;
            const node = $(`<div class="mind-node" id="node-${nodeCount}">
      <h3>Novo Nó ${nodeCount}</h3>
      <p>Clique para editar</p>
      <div class="connector" style="top: 50%; right: -5px;"></div>
      <div class="connector" style="top: 50%; left: -5px;"></div>
    </div>`);

            addNodeToWorkspace(node);
        });

        $('#addCardNode').click(function() {
            nodeCount++;
            const node = $(`<div class="card-node" id="node-${nodeCount}">
      <img src="https://via.placeholder.com/150" alt="Card image">
      <h3>Novo Card ${nodeCount}</h3>
      <p>Clique para editar</p>
      <div class="connector" style="top: 50%; right: -5px;"></div>
      <div class="connector" style="top: 50%; left: -5px;"></div>
    </div>`);

            addNodeToWorkspace(node);
        });

        function addNodeToWorkspace(node) {
            const workspace = $('#workspace');
            const maxX = workspace.width() - 200;
            const maxY = workspace.height() - 100;
            const randomX = Math.random() * maxX;
            const randomY = Math.random() * maxY;

            node.css({
                left: randomX + 'px',
                top: randomY + 'px'
            });

            workspace.append(node);

            node.draggable(settings_draggable);
        }



        $(document).on('click', '.connector', function(e) {
            e.stopPropagation();

            if (!connectionStart) {
                connectionStart = $(this);
                connectionStart.addClass('active');
            } else {
                const connectionEnd = $(this);

                if (connectionStart.parent().attr('id') !== connectionEnd.parent().attr('id')) {
                    createConnection(connectionStart, connectionEnd);
                }

                connectionStart.removeClass('active');
                connectionStart = null;
            }
        });

        function createConnection(start, end) {
            // Ensure start and end are valid jQuery objects with parent nodes
            if (!start || !end || start.length === 0 || end.length === 0) {
                console.warn('Invalid connection: missing start or end connector');
                return;
            }

            const startNode = start.parent();
            const endNode = end.parent();

            // Validate nodes exist
            if (startNode.length === 0 || endNode.length === 0) {
                console.warn('Cannot create connection: node not found');
                return;
            }

            const connection = {
                line: $('<div class="connection-line"></div>'),
                start: start,
                end: end
            };

            $('#workspace').append(connection.line);
            connections.push(connection);
            updateConnectionPosition(connection);
        }


        function updateConnectionPosition(connection) {
            // Validate connection object
            if (!connection || !connection.start || !connection.end) {
                console.warn('Invalid connection object');
                return;
            }

            const startNode = connection.start.parent();
            const endNode = connection.end.parent();

            // Validate nodes exist
            if (startNode.length === 0 || endNode.length === 0) {
                console.warn('Cannot update connection: node not found');
                return;
            }

            // Safely get positions with fallback
            const startPos = {
                left: parseFloat(startNode.css('left') || 0),
                top: parseFloat(startNode.css('top') || 0)
            };
            const endPos = {
                left: parseFloat(endNode.css('left') || 0),
                top: parseFloat(endNode.css('top') || 0)
            };

            const startX = startPos.left + (connection.start.position().left + connection.start.width() / 2);
            const startY = startPos.top + (connection.start.position().top + connection.start.height() / 2);
            const endX = endPos.left + (connection.end.position().left + connection.end.width() / 2);
            const endY = endPos.top + (connection.end.position().top + connection.end.height() / 2);

            const length = Math.sqrt(Math.pow(endX - startX, 2) + Math.pow(endY - startY, 2));
            const angle = Math.atan2(endY - startY, endX - startX) * 180 / Math.PI;

            connection.line.css({
                left: startX + 'px',
                top: startY + 'px',
                width: length + 'px',
                transform: `rotate(${angle}deg)`
            });
        }

        function updateConnections(currentScale) {
            connections.forEach(connection => {
                updateConnectionPosition(connection);
            });
        }

        $(document).on('click', '.mind-node, .card-node', function(e) {
            if (connectionStart) return;

            selectedNode = $(this);
            $('.mind-node, .card-node').css('border-color', 'transparent');
            $(this).css('border-color', '#007bff');

            toggleSidebar(true);
            $('#nodeTitle').val($(this).find('h3').text());
            $('#nodeDesc').val($(this).find('p').text());

            // Safely get initial colors with defaults
            const currentColor = $(this).css('color') || '#ffffff';
            const currentBg = $(this).css('background-color') || 'rgba(255,255,255,0.1)';

            try {
                $('#textColor').val(rgb2hex(currentColor));
                $('#bgColor').val(rgb2hex(currentBg));
            } catch (e) {
                console.log('Error converting colors:', e);
                // Set defaults if conversion fails
                $('#textColor').val('#ffffff');
                $('#bgColor').val('#ffffff');
            }

            $('#fontFamily').val($(this).css('font-family'));

            if ($(this).hasClass('card-node')) {
                $('#imageUrlField').show();
                $('#nodeImage').val($(this).find('img').attr('src'));
            } else {
                $('#imageUrlField').hide();
            }
        });

        // Função auxiliar para converter RGB para Hex
        function rgb2hex(rgb) {
            if (!rgb) return '#000000'; // Default color if null
            if (rgb.startsWith('#')) return rgb;
            if (rgb.startsWith('rgba')) {
                rgb = rgb.match(/^rgba\((\d+),\s*(\d+),\s*(\d+)/);
            } else {
                rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)/);
            }
            if (!rgb) return '#000000';
            return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
        }

        function hex(x) {
            const hex = parseInt(x).toString(16);
            return hex.length === 1 ? "0" + hex : hex;
        }

        $('#nodeEditor').submit(function(e) {
            e.preventDefault();
            if (selectedNode) {
                selectedNode.find('h3').text($('#nodeTitle').val());
                selectedNode.find('p').text($('#nodeDesc').val());

                // Aplica os estilos
                selectedNode.css({
                    color: $('#textColor').val(),
                    backgroundColor: $('#bgColor').val(),
                    fontFamily: $('#fontFamily').val()
                });

                if (selectedNode.hasClass('card-node')) {
                    selectedNode.find('img').attr('src', $('#nodeImage').val());
                }
            }
        });

        $('#deleteNode').click(function() {
            // excluir junto as ligações

            connections.forEach(connection => {
                // pegar id do nó pai
                const parentId = connection.start.parent().attr('id');


                if (connection.start.parent().attr('id') === selectedNode.attr('id') || connection.end.parent().attr('id') === selectedNode.attr('id')) {
                    connection.line.remove();
                    connections.splice(connections.indexOf(connection), 1);
                }
                console.log(connection);
            });

            if (selectedNode) {
                selectedNode.remove();
                selectedNode = null;
                toggleSidebar(false);
            }


        });

        function toggleSidebar(show) {
            if (show) {
                $('#sidebar').removeClass('hidden');
                $('.col-9').removeClass('expanded');
                $('#sidebar').removeClass('d-none');
            } else {
                $('#sidebar').addClass('hidden');
                setTimeout(() => {
                    $('#sidebar').addClass('d-none');
                }, 300);
                $('.col-9').addClass('expanded');
            }
        }

        toggleSidebar(false);



        // Inicialmente esconde o sidebar
        $('#sidebar').addClass('hidden');

        // Função para estruturar os nós a partir de um JSON
        function importNodesFromJson(jsonData) {
            const workspace = $('#workspace');
            workspace.empty(); // Clear the workspace before importing nodes

            // Ensure jsonData is parsed if it's a string
            if (typeof jsonData === 'string') {
                try {
                    jsonData = JSON.parse(jsonData);
                } catch (error) {
                    console.error('Invalid JSON format:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid JSON',
                        text: 'The provided JSON could not be parsed.'
                    });
                    return;
                }
            }

            // Validate the JSON structure
            if (!jsonData.nodes || !Array.isArray(jsonData.nodes)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid JSON',
                    text: 'The JSON is missing a valid nodes array.'
                });
                return;
            }

            // Clear existing connections
            connections.forEach(conn => conn.line.remove());
            connections = [];

            // Import nodes
            jsonData.nodes.forEach((nodeData) => {
                if (!nodeData.id || !nodeData.title) {
                    console.warn('Skipping invalid node:', nodeData);
                    return;
                }

                let node;
                // (rest of the node creation logic remains the same)
                // Criação dos nós com base no tipo
                if (nodeData.type === 'simple') {
                    node = $(`<div class="mind-node" id="node-${nodeData.id}">
                    <h3>${nodeData.title}</h3>
                    <p>${nodeData.description}</p>
                    <div class="connector" style="top: 50%; right: -5px;"></div>
                    <div class="connector" style="top: 50%; left: -5px;"></div>
                </div>`);
                } else if (nodeData.type === 'card') {
                    node = $(`<div class="card-node" id="node-${nodeData.id}">
                    <img src="${nodeData.image}" alt="Card image">
                    <h3>${nodeData.title}</h3>
                    <p>${nodeData.description}</p>
                    <div class="connector" style="top: 50%; right: -5px;"></div>
                    <div class="connector" style="top: 50%; left: -5px;"></div>
                </div>`);
                }

                // Definir posição do nó com base nas coordenadas
                node.css({
                    left: nodeData.position.x + 'px',
                    top: nodeData.position.y + 'px'
                });

                // Adicionar nó ao workspace
                workspace.append(node);

                // Tornar o nó arrastável
                node.draggable(settings_draggable);
            });

            // Modify inside importNodesFromJson function
            if (jsonData.connections && Array.isArray(jsonData.connections)) {
                jsonData.connections.forEach((connectionData) => {
                    const startNode = $(`#node-${connectionData.startNode}`);
                    const endNode = $(`#node-${connectionData.endNode}`);

                    if (startNode.length && endNode.length) {
                        const startConnectors = startNode.find('.connector');
                        const endConnectors = endNode.find('.connector');

                        // More flexible connector selection
                        const startConnector = startConnectors.length > connectionData.startConnectorIndex ?
                            startConnectors.eq(connectionData.startConnectorIndex) :
                            startConnectors.first();

                        const endConnector = endConnectors.length > connectionData.endConnectorIndex ?
                            endConnectors.eq(connectionData.endConnectorIndex) :
                            endConnectors.last();

                        if (startConnector.length && endConnector.length) {
                            createConnection(startConnector, endConnector);
                        }
                    }
                });
            }
        }
        // Função para exportar os nós e conexões para JSON
        function exportNodesToJson() {
            const nodes = [];
            const connections_json = [];

            // Exportar os nós
            $('.mind-node, .card-node').each(function() {
                const node = $(this);
                const nodeData = {
                    id: parseInt(node.attr('id').split('-')[1]), // Pega o ID do nó
                    title: node.find('h3').text(),
                    description: node.find('p').text(),
                    position: {
                        x: parseFloat(node.css('left')),
                        y: parseFloat(node.css('top'))
                    },
                    type: node.hasClass('card-node') ? 'card' : 'simple',
                    image: node.hasClass('card-node') ? node.find('img').attr('src') : ''
                };
                nodes.push(nodeData);
            });

            // Exportar as conexões
            connections.forEach(function(connection) {
                const startNode = $(connection.start).parent();
                const endNode = $(connection.end).parent();
                connections_json.push({
                    startNode: parseInt(startNode.attr('id').split('-')[1]),
                    endNode: parseInt(endNode.attr('id').split('-')[1]),
                    startConnectorIndex: $(connection.start).index(),
                    endConnectorIndex: $(connection.end).index()
                });
            });

            return {
                nodes: nodes,
                connections: connections_json
            };
        }




        // Função para salvar os nós e conexões como um arquivo JSON
        function saveAsJsonFile() {
            const jsonData = exportNodesToJson();
            const blob = new Blob([JSON.stringify(jsonData, null, 2)], {
                type: 'application/json'
            });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'nodes.json';
            link.click();
        }

        // Função para importar os nós de um arquivo JSON
        function loadFromJsonFile(file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const jsonData = JSON.parse(event.target.result);
                importNodesFromJson(jsonData);
            };
            reader.readAsText(file);
        }

        // Exemplo de como usar essas funções

        // Importar dados de JSON (você pode chamar isso de alguma forma, como um botão de upload)
        $('#importBtn').on('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                loadFromJsonFile(file);
            }
        });

        // Exportar dados de JSON (chame isso em algum lugar, como um botão de exportação)
        $('#exportBtn').on('click', function() {
            saveAsJsonFile();
        });

        $("#exportJson").click(function() {
            // copy to clipboard
            const jsonData = exportNodesToJson();
            const jsonStr = JSON.stringify(jsonData, null, 2);
            navigator.clipboard.writeText(jsonStr).then(function() {
                Toast.fire({
                    icon: "success",
                    title: "JSON copied to clipboard!"
                });
            }, function(err) {
                console.error('Failed to copy JSON to clipboard:', err);
                Toast.fire({
                    icon: "error",
                    title: "Failed to copy JSON to clipboard!"
                });
            });
        })

        $("#importJson").click(function() {
            // abrir swall e pegar textarea com json
            Swal.fire({
                title: 'Import JSON',
                html: '<textarea id="jsonInput" style="width: 100%; height: 200px"></textarea>',
                showCancelButton: true,
                confirmButtonText: 'Import',
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return new Promise(function(resolve, reject) {
                        const jsonStr = document.getElementById('jsonInput').value;
                        try {
                            // verificar se é um json

                            const jsonData = jsonStr;
                            importNodesFromJson(jsonData);
                            Toast.fire({
                                icon: "success",
                                title: "JSON imported successfully!"
                            })
                            resolve();


                        } catch (error) {
                            console.error('Invalid JSON format:', error);
                            Toast.fire({
                                icon: "error",
                                title: "Invalid JSON format!"
                            })

                            reject('Invalid JSON format');
                        }
                    });
                }
            })
        })

        var nodes_test = {
            "nodes": [{
                    "id": 1,
                    "title": "Nó 1",
                    "description": "Descrição do nó 1",
                    "position": {
                        "x": 100,
                        "y": 100
                    },
                    "type": "simple" // ou "card"
                },
                {
                    "id": 2,
                    "title": "Nó 2",
                    "description": "Descrição do nó 2",
                    "position": {
                        "x": 300,
                        "y": 200
                    },
                    "type": "card",
                    "image": "https://example.com/image.jpg"
                }
            ],
            "connections": [{
                "startNode": 1,
                "endNode": 2,
                "startConnectorIndex": 0,
                "endConnectorIndex": 1
            }]
        };


        importNodesFromJson(nodes_test);


    });
</script>