<html>

<head>
    <title></title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

    <style>
        .center {

            text-align: center;
        }

        /* .modal-dialog-edit {
            max-height: 70vh;
        }

        .modal-content-edit {
            margin-top: -16px;
            margin-bottom: 10px;
        } */


        .spacebtn {
            margin-right: 3%;
        }

        .attention {
            color: red;
        }

        tbody tr:nth-child(odd) {
            background-color: EFEFEF;
        }

        tbody tr:nth-child(even) {
            background-color: D3D3D3;
        }

        tbody tr:hover {
            background-color: C0C0C0 !important;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Liste des personnes de l'université</h2>
                <button class="btn btn-primary mt-2" name="createtypepersonne" id="createtypepersonne">Ajouter un Type Personne</button>

                <br>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">NOM</th>
                            <th scope="col">PRENOM</th>
                            <th scope="col">SEXE</th>
                            <th scope="col">TYPE</th>
                            <th scope="col">PHOTO</th>
                            <th scope="col">DATE</th>
                            <th scope="col" class="center">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($personnes as $total)

                        <tr>
                            <td class="id">{{ $total->id}}</td>
                            <td>{{ $total->nom }}</td>
                            <td>{{ $total->prenom }}</td>
                            <td>{{ $total->sexe }}</td>
                            <td>{{ $total->libelle }}</td>
                            <td>
                                @if($total->photo !== "etudiant_photo")
                                <img src="{{ asset('uploads/' . $total->photo) }}" width="50">
                                @else
                                <img src="{{ asset('uploads/etudiants.jpg') }}" width="50">
                                @endif
                            </td>
                            <td>{{ $total->date_formatted }}</td>

                            <td>
                                <button type="button" class="edit btn btn-success" data-toggle="modal" data-target="#edit_form" id="{{ $total->id}}">Modifier</button>

                                <button type="button" class="remove btn btn-danger" data-toggle="modal" data-target="#myModal" id="{{ $total->id}}">Supprimer</button>



                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a id="create" class="btn btn-primary">Ajouter une nouvelle personne</a>

            </div>
        </div>

    </div>

    <!-- Modal de modification -->
    <div class="modal create_form" tabindex="-1" role="dialog" id="edit_form">
        <div class="modal-dialog modal-dialog-edit" role="document">
            <div class="modal-content modal-content-edit">
                <div class="modal-header modal-header-edit">
                    <h3 class="modal-title"><strong>Modification</strong></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body modal-body-edit">
                    <div id="inscription_edit" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <input type="hidden" name="id_personne" id="id_personne">
                            <div class="form-group">
                                <label for="nom_edit">Nom</label>
                                <input type="text" name="nom_edit" id="nom_edit" class="form-control">
                            </div>
                            <div class="form-group mt-2">
                                <label for="prenom_edit">Prenom</label>
                                <input type="text" name="prenom_edit" id="prenom_edit" class="form-control">
                            </div>

                            <div class="mt-2">
                                <label for="sexe_edit" class="form-check-label">SEXE :</label>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-check-inline">
                                            <input type="radio" name="sexe_edit" value="M" id="sexe_edit" class="form-check-input">
                                            <label class="form-check-label">Homme</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-check-inline">
                                            <input type="radio" name="sexe_edit" value="F" id="sexe_edit" class="form-check-input">
                                            <label class="form-check-label">Femme</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-2">
                                <label for="typepersonne_edit">Type de personne</label>
                                <select name="typepersonne_edit" id="typepersonne_edit" class="form-control" onchange="affiche_edit(this.value)">
                                    @foreach($types as $typePersonne)
                                    <option value="{{ $typePersonne->id }}">{{ $typePersonne->libelle }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>




                            <div class="from-group mt-2" id="userfiles_edit" style="display: none;">
                                <label for="userfile_edit">PHOTO</label>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-control">
                                            <input type="file" name="userfile_edit" id="userfile_edit" size="20" />
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="mt-2" id="visualiser_edit"></div>
                                    </div>

                                    <div class="centre mt-2 col-sm-2 " id="remplace" style="display: none;">
                                        <img src="{{ asset('uploads/remplace.png') }}" width="50">
                                        <span> REMPLACE</span>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="center mt-2" id="maphoto"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label for="dates_edit">Date</label>
                            <input type="date" name="dates_edit" id="dates_edit" class="form-control">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-success mt-2" id="save_edit">Valider</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Modal de formulaire -->
    <div class="modal" tabindex="-1" role="dialog" id="create_form">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><strong>CREATION PERSONNE</strong></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form method="post" id="inscription"  enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <!--<input type="hidden" name="id_personne" id="id_personne">-->
                            <div class="form-group">
                                <label for="nom">Nom</label>
                                <input type="text" name="nom" id="nom" class="form-control">
                            </div>
                            <div class="form-group mt-2">
                                <label for="prenom">Prenom</label>
                                <input type="text" name="prenom" id="prenom" class="form-control">
                            </div>
                            <div class="mt-2">
                                <label for="sexe" class="form-check-label">SEXE :</label>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-check-inline">
                                            <input type="radio" name="sexe" value="M" id="sexe" class="form-check-input">
                                            <label class="form-check-label">Homme</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-check-inline">
                                            <input type="radio" name="sexe" value="F" id="sexe" class="form-check-input">
                                            <label class="form-check-label">Femme</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-2">
                                <label for="typepersonne">Type de personne</label>
                                <select name="typepersonne" id="typepersonne" class="form-control" onchange="affiche(this.value)">
                                    @foreach($types as $typePersonne)
                                    <option value="{{ $typePersonne->id }}">{{ $typePersonne->libelle }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="from-group mt-2" id="userfiles" style="display: none;">
                                <label for="userfile">PHOTO</label>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-control">
                                            <input type="file" name="userfile" id="userfile" size="20" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="center mt-2" id="visualiser">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-2">
                                <label for="dates">Date</label>
                                <input type="date" name="dates" id="dates" class="form-control">
                            </div>
                        </div>
                        <div class="card-footer">
                        <input class="btn-submit btn btn-success mt-2" type="submit" id="saveandexit" name="saveandexit" value="Enregistrer&Quitter">
                        <!-- <button class="btn btn-success mt-2" name="saveandexit" id="saveandexit"></button> -->
                            <button class="btn btn-success mt-2" name="save" id="save">Enregistrer</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>


    <!-- Modal de suppression -->
    <div class="modal" tabindex="-1" role="dialog" id="myModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><strong>ATTENTION</strong></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="attention">Attenion!!!</p>
                    <p>Voulez vous supprimez cette personne</p>
                    <span id="contenu"></span>
                    <input type="hidden" name="id_personne_supp" id="id_personne_supp">
                </div>
                <div class="modal-footer">
                    <button class="deleteid btn btn-danger mt-2" id="deleteid">Supprimer</button>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Type personne ajouter -->
    <div class="modal create_type" id="create_type" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">AJOUTER UN TYPE PERSONNE</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="Addtypepersonne">
                        @csrf
                        <div class="">

                            <div class="form-group">
                                <label for="id_type">Identifiant</label>
                                <input type="text" name="id_type" id="nom_type" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="nom_type">Nom</label>
                                <input type="text" name="nom_type" id="nom_type" class="form-control">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="save_type" class="btn btn-primary">Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        

        $("#createtypepersonne").click(function() {
            $('#create_type').addClass('show');

            $('#save_type').click(function(e) {
                e.preventDefault();

                $.ajax({
                    data: $('#Addtypepersonne').serialize(),
                    url: "{{route ('addtype') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('#create_type').removeClass('show');
                        window.location.reload();
                        //alert('ok');
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        alert('erreur ajax type personne');
                    }
                });
            });
        });


        $("#create").click(function() {
            $('#create_form').addClass('show');
            $("#visualiser").empty();
            $("#userfile").val('');

            $("#save").click(function(e) {
                e.preventDefault();
                // Créez un objet pour stocker les données à envoyer
                var formData = new FormData();

                // Ajoutez chaque champ individuellement à l'objet formData
                formData.append('nom', $('#nom').val());
                formData.append('prenom', $('#prenom').val());
                formData.append('sexe', $('input[name="sexe"]:checked').val());
                formData.append('typepersonne', $('#typepersonne').val());
                formData.append('dates', $('#dates').val());
                var file = $('#userfile')[0].files[0];
                formData.append('userfile', file);

                if (typeof file === 'undefined') {
                    formData.append('userfile', 'etudiant_photo');
                } else {
                    formData.append('userfile', file);
                }

                $.ajax({
                    url: "{{ route('insert') }}",
                    type: "POST",
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,

                    success: function(data) {
                        //$('#create_form').removeClass('show');
                        $("#nom").val('');
                        $("#prenom").val('');
                        //$("#sexe").val('');
                        $("#visualiser").empty();
                        //$("#typepersonne").val();
                        $("#userfile").val('');
                        $("#dates").val('');

                        $("tbody").empty();
                        for (var i = 0; i < data.length; i++) {

                            var dates = moment(data[i]['datenaissance']);
                            var dateFormate = dates.format('YYYY-MM-DD');

                            var editButton = '<button type="button" class="edit spacebtn btn btn-success" data-toggle="modal" data-target="#edit_form" id="' + data[i]['id'] + '">Modifier</button>';

                            var deleteButton = '<button type="button" class="remove btn btn-danger" data-toggle="modal" data-target="#myModal" id="' + data[i]['id'] + '">Supprimer</button>';

                            var photoSrc = (data[i]['photo'] !== "etudiant_photo") ? "{{ asset('uploads/') }}" + "/" + data[i]['photo'] : "{{ asset('uploads/etudiants.jpg') }}";
                            var imgElement = '<img src="' + photoSrc + '" width="50">';

                            $("tbody").append(
                                "<tr><td>" + data[i]['id'] + "</td><td>" + data[i]['nom'] + "</td><td>" + data[i]['prenom'] +
                                "</td><td>" + data[i]['sexe'] + "</td><td>" + data[i]['libelle'] + "</td><td>" + imgElement +
                                "</td><td>" + dateFormate + "</td><td>" + editButton + deleteButton + "</td></tr>"
                            );
                        }

                        
                    },

                    error: function(data) {
                        console.log('Error:', data);
                        //alert('Erreur insertion');
                    }
                    

                });

            });
        });

        $("#saveandexit").click(function() {
            $('#create_form').removeClass('show');
            $('#inscription').attr('action', '{{route('insertphp')}}');
        });


        $("tbody").on("click", ".edit", function() {

            $('#edit_form').addClass('show');
            $("#visualiser_edit").empty();
            $('#remplace').css("display", "none");
            $("#userfile_edit").val('');
            var id = $(this).attr('id');
            $('#id_personne').val(id);
            $.ajax({
                data: {
                    id: id
                },
                url: '/recupediter/' + id,
                type: "GET",
                dataType: 'json',
                success: function(data) {
                    $('#maphoto').empty();
                    var dates = moment(data.datenaissance);
                    var dateFormate = dates.format('YYYY-MM-DD');
                    var routephoto = "{{ asset('uploads/') }}" + "/" + data.photo;
                    $('#nom_edit').val(data.nom);
                    $('#prenom_edit').val(data.prenom);
                    $('input[name="sexe_edit"][value="' + data.sexe + '"]').prop('checked', true);
                    $('#typepersonne_edit option[value="' + data.idtypepersonne + '"]').prop('selected', true);
                    $('#dates_edit').val(dateFormate);
                    $('#maphoto').append('<img src="' + routephoto + '" width="110" height="55">');
                    if (data.idtypepersonne != "1") {
                        $('#userfiles_edit').css("display", "block");
                        $('#userfile_edit').val('');
                        $('#userfile_display').text(data.photo);
                    } else {
                        $('#userfiles_edit').css("display", "none");
                    }

                },
                error: function(data) {
                    console.log('Error:', data);
                    //alert('Erreur modif');
                }

            });

            $("#save_edit").click(function() {

                var id = $('#id_personne').val();

                // Créez un objet pour stocker les données à envoyer
                var formData = new FormData();

                // Ajoutez chaque champ individuellement à l'objet formData
                formData.append('id_personne', id);
                formData.append('nom_edit', $('#nom_edit').val());
                formData.append('prenom_edit', $('#prenom_edit').val());
                formData.append('sexe_edit', $('input[name="sexe_edit"]:checked').val());
                formData.append('typepersonne_edit', $('#typepersonne_edit').val());
                formData.append('dates_edit', $('#dates_edit').val());

                var file = $('#userfile_edit')[0].files[0];
                formData.append('userfile_edit', file);
                if (typeof file === 'undefined') {
                    formData.append('userfile_edit', 'etudiant_photo');
                } else {
                    formData.append('userfile_edit', file);
                }

                $.ajax({
                    url: '/editer/' + id,
                    type: "POST",
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data) {

                        $('#edit_form').removeClass('show');
                        $("tbody").empty();
                        for (var i = 0; i < data.length; i++) {

                            var dates = moment(data[i]['datenaissance']);
                            var dateFormate = dates.format('YYYY-MM-DD');

                            var editButton = '<button type="button" class="edit spacebtn btn btn-success" data-toggle="modal" data-target="#edit_form" id="' + data[i]['id'] + '">Modifier</button>';

                            var deleteButton = '<button type="button" class="remove btn btn-danger" data-toggle="modal" data-target="#myModal" id="' + data[i]['id'] + '">Supprimer</button>';

                            var photoSrc = (data[i]['photo'] !== "etudiant_photo") ? "{{ asset('uploads/') }}" + "/" + data[i]['photo'] : "{{ asset('uploads/etudiants.jpg') }}";
                            var imgElement = '<img src="' + photoSrc + '" width="50">';

                            $("tbody").append(
                                "<tr><td>" + data[i]['id'] + "</td><td>" + data[i]['nom'] + "</td><td>" + data[i]['prenom'] +
                                "</td><td>" + data[i]['sexe'] + "</td><td>" + data[i]['libelle'] + "</td><td>" + imgElement +
                                "</td><td>" + dateFormate + "</td><td>" + editButton + deleteButton + "</td></tr>"
                            );
                        }

                    },
                    error: function(data) {
                        console.log('Error:', data);
                        alert('Erreur modif');
                    }
                });

            });

        });


        $("tbody").on("click", ".remove", function() {

            $('#myModal').addClass('show');
            var id = $(this).attr('id');
            $('#id_personne_supp').val(id);
            $.ajax({
                data: {
                    id: id
                },
                url: '/recupediter/' + id,
                type: "GET",
                dataType: 'json',
                success: function(data) {
                    $("#contenu").empty();
                    $('#contenu').append("l'identifiant : " + data.id);
                },
                error: function(data) {
                    alert('Erreur suppresion');
                }
            });

            $(".deleteid").click(function() {

                var id = $('#id_personne_supp').val();
                $.ajax({
                    data: {
                        id: id
                    },
                    url: '/delete/' + id,
                    type: "GET",
                    dataType: 'json',
                    success: function(data) {
                        $('#myModal').removeClass('show');

                        $("tbody").empty();
                        for (var i = 0; i < data.length; i++) {

                            var dates = moment(data[i]['datenaissance']);
                            var dateFormate = dates.format('YYYY-MM-DD');

                            var editButton = '<button type="button" class="edit spacebtn btn btn-success" data-toggle="modal" data-target="#edit_form" id="' + data[i]['id'] + '">Modifier</button>';

                            var deleteButton = '<button type="button" class="remove btn btn-danger" data-toggle="modal" data-target="#myModal" id="' + data[i]['id'] + '">Supprimer</button>';

                            var photoSrc = (data[i]['photo'] !== "etudiant_photo") ? "{{ asset('uploads/') }}" + "/" + data[i]['photo'] : "{{ asset('uploads/etudiants.jpg') }}";
                            var imgElement = '<img src="' + photoSrc + '" width="50">';

                            $("tbody").append(
                                "<tr><td>" + data[i]['id'] + "</td><td>" + data[i]['nom'] + "</td><td>" + data[i]['prenom'] +
                                "</td><td>" + data[i]['sexe'] + "</td><td>" + data[i]['libelle'] + "</td><td>" + imgElement +
                                "</td><td>" + dateFormate + "</td><td>" + editButton + deleteButton + "</td></tr>"
                            );
                        }
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        //alert('Erreur suppression');
                    }
                });
            });

        });


        $(".close").click(function() {
            $('.modal').removeClass('show');
        });

        $('#userfile').change(function(e) {
            var input = e.currentTarget,
                visualiser = $("#visualiser");
            if (input.files && visualiser.length) {
                visualiser.empty();
                for (var i = 0; i < input.files.length; i++) {
                    var file = input.files[i],
                        element = null;
                    if (file.type.indexOf('image') === 0) {
                        element = $('<img>');
                        element.attr('src', URL.createObjectURL(file));
                        element.attr('width', '110');
                        element.attr('height', '55'); // Ajustez la largeur si nécessaire
                        // Vous pouvez ajouter d'autres attributs ou classes à l'élément image si nécessaire
                    } else {
                        visualiser.text('Impossible de charger la ressource');
                    }
                    if (element) {
                        visualiser.append(element);
                    }
                }
            }
        });

        $('#userfile_edit').change(function(e) {
            var input = e.currentTarget,
                visualiser = $("#visualiser_edit");
            $('#remplace').css("display", "block");
            if (input.files && visualiser.length) {
                visualiser.empty();
                for (var i = 0; i < input.files.length; i++) {
                    var file = input.files[i],
                        element = null;
                    if (file.type.indexOf('image') === 0) {
                        element = $('<img>');
                        element.attr('src', URL.createObjectURL(file));
                        element.attr('width', '110');
                        element.attr('height', '55'); // Ajustez la largeur si nécessaire
                        // Vous pouvez ajouter d'autres attributs ou classes à l'élément image si nécessaire
                    } else {
                        visualiser.text('Impossible de charger la ressource');
                    }
                    if (element) {
                        visualiser.append(element);
                    }
                }
            }
        });
    </script>




    <script type="text/javascript">
        function affiche(typepersonne) {
            var photos = document.getElementById("userfiles");
            if (typepersonne == 2) {
                photos.style.display = "block";
            } else {
                photos.style.display = "none";
            }
        }

        function affiche_edit(typepersonne_edit) {
            var photos = document.getElementById("userfiles_edit");
            if (typepersonne_edit == 2) {
                photos.style.display = "block";
            } else {
                photos.style.display = "none";
            }
        }
    </script>


</body>

</html>