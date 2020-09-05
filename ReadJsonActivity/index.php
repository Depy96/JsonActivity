<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css"  crossorigin="anonymous">
        <title>Lettura Attività</title>

    </head>
    <body>
        <!-- Begin page content -->
        <main role="main" class="container">
            <h1 class="mt-5">Lettura Attività Tramite Json </h1>

            <form>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputAddress">Coordinate</label>
                        <input type="text" class="form-control" id="inputAddress" placeholder="Ricerca Posizione..." readonly value="">
                    </div>
                </div>

                <button type="button" id="btn_primary" class="btn btn-primary">Richiedi Attivit&agrave;</button>
            </form>
            <div class="table-responsive">
                <table class="table table-striped table-white" id="data_table">
                    <thead>
                        <tr>
                            <th >ID</th>
                            <th>Nome</th>
                            <th >Tipologia</th>
                            <th  id="distanza"><nobr>Distanza (m)</nobr></th>
                            <th  ></th>
                        </tr>
                    <tbody id="t_body"></tbody>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </main>


        <br><br><br><br><br>
        <div class=" text-muted card text-center" style="position: fixed; bottom: 0;  width: 100%;margin-top: 20px;">
            Copyright &COPY; 2020 - Product By Giuseppe De Palma
        </div>


        <div id="output"></div>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script>
            $("#btn_primary").click(function () {
                Send();
            });
            
            function Send(){
                
                
                var Data = new FormData();
                Data.append("ope", "read_json");

                if (latitudine < 1 || longitudine < 1) {
                    alert("Si prega attendere e di consentire la geolocalizzazione");
                    return false;
                }
                Data.append("lat", latitudine);
                Data.append("lon", longitudine);
                document.getElementById("btn_primary").innerText = "In corso...";
                document.getElementById("btn_primary").setAttribute("disabled", "disabled");
                $.ajax({
                    url: "controller.php",
                    type: 'post',
                    data: Data,
                    contentType: false,
                    processData: false,
                    success: function (result) {

                        //$('#output').html((result));
                        buildHtmlTable("#t_body", JSON.parse(result));
                        document.getElementById("btn_primary").innerText = "Aggiorna Attività";
                        document.getElementById("btn_primary").removeAttribute("disabled");
                    },
                    error: function () {
                        alert('Al momento non e\' possibile evadere la richiesta.\nSi prega di riprovare piu\' tardi...');
                    }
                });
            }
            
            var latitudine = 0, longitudine = 0;
            function get_position(position)
            {
                latitudine = position.coords.latitude;
                longitudine = position.coords.longitude;
                document.getElementById("inputAddress").value = latitudine.toString().substring(0, 9) + " - " + longitudine.toString().substring(0, 9);
            }
            function gestisciErrore(error) {
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        alert("Permesso negato dall'utente\nSi prega di consentire e/o abilitare la geolocalizzazione.");
                        self.location.reload();
                        break;
                    case error.POSITION_UNAVAILABLE:
                        alert("Impossibile determinare la posizione corrente\n" +
                                "Controllare che sia attiva la geolocalizzazione sul dispositivo");
                        self.location.reload();
                        break;
                    case error.TIMEOUT:
                        alert("Il rilevamento della posizione impiega troppo tempo");
                        break;
                    case error.UNKNOWN_ERROR:
                        alert("Si è verificato un errore sconosciuto");
                        break;
                }
            }
            if (navigator.geolocation)
                navigator.geolocation.getCurrentPosition(get_position, gestisciErrore);
            else
                alert('Il browser non supporta la geolocalizzazione');

            function buildHtmlTable(selector, data) {
                
                for (var i = 0; i < data.length; i++) {
                     json_d=JSON.parse(data[i]);
                     
                     
                    if(json_d.distance>0 ){
                   
                        tr = $('<tr/>');
                        tr.append("<td>" + (i+1) + "</td>");
                        tr.append("<td>" + json_d.name + "</td>");
                        tr.append("<td>" + json_d.type + "</td>");
                        tr.append("<td>" + json_d.distance + "</td>");
                        tr.append("<td><button class='btn  btn-primary  btn-round'   title='Ricerca Tramite Google Maps' onclick='window.open(\"https://www.google.com/maps/search/" + json_d.name + "\")' >Info</button></td>");
                        $(selector).append(tr);
                        
                    }            
                    
                }
                $('#data_table').DataTable();
                document.getElementById("distanza").click();
                 
            }
            
        </script>
    </body>
</html>
