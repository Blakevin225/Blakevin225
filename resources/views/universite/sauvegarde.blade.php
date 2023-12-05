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

                    <div method="post" id="inscription" class="form-horizontal" enctype="multipart/form-data">
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
                                <div class="form-control">
                                    <input type="file" name="userfile" id="userfile" size="20" />
                                </div>
                            </div>

                            <div class="form-group mt-2">
                                <label for="dates">Date</label>
                                <input type="date" name="dates" id="dates" class="form-control">
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-success mt-2" name="save" id="save">Valider</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>