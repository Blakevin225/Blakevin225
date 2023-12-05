<html>
<head>
        <title></title>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    

</head>
<body>



<div class="container">

    <div class="card">
        <div class="card-header">
            <h2>FORMULAIRE</h2>
        </div>
       <form method="post" id="inscription" class="form-horizontal" enctype="multipart/form-data">
       @csrf
        <div class="card-body">
                <!--<input type="hidden" name="id_personne" id="id_personne">-->
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" name="nom" id="nom" class="form-control">
            </div>
            <div class="form-group mt-2">
                <label for="prenom">Prenom</label>
                <input  type="text" name="prenom" id="prenom" class="form-control">
            </div>
            <div class="mt-2">
                <label for="sexe" class="form-check-label">SEXE :</label>
                <div class="form-check-inline">
                    <input type="radio" name="sexe" value="M" id="sexe" class="form-check-input">
                    <label class="form-check-label">Homme</label>
                </div>
                <div class="form-check-inline"> 
                    <input type="radio" name="sexe" value="F" id="sexe" class="form-check-input">
                    <label class="form-check-label">Femme</label>
                </div>
            </div>

            <div class="form-group mt-2">
                <label for="typepersonne">Type de personne</label>
                <select name="typepersonne" id="typepersonne" class="form-control" onchange ="affiche(this.value)">
                    @foreach($types as $typePersonne)
                        <option value="{{ $typePersonne->id }}">{{ $typePersonne->libelle }}</option>
                    @endforeach
                </select>
            </div>

            <div class="from-group mt-2" id="userfiles" style="display: none;" >        
                <label for="userfile">PHOTO</label>
                <div class="form-control">
                    <input type="file" name="userfile" id="userfile" size="20" />
                </div>
            </div>

            <div class="form-group mt-2" >
                <label for="dates" >Date</label>
                <input type="date" name="dates" id="dates" class="form-control">
            </div>
        </div>
        <div class="card-footer">
                <button class="btn btn-success mt-2" name="save" id="save" >Save</button>
        </div>
      </form>
    </div>
</div>
<div class="mt-4"></div>
<!-- liste des personne-->
<div class="liste">
    
</div>

<script type="text/javascript">

$.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

    $("#save").click(function(e){

        e.preventDefault();
        $(this).html('Sending..');

       var nom = $("#nom").val();
       var prenom = $("#prenom").val();
       var sexe = $("#sexe").val();
       var typepersonne = $("#typepersonne").val();
       var dates = $("#dates").val();

       //alert(dates);

        $.ajax({
          data: $('#inscription').serialize(),
          url: "{{ route('insert') }}",
          type: "POST",
          //data: {typepersonne : typepersonne, nom: nom, prenom: prenom, sexe: sexe, dates: dates},
          dataType: 'json',

          success: function(data) {
            window.location.href = "{{ route('liste') }}";
            },
          error: function(data) {
                console.log('Error:', data);
                alert('Erreur insertion');
            }

        });

    });




</script>



<script type="text/javascript">
    
function affiche(typepersonne){
    var photos = document.getElementById("userfiles");
    if( typepersonne == 2){
        photos.style.display = "block";
    }else{
        photos.style.display = "none";
    }
}
</script> 

</body>
</html>