<?php

namespace App\Http\Controllers;

use App\Models\Personne;
use App\Models\TypePersonne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
//use Carbon\Carbon;
//use DataTables;




class PersonneController extends Controller
{
        public function __construct()
        {
                //  $this->middleware('auth');
        }

        public function index()
        {

                $personnes = Personne::join('typepersonne', 'idtypepersonne', '=', 'typepersonne.id')
                        ->select('personne.*', 'libelle', Personne::raw('DATE_FORMAT(personne.datenaissance, "%Y-%m-%d") AS date_formatted'))
                        ->get();

                $types = TypePersonne::all();

                return view('universite.liste', compact('personnes', 'types'));
        }

        public function recupForEdit($id)
        {
                $personne_edit = Personne::find($id);
                return response()->json($personne_edit);
        }

        public function edit(Request $request, $id)
        {
                $input = $request->all();
                $validator = Validator::make($input, [
                        'typepersonne_edit' => 'required',
                        'nom_edit' => 'required',
                        'prenom_edit' => 'required|min:11|max:11',
                        'sexe_edit' => 'required',
                        'dates_edit' => 'required',
                ]);
                if ($validator->fails()) {
                        return view('universite.liste');
                } else {
                        $personne_editer = Personne::findOrFail($id);

                        $donnee_edit = [
                                'idtypepersonne' => $request->input('typepersonne_edit'),
                                'nom' => $request->input('nom_edit'),
                                'prenom' => $request->input('prenom_edit'),
                                'sexe' => $request->input('sexe_edit'),
                                'datenaissance' => $request->input('dates_edit')
                        ];

                        if ($request->hasFile('userfile_edit')) {
                                // Si une nouvelle photo est téléchargée, enregistrez-la et mettez à jour le champ photo
                                $photoName = time() . '.' . $request->file('userfile_edit')->getClientOriginalExtension();
                                $request->file('userfile_edit')->move(public_path('uploads'), $photoName);
                                $donnee_edit['photo'] = $photoName;

                                // Supprimez l'ancienne photo s'il en existe une
                                if ($personne_editer->photo !== "etudiant_photo") {
                                        $anciennePhoto = public_path('uploads') . '/' . $personne_editer->photo;
                                        if (file_exists($anciennePhoto)) {
                                                unlink($anciennePhoto); // Suppression de l'ancienne photo du dossier
                                        }
                                }
                        } else {
                                // Si aucune nouvelle photo n'est téléchargée, conservez l'ancienne photo
                                if ($request->input('typepersonne_edit') == "1") {
                                        $donnee_edit['photo'] = "etudiant_photo";
                                }
                        }

                        $personne_editer->update($donnee_edit);
                }
                $all_personne = Personne::join('typepersonne', 'idtypepersonne', '=', 'typepersonne.id')
                        ->select('personne.*', 'typepersonne.libelle')->get();

                return response()->json($all_personne);
        }

        public function delete($id)
        {

                $personne_delete = Personne::findOrFail($id);
                $personne_delete->delete();
                $all_personne = Personne::join('typepersonne', 'idtypepersonne', '=', 'typepersonne.id')
                        ->select('personne.*', 'typepersonne.libelle')->get();
                return response()->json($all_personne);
        }


        public function ajaxRequestPost(Request $request)
        {
               
                $input = $request->all();
                $validator = Validator::make($input, [
                        'typepersonne' => 'required',
                        'nom' => 'required',
                        'prenom' => 'required',
                        'sexe' => 'required',
                        'dates' => 'required',
                ]);
                if ($validator->fails()) {
                        return view('universite.liste');
                } else {
                        $donnee = array(
                                'idtypepersonne' => $request->input('typepersonne'),
                                'nom' => $request->input('nom'),
                                'prenom' => $request->input('prenom'),
                                'sexe' => $request->input('sexe'),
                                'datenaissance' => $request->input('dates')
                        );

                        if ($request->hasFile('userfile')) {
                                $photoName = time() . '.' . $request->file('userfile')->getClientOriginalExtension();
                                $request->file('userfile')->move(public_path('uploads'), $photoName);
                                $donnee['photo'] = $photoName;
                        } else {
                                if ($request->input('typepersonne') !== "2") {
                                        $donnee['photo'] = "etudiant_photo";
                                }
                        }


                        Personne::create($donnee);
                }

                $new_personne = Personne::join('typepersonne', 'idtypepersonne', '=', 'typepersonne.id')
                                        ->select('personne.*', 'typepersonne.libelle')->get();
                return response()->json($new_personne);

                
        }

        public function phpRequestPost(Request $request)
        {
                // $personness = Personne::join('typepersonne', 'idtypepersonne', '=', 'typepersonne.id')
                //         ->select('personne.*', 'libelle', Personne::raw('DATE_FORMAT(personne.datenaissance, "%Y-%m-%d") AS date_formatted'))
                //         ->get();

                //$types = TypePersonne::all();

                $input = $request->all();
                $validator = Validator::make($input, [
                        'typepersonne' => 'required',
                        'nom' => 'required',
                        'prenom' => 'required',
                        'sexe' => 'required',
                        'dates' => 'required',
                ]);
                if ($validator->fails()) {
                        return view('universite.liste');
                } else {
                        $donnee = array(
                                'idtypepersonne' => $request->input('typepersonne'),
                                'nom' => $request->input('nom'),
                                'prenom' => $request->input('prenom'),
                                'sexe' => $request->input('sexe'),
                                'datenaissance' => $request->input('dates')
                        );

                        if ($request->hasFile('userfile')) {
                                $photoName = time() . '.' . $request->file('userfile')->getClientOriginalExtension();
                                $request->file('userfile')->move(public_path('uploads'), $photoName);
                                $donnee['photo'] = $photoName;
                        } else {
                                if ($request->input('typepersonne') !== "2") {
                                        $donnee['photo'] = "etudiant_photo";
                                }
                        }


                        Personne::create($donnee);
                }

                // return view('universite.liste', compact('personness', 'types'));

                
        }

        

        public function ajaxTypepersonne(Request $request)
        {
                $input = $request->all();
                $validator = Validator::make($input, [
                        'nom_type' => 'required',
                ]);
                if ($validator->fails()) {
                        return view('universite.liste');
                } else {
                        $donneetype = array(
                                'libelle' => $request->input('nom_type')
                        );
                        TypePersonne::create($donneetype);
                }
                $new_typepersonne = TypePersonne::all();
                return response()->json($new_typepersonne);
                //return view('universite.liste');
        }



        public function form()
        {
                $types = TypePersonne::all();
                return view('universite/formulaire', compact('types'));
        }
}
