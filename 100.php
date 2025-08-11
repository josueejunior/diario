<?php
error_reporting(1);

$TableTipoLei = new DB('tipo_lei',$ResultCliente[0]["banco"], $ResultCliente[0]["banco_user"], $ResultCliente[0]["banco_pass"],$ResultCliente[0]["host"]);
$TableLegislacao = new DB('legislacao',$ResultCliente[0]["banco"], $ResultCliente[0]["banco_user"], $ResultCliente[0]["banco_pass"],$ResultCliente[0]["host"]);
$TableAnexosLegislacao = new DB('anexo_legislacao', $ResultCliente[0]["banco"], $ResultCliente[0]["banco_user"], $ResultCliente[0]["banco_pass"],$ResultCliente[0]["host"]);

$resultTipoLei = $TableTipoLei->buscar();

include 'conectarel.php';

// Filtros da busca 
$search = isset($_GET['search']) ? $_GET['search'] : ''; 
$tipolei = isset($_GET['tipolei']) ? $_GET['tipolei'] : '';
$fundo = isset($_GET['fundo']) ? $_GET['fundo'] : '';
$numero = isset($_GET['numero']) ? $_GET['numero'] : '';
$ano = $ano_get = isset($_GET['ano']) ? $_GET['ano'] : '';

// Inicia a query, mas os filtros serão adicionados dinamicamente
$sql = "SELECT l.ano as anoletivo, t.id as IDT, t.tipo AS ttipolei, l.id, l.numero, l.tipo_id, l.sumula, l.ano, l.data, l.arquivo, f.fundo, f.sigla FROM legislacao AS l 
        LEFT JOIN fundo f ON f.id = l.fundo_id 
        LEFT JOIN tipo_lei t ON t.id = l.tipo_id 
        WHERE 1=1 ";

// Cria um array com os filtros a serem aplicados 
$filters = [];
if (!empty($search)) {
    $filters[] = [
        'placeholder' => ':search',
        'sql' => '( UCASE(l.sumula) LIKE UCASE(:search))',
        'value' => '%' . $search . '%',
        'param_type' => PDO::PARAM_STR,
    ];
}

if (!empty($numero)) {
    $filters[] = [
        'placeholder' => ':numero',
        'sql' => 'l.numero = :numero',
        'value' => $numero,
        'param_type' => PDO::PARAM_INT,
    ];
}

if (!empty($tipolei)) {
    $filters[] = [
        'placeholder' => ':tipolei',
        'sql' => 'l.tipo_id= :tipolei',
        'value' => $tipolei,
        'param_type' => PDO::PARAM_INT,
    ];
}


if (!empty($ano)) {
    $filters[] = [
        'placeholder' => ':ano',
        'sql' => 'l.ano = :ano',
        'value' => $ano,
        'param_type' => PDO::PARAM_STR,
    ];
}

foreach ($filters as $filter) {
    $sql .= " AND " . $filter['sql'];
}

$sql .= " order by l.ano DESC, l.numero DESC";

// Abre a conexão e define codificação UTF-8
$PDO = new PDO('mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DB, MYSQL_USER, MYSQL_PASSWORD);

// Cria o Prepared Statement
$stmt = $PDO->prepare($sql);

// Faz o bind dos valores dos filtros
foreach ($filters as $filter) {
    $stmt->bindValue($filter['placeholder'], $filter['value'], $filter['param_type']);
}

// Executa a query
$stmt->execute();

// Cria um array com os resultados
$legislacao = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql_ano = 'SELECT distinct(ano) FROM legislacao WHERE ano IS NOT NULL ORDER BY ano DESC';
$stmt = $PDO->prepare($sql_ano);
$stmt->execute();
$anos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!empty($tipolei)) {
    $sql_tipolei = 'SELECT * FROM tipo_lei WHERE id = ' . $tipolei;
    $stmt = $PDO->prepare($sql_tipolei);
    $stmt->execute();
    $obj_tipolei = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $obj_tipolei = null;
}

// Paginação
$porPagina = 10;
$paginas = ceil(count($legislacao) / $porPagina);
$pagina = empty($_POST['pag']) ? 1 : $_POST['pag'];
$primeiro = ($pagina - 1) * $porPagina;
$resultadoPagina = array_slice($legislacao, $primeiro, $porPagina);

$caminho = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];     
?> 

<script>
   $(document).ready(function() {
        var tipos_de_lei = <?php echo json_encode($resultTipoLei); ?>;

        $('#tipolei').on('change', function() {
        var novoValor = $(this).val();
        var lei_tipo = '';
        var lei_descricao = '';
        
        for (var i = 0; i < tipos_de_lei.length; i++) {
          if (tipos_de_lei[i]['id'] == novoValor) {
          lei_tipo = tipos_de_lei[i]['tipo'];
          lei_descricao = tipos_de_lei[i]['descricao'] || '';
          break;
          }
        }
        $('#tipo_lei').text(lei_tipo ? lei_tipo : 'Atos Oficiais');
        $('#tipo_lei_descricao').text(lei_descricao);
        });
      });
</script>


  <div class="container my-5">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light p-3 shadow-sm" style="--bs-breadcrumb-divider: '›';">
              <li class="breadcrumb-item">
                <a href="index.php" class="text-dark text-decoration-none">Inicio</a>
              </li>
              <li class="breadcrumb-item">
                <a href="<?= $_SERVER['REQUEST_URI'] ?>" class="text-dark text-decoration-none"><?= $resultPagina[0]["titulo"] ?></a>
              </li>
            </ol>
          </nav>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row">


          <div class="col-sm-9 col-md-9 col-xs-12">
              <?php
              if(!empty($obj_tipolei)){
              ?>
              <div class="row">
                  <div class="col sm-12 col-xs-12 padrao1_h2 padrao_interna">
                      <h1 class="underline_ccc contr_color_white"><?= $obj_tipolei[0]['tipolei'] ?></h1>
                  </div>
              </div>
              <?php
              }
              ?>
              <div class="row" id="contentAtual">
                <div id="content">
                  <form action="" method="get" role="form" style="background:#eee; padding:10px 10px 10px; text-align: center;">
                    <div class="row">
                      <div class="col sm-12 col-xs-12 padrao1_h2 padrao_interna" style="padding-bottom: 10px;">
                        <h4 class="underline_ccc contr_color_white"><strong>BUSCA DETALHADA</strong></h4>
                      </div>
                    </div>
                    <input type="hidden" name="meio" id="meio" value="1084">
                    <div class="row">
                      <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <label for="ano">Ano:</label>
                        <select class="form-control input-sm" style="font-size: 0.75rem;" id="ano" name="ano">
                          <option value="" selected="selected"> Todos </option>
                            <?php
                            foreach($anos as $ano){
                              $selected = $ano['ano'] == $ano_get ? 'selected' : ''; 
                                echo "<option style=\"width:5px\" {$selected} value='{$ano['ano']}' class=\"form-group\">{$ano['ano']}</option>";
                            }
                            ?>
                        </select>
                      </div>

                      <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <label for="tipolei">Tipo Lei:</label>
                        <select name="tipolei" class="form-control input-sm" style="font-size: 0.75rem;" id="tipolei">
                          <option value="" selected="selected">Todas</option>
                          <?php 
                          for($p=0;$p<count($resultTipoLei);$p++){ 
                            $selected = $resultTipoLei[$p]["id"] == $tipolei ? 'selected' : ''; 
                            echo "<option style=\"width:10px\" {$selected} value=\"".$resultTipoLei[$p]["id"]."\" class=\"form-group\">".$resultTipoLei[$p]["tipo"]."</option>";     
                          }    
                          ?>
                        </select>
                      </div>

                      <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label for="search">Descrição:</label>
                        <input type="text" class="form-control input-sm" style="font-size: 0.75rem;" id="search" name="search" placeholder="Descrição da pesquisa" value="<?=$search?>">
                      </div>

                      <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <label for="numero">Número:</label>
                        <input type="text" class="form-control input-sm" style="font-size: 0.75rem;" id="numero" name="numero" placeholder="Número da pesquisa">
                      </div>

                        <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <div class="no-print">
                                <br class="hidden-xs">
                                <button type="submit" class="btn btn-primary" style="font-size: 0.75rem;"><i class="fa fa-search"></i> FILTRAR</button>
                            </div>
                        </div>
                    </div>

                  </form></br>
                  <div class="mb-3">
                    <p id="tipo_lei_descricao" class="form-text text-muted" style="font-size: 0.95em; min-height: 1.5em;">
                      <?php
                        // Exibe a descrição do tipo de lei selecionado, se houver
                        if (!empty($obj_tipolei) && !empty($obj_tipolei[0]['descricao'])) {
                          echo htmlspecialchars($obj_tipolei[0]['descricao']);
                        }
                      ?>
                    </p>
                  </div>

                  <div style="text-align: end; padding-bottom: 10px;">
													<button id="btn" style="border-radius: 35px; --bs-btn-font-size: 0,75rem;" class="btn btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Imprimir Listagem"><i class="fas fa-print"></i> Imprimir</button>
													<button id="btn" style="border-radius: 35px; --bs-btn-font-size: 0,75rem;" class="btn btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Gerar Listagem em CSV"><i class="fas fa-file-excel"></i> Excel</button>
													<button type="button" style="border-radius: 35px; --bs-btn-font-size: 0,75rem;" class="btn btn-outline-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Gerar Listagem em PDF" onclick="CriaPDF()"><i class="fas fa-file-pdf"></i> PDF</button>
									</div>

                  <?php
                    if(count($resultadoPagina)>0){
                                $displayedTitles = [];
                                foreach($resultadoPagina as $i=>$legislacao){
                              
                              if (in_array($legislacao['sumula'], $displayedTitles)) {
                                continue; // Skip if the title has already been displayed
                            }
                            $displayedTitles[] = $legislacao['sumula'];

                              echo "<div class=\"panel panel-default\">";
                            echo "<table class=\"table table-bordered table-condensed tabelalicitacao\">
                              <tbody>"; 

                              echo"
                              <tr>
                                <td colspan=\"6\" class=\"tdpreto\" style=\"background-color:#0d6efd;\"><strong><font color=\"#ffffff\">Número: ".$legislacao['numero']."/".$legislacao['anoletivo']."</font></strong></td>
                              </tr>"; 
                            
                          if($legislacao['data']!="0000-00-00"){ 
                          echo"<tr>
                                <td width=\"100px\" class=\"tdcinza\" valign=\"top\" style=\"background-color:#f5f5f5;\"><strong>Data:</strong></td>
                                <td colspan=\"4\">".TrataData($legislacao['data'])."</td>
                              </tr>"; 
                          } 		

                          if(!empty($legislacao['sigla'])){
                              echo"
                                <tr>
                                  <td width=\"100px\" class=\"tdcinza\" valign=\"top\" style=\"background-color:#f5f5f5;\"><strong>Fundo:</strong></td>
                                  <td colspan=\"4\">".$legislacao['sigla']."</td>
                                </tr>
                              <tr>";
                          }
                              echo"
                              <tr>
                                <td width=\"100px\" class=\"tdcinza\" valign=\"top\" style=\"background-color:#f5f5f5;\"><strong>Súmula:</strong></td>
                                <td colspan=\"4\">".$legislacao['sumula']."</td>
                              </tr>
                              <tr>";
                              echo" <td class=\"tdcinza\" style=\"background-color:#f5f5f5;\"><strong>Tipo:</strong></td>";
                              echo"<td colspan=\"1\">";
                                echo $legislacao['ttipolei']; 
                              echo "</td>";
                              echo" <td colspan=\"1\" class=\"tdcinza\" style=\"background-color:#f5f5f5;\"><strong>Arquivo:</strong></td>";									
                                echo "<td>";
                                echo "<button type=\"button\" class=\"btn btn-primary\" style=\"--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;\">";
                                if(file_exists("documentos/legislacao/" . $legislacao["arquivo"])) {
                                    echo "<a href=\"documentos/legislacao/" . $legislacao["arquivo"] . "\" style=\"color: #ffff;\" target=\"_blank\">BAIXAR</a>";

                                  }else{
                                    echo "<a href=\"documentos/12250leis/" . $legislacao["arquivo"] . "\" style=\"color: #ffff;\" target=\"_blank\">BAIXAR</a>";
                                }
                                echo "</button>";
                              echo "</td>";
                              echo "</tr>";
                              $resultAnexosLegislacao = $TableAnexosLegislacao->buscar("id = '".$legislacao["id"]."'",NULL,array("created_at"=>"DESC"));
                              if(!empty($resultAnexosLegislacao)){
                              echo"<tr>
                              <td class=\"tdcinza\" style=\"background-color:#f5f5f5;\"><strong>Anexos:</strong></td>
                              <td colspan=\"4\">";
                              for($i=0;$i<count($resultAnexosLegislacao);$i++){
                                if(file_exists("documentos/arquivosbkp/".$resultAnexosLegislacao[$i]["anexo"])){
                              echo "<a href=\"documentos/12250leis/".$resultAnexosLegislacao[$i]["anexo"]."\" target=\"_blank\">".$resultAnexosLegislacao[$i]["descricao"]." - </a>";
                                } else {
                                  echo "<a href=\"documentos/anexo_legislacao/".$resultAnexosLegislacao[$i]["anexo"]."\" target=\"_blank\">".$resultAnexosLegislacao[$i]["descricao"]." - </a>";
                                }
                              }
                                echo"</td>
                              </tr>";
                            }
                                echo"</tbody>
                                </table>";
                              echo "</div>";

                          }

                      }else{

                          echo "Nenhuma Legislação encontrada.";	

                      }

                  ?>
                </div>

             
              </div>

          <div id="page-selection" class="text-center mt-4">
            <nav aria-label="Navegação de páginas">
                <ul class="pagination justify-content-center">
                    <li class="page-item">
                        <a class="page-link prev-set" href="#" aria-label="Anterior">
                            <span aria-hidden="true">&laquo;&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link prev-page" href="#" aria-label="Anterior">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <?php 
                    $start = max(1, $pagina - 2);
                    $end = min($paginas, $pagina + 2);
                    for ($i = $start; $i <= $end; $i++): ?>
                        <li class="page-item <?php echo ($i == $pagina) ? 'active' : ''; ?>">
                            <a class="page-link page-number" href="#" data-page="<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item">
                        <a class="page-link next-page" href="#" aria-label="Próximo">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link next-set" href="#" aria-label="Próximo">
                            <span aria-hidden="true">&raquo;&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
          </div>

          <script type="text/javascript">
            
             

              $(document).ready(function () {
                  $(".page-number").click(function (e) {
                      e.preventDefault();
                      var page = $(this).data("page");
                      post_page(page);
                  });

                  $(".prev-page").click(function (e) {
                      e.preventDefault();
                      var currentPage = <?php echo $pagina; ?>;
                      if (currentPage > 1) {
                          post_page(currentPage - 1);
                      }
                  });

                  $(".next-page").click(function (e) {
                      e.preventDefault();
                      var currentPage = <?php echo $pagina; ?>;
                      if (currentPage < <?php echo $paginas; ?>) {
                          post_page(currentPage + 1);
                      }
                  });

                  $(".prev-set").click(function (e) {
                      e.preventDefault();
                      var currentPage = <?php echo $pagina; ?>;
                      var newPage = Math.max(1, currentPage - 5);
                      post_page(newPage);
                  });

                  $(".next-set").click(function (e) {
                      e.preventDefault();
                      var currentPage = <?php echo $pagina; ?>;
                      var newPage = Math.min(<?php echo $paginas; ?>, currentPage + 5);
                      post_page(newPage);
                  });

                  function post_page(num) {
                      var form = document.createElement("form");
                      form.setAttribute("method", 'post');
                      form.setAttribute("action", '');

                      var hiddenField = document.createElement("input");
                      hiddenField.setAttribute("type", "hidden");
                      hiddenField.setAttribute("name", 'pag');
                      hiddenField.setAttribute("value", num);
                      form.appendChild(hiddenField);

                      document.body.appendChild(form);
                      form.submit();
                  }
              });
          </script>

              <p>
                Última Atualização em: <?php
                  $resultData = $TableLegislacao->buscar(NULL, 1, array("legislacao.created_at" => "DESC"));
                  if (!empty($resultData)) {
                      for ($b = 0; $b < count($resultData); $b++) {
                          echo TrataDataHora($resultData[0]['created_at']);
                      }
                  } else {
                      echo date('d/m/Y H:i:s', filemtime(__FILE__)); // Use the file's last modification time
                  }
                ?>
              </p>

								</div> 
                <div class="col-sm-3">                
                 <?php include 'slidebar.php'; ?>
                 </div> 
               </div>
					</div>        
				</div>
      
  </div>
</div>    
</body>
</html>


<script>
			document.addEventListener("DOMContentLoaded", function() {
				var accordions = document.querySelectorAll(".accordion");

				accordions.forEach(function(accordion) {
					accordion.addEventListener("click", function() {
						this.classList.toggle("active");
						var panel = this.nextElementSibling;

						if (panel.style.maxHeight) {
							panel.style.maxHeight = null;
						} else {
							panel.style.maxHeight = panel.scrollHeight + "px";
						}
					});
				});
			});
		</script>

		

	<script type="text/javascript">
		function MM_showHideLayers() { //v9.0
		var i,p,v,obj,args=MM_showHideLayers.arguments;
		for (i=0; i<(args.length-2); i+=3) 
		with (document) if (getElementById && ((obj=getElementById(args[i]))!=null)) { v=args[i+2];
			if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
			obj.visibility=v; }
		}

	</script>

	<script>
		$(function() {
			$(".exportToExcel").click(function(e){
				debugger
				var table = $('.table2excel');
				if(table && table.length){
					var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
					$(table).table2excel({
						exclude: ".noExl",
						name: "Licitacao",
						filename: "licitacao" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
						fileext: ".xls",
						exclude_img: true,
						exclude_links: true,
						exclude_inputs: true,
						preserveColors: preserveColors
					});
				}
			});
			
		});
	</script>


	<script>
		document.addEventListener("DOMContentLoaded", function() {
			const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
			
			tooltipTriggerList.forEach(function(tooltipTriggerEl) {
				new bootstrap.Tooltip(tooltipTriggerEl);
			});
		});

		// Código para o acordeão
		var accordions = document.querySelectorAll(".accordion");

		accordions.forEach(function(accordion) {
			accordion.addEventListener("click", function() {
				this.classList.toggle("active");
				var panel = this.nextElementSibling;

				if (panel.style.maxHeight) {
					panel.style.maxHeight = null;
				} else {
					panel.style.maxHeight = panel.scrollHeight + "px";
				}
			});
		});
	</script>


	<script>
		function CriaPDF() {
			var minhaTabela = document.getElementById('tabela').innerHTML;

			var style = "<style>";
			style = style + "table {width: 100%;font: 20px Calibri;}";
			style = style + "table, th, td {border: solid 1px #DDD; border-collapse: collapse;";
			style = style + "padding: 2px 3px;text-align: center;}";
			style = style + "</style>";

			// CRIA UM OBJETO WINDOW
			var win = window.open('', '', 'height=700,width=700');

			win.document.write('<html><head>');
			win.document.write('<title>Empregados</title>');   // <title> CABEÇALHO DO PDF.
			win.document.write(style);                       // INCLUI UM ESTILO NA TAB HEAD
			win.document.write('</head>');
			win.document.write('<body>');
			win.document.write(minhaTabela);                   // O CONTEUDO DA TABELA DENTRO DA TAG BODY
			win.document.write('</body></html>');

			win.document.close(); 	                            // FECHA A JANELA

			win.print();                                        // IMPRIME O CONTEUDO
		}
	</script>

	<script>
		document.getElementById('btn').onclick = function() {
			window.print();
		};
	</script>



	<script type="text/javascript">
		$("#contentBusca").hide();
		$("#selectAno").change(function(){
			var id = $("#selectAno").val();
			$("#form_ano").trigger('submit');
		});
	</script>



