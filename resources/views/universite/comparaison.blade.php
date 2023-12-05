<?php


namespace App\Http\Controllers;

use App\Exceptions;
use App\Models\Clientsavia;
use App\Models\Clients;
use App\Exceptions\TestException;
use App\Models\Comptes;
use App\Models\Typecomptes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ClientsaviaController extends Controller
{
    public function __construct()
    {
        //  $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // Constante pour la taille de reference client avia
    const SIZEREFCLIENT = 11;

    public function findCustomer($input, $unsuscribe = false)
    {


        $input = Clientsavia::splitAgenceAndCodeclient($input);
        $clientexiste = Clients::where('AG_CODEAGCE', '=', $input['AG_CODEAGCE'])
            ->where('CL_CODECLIENT', '=', $input['CL_CODECLIENT'])->where('CL_CLOTURE', '=', 'N')->first();

        $input = Clientsavia::splitAgenceAndCodeclient($input);
        $clientexiste = Clients::where('AG_CODEAGCE', '=', $input['AG_CODEAGCE'])
            ->where('CL_CODECLIENT', '=', $input['CL_CODECLIENT'])->where('CL_CLOTURE', '=', 'N')->first();

        if ($clientexiste <> null) {

            $clientdejaenrole = Clientsavia::where('AG_CODEAGCE', '=', $input['AG_CODEAGCE'])
                ->where('CL_CODECLIENT', '=', $input['CL_CODECLIENT'])->where('CLA_SOUSCRIPTION', '=', 'V')->first();

            if ($clientdejaenrole <> null) {
                if ($unsuscribe == true) {
                    $clientdejaenrole = Clientsavia::where('AG_CODEAGCE', '=', $input['AG_CODEAGCE'])
                        ->where('CL_CODECLIENT', '=', $input['CL_CODECLIENT'])
                        ->where('CLA_REFERENCELINK', '=', $input['reference_link'])
                        ->where('CLA_REFERENCEWALLET', '=', $input['reference_wallet'])
                        ->where('CLA_SOUSCRIPTION', '=', 'V')->first();
                    if ($clientdejaenrole <> null) {
                        $response = [
                            'code_message' => '200', 'existe' => true, 'valide' => true, 'reference_client_bd' => $input['reference_client_bd'],
                            'prenom' => $clientexiste['CL_PRENOMCLI'], 'nom' => $clientexiste['CL_NOMCLI'], 'telephone_mobile' => $clientexiste['PY_CODEPAYS'] . $clientexiste['CL_TELEPHONE'],
                            'email' => $clientexiste['CL_EMAIL'], 'adresse' => $clientexiste['CL_ADRESSEGEO'], 'pays_piece_identite' => $clientexiste['CL_NUMPIECE'],
                            'type_piece_identite' => $clientexiste['CL_TYPEPIECE'], 'numero_piece_identite' => $clientexiste['CL_NUMPIECE'], 'date_delivrance' => Carbon::parse($clientexiste['CL_DATEPIECE'])->format('Y-m-d'),
                            'date_echeance_piece_identite' => Carbon::parse($clientexiste['CL_DATEVALIDATION'])->format('Y-m-d'), 'date_naissance' => Carbon::parse($clientexiste['CL_DATENAIS'])->format('Y-m-d')
                        ];
                        return response()->json($response, 200);
                    } else {
                        $response['code_message'] = '404';
                        return response()->json($response, 404);
                    }
                } else {

                    $response = [
                        'code_message' => '200', 'existe' => true, 'valide' => true, 'reference_client_bd' => $input['reference_client_bd'],
                        'prenom' => $clientexiste['CL_PRENOMCLI'], 'nom' => $clientexiste['CL_NOMCLI'], 'telephone_mobile' => $clientexiste['PY_CODEPAYS'] . $clientexiste['CL_TELEPHONE'],
                        'email' => $clientexiste['CL_EMAIL'], 'adresse' => $clientexiste['CL_ADRESSEGEO'], 'pays_piece_identite' => $clientexiste['CL_NUMPIECE'],
                        'type_piece_identite' => $clientexiste['CL_TYPEPIECE'], 'numero_piece_identite' => $clientexiste['CL_NUMPIECE'], 'date_delivrance' => Carbon::parse($clientexiste['CL_DATEPIECE'])->format('Y-m-d'),
                        'date_echeance_piece_identite' => Carbon::parse($clientexiste['CL_DATEVALIDATION'])->format('Y-m-d'), 'date_naissance' => Carbon::parse($clientexiste['CL_DATENAIS'])->format('Y-m-d')
                    ];
                    return response()->json($response, 200);
                }
            } else {
                $response = [
                    'code_message' => '200', 'existe' => true, 'valide' => true, 'reference_client_bd' => $input['reference_client_bd'],
                    'prenom' => $clientexiste['CL_PRENOMCLI'], 'nom' => $clientexiste['CL_NOMCLI'], 'telephone_mobile' => $clientexiste['PY_CODEPAYS'] . $clientexiste['CL_TELEPHONE'],
                    'email' => $clientexiste['CL_EMAIL'], 'adresse' => $clientexiste['CL_ADRESSEGEO'], 'pays_piece_identite' => $clientexiste['CL_NUMPIECE'],
                    'type_piece_identite' => $clientexiste['CL_TYPEPIECE'], 'numero_piece_identite' => $clientexiste['CL_NUMPIECE'], 'date_delivrance' => Carbon::parse($clientexiste['CL_DATEPIECE'])->format('Y-m-d'),
                    'date_echeance_piece_identite' => Carbon::parse($clientexiste['CL_DATEVALIDATION'])->format('Y-m-d'), 'date_naissance' => Carbon::parse($clientexiste['CL_DATENAIS'])->format('Y-m-d')
                ];
                return response()->json($response, 200);
            }
        } else {

            $response = [
                'message' => "non autorise",
                'code_message' => "401",
            ];
            return response()->json($response, 401);
        }
        //return $response;
    }


    public function verifyCustomer(Request $request)
    {

        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'reference_client_bd' => 'required|min:11|max:11',
        ]);

        if ($validator->fails()) {
            $response['code_message'] = '400';
            return response()->json($response, 400);
        } else {
            $response = $this->findCustomer($input);
            return $response;
        }
    }



    public function custumerActivation(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'reference_client_bd' => 'required|min:11|max:11',
            'reference_wallet' => 'required',
            'reference_link' => 'required',
        ]);

        if ($validator->fails()) {
            $response['code_message'] = '400';
            return response()->json($response, 400);
        } else {

            $response = $this->findCustomer($input);
            return $response;
            $input = Clientsavia::splitAgenceAndCodeclient($input);

            if ($response['code_message'] == '200') {
                // $clientenattente = Clientsavia::where('AG_CODEAGCE', '=', $input['AG_CODEAGCE'])->where('CL_CODECLIENT', '=', $input['CL_CODECLIENT'])->where('CLA_SOUSCRIPTION', '=', 'A')->first();
                $clientenattente = Clientsavia::where('AG_CODEAGCE', '=', $input['AG_CODEAGCE'])->where('CL_CODECLIENT', '=', $input['CL_CODECLIENT'])->first();
                if ($clientenattente <> null) {
                    if ($clientenattente->CLA_SOUSCRIPTION == "V") {
                        $comptes = Comptes::where('CL_CODECLIENT', '=', $input['CL_CODECLIENT'])->where('AG_CODEAGCE', '=', $input['AG_CODEAGCE'])->where('CP_SITCPTE', '=', 'N')->where('CP_CLOTURE', '=', 'N')->where('CP_SUSPENSION', '=', 'N')->get();

                        $cptclient = array();
                        $x = 0;
                        foreach ($comptes as $compte) {
                            $reference_adhesion = Comptes::codeReferenceAdhesion();
                            $typcompte = Typecomptes::where('TC_TYPECOMPTE', '=', $compte->TC_TYPECOMPTE)->first();
                            $cptclient[] = [
                                'reference_adhesion' => $reference_adhesion, 'reference_iban' => '',
                                'compte' => $compte->CP_NUMCPTE, 'type_compte' => $typcompte['TC_LIBELLETYPE'], 'titulaire' => $compte->CP_INTITULE, 'devise' => 'XOF'
                            ];

                            Comptes::where('AG_CODEAGCE', '=', $input['AG_CODEAGCE'])
                                ->where('CL_CODECLIENT', '=', $input['CL_CODECLIENT'])
                                ->where('CP_NUMCPTE', '=', $compte->CP_NUMCPTE)->where('CP_CLOTURE', '=', 'N')->update(['reference_adhesion' => $reference_adhesion]); //first();//

                            $x++;
                        }
                        $response['couplages'] = $cptclient;
                        $response['code_message'] = '200';
                        return response()->json($response, 200);
                    }
                } else {
                    $input['AG_CODEAGCE'] = $input['AG_CODEAGCE'];
                    $input['CL_CODECLIENT'] = $input['CL_CODECLIENT'];
                    $input['CLA_REFERENCEWALLET'] = $input['reference_wallet'];
                    $input['CLA_REFERENCELINK'] = $input['reference_link'];
                    $input['CLA_SOUSCRIPTION'] = 'A';
                    $input['CLA_DATESOUSCRIPAVIA'] = NULL;
                    $input['CLA_DATEDESOUSCRIPTION'] = NULL;
                    $input['SYNCHRONISATIONAVIA'] = 'N';
                    $clientsavia1 = Clientsavia::create($input);
                }
            }
            return response()->json($response);
        }
    }
   
    // Désinscription d'un utilisateur
    public function unsuscribeCustumer(Request $request)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'reference_client_bd' => 'required|min:11|max:11',
            'reference_wallet' => 'required',
            'reference_link' => 'required',
        ]);

        if ($validator->fails()) {
            $response['code_message'] = '400';
        } else {
            $response = $this->findCustomer($input, true);
            $input = Clientsavia::splitAgenceAndCodeclient($input);
            if ($response['code_message'] == '200') {

                $updateclientavia = Clientsavia::where('AG_CODEAGCE', '=', $input['AG_CODEAGCE'])
                    ->where('CL_CODECLIENT', '=', $input['CL_CODECLIENT'])
                    ->where('CLA_REFERENCELINK', '=', $input['reference_link'])
                    ->update([
                        'SYNCHRONISATIONAVIA' => 'N', 'CLA_SOUSCRIPTION' => 'D',
                        'CLA_DATEDESOUSCRIPTION' => Carbon::now()->format('Y/m/d')
                    ]);

                $updateclients = Clients::where('AG_CODEAGCE', '=', $input['AG_CODEAGCE'])
                    ->where('CL_CODECLIENT', '=', $input['CL_CODECLIENT'])
                    ->update(['SYNCHRONISATIONAVIA' => 'N']); //first();//
                $response = [
                    'reference_wallet' => $input['reference_wallet'],
                    'Reference_client_bd' => $input['reference_client_bd'], 'reference_link' => $input['reference_link']
                ];
            } else {

                $dejadesouscrire = Clientsavia::where('AG_CODEAGCE', '=', $input['AG_CODEAGCE'])
                    ->where('CL_CODECLIENT', '=', $input['CL_CODECLIENT'])
                    ->where('CLA_REFERENCELINK', '=', $input['reference_link'])
                    ->where('CLA_SOUSCRIPTION', '=', 'D')->where('CLA_DATEDESOUSCRIPTION', '<>', NULL)->first();

                if ($dejadesouscrire <> null) {
                    $response = ['code_message' => '409', 'valide' => false, 'existe' => true];
                } else {
                    $response = ['code_message' => '404', 'valide' => false, 'existe' => false];
                }
            }
        }
        return response()->json($response);
    }


    public function listCostumerForValidate(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'AG_CODEAGCE' => 'required',

        ]);

        if ($validator->fails()) {
            $response = array();
        } else {

            $clientavias = Clientsavia::where('CLA_SOUSCRIPTION', '=', 'A')->where('AG_CODEAGCE', '=', $input['AG_CODEAGCE'])->where('SYNCHRONISATIONAVIA', '=', 'N')->get();
            if ($clientavias <> null) {
                foreach ($clientavias as $clientavia) {
                    //var_dump($clientavia);
                    $clientavia->CLA_DATESOUSCRIPAVIA =  ($clientavia->CLA_DATESOUSCRIPAVIA != NULL) ? Carbon::parse($clientavia->CLA_DATESOUSCRIPAVIA)->format('d/m/Y') : NULL;
                    $clientavia->CLA_DATEDESOUSCRIPTION =  ($clientavia->CLA_DATEDESOUSCRIPTION != NULL) ? Carbon::parse($clientavia->CLA_DATEDESOUSCRIPTION)->format('d/m/Y') : NULL;
                    $clientavia->CLA_DATESOUSCRIPAVIA =  ($clientavia->CLA_DATESOUSCRIPAVIA != NULL) ? Carbon::parse($clientavia->CLA_DATESOUSCRIPAVIA)->format('d/m/Y') : NULL;
                    $clientavia->CLA_DATEMODIFICATION1 =  ($clientavia->CLA_DATEMODIFICATION != NULL) ? Carbon::parse($clientavia->CLA_DATEMODIFICATION)->format('d/m/Y') : NULL;
                    $clientavia->CLA_DATECREATION1 =  ($clientavia->CLA_DATECREATION != NULL) ? Carbon::parse($clientavia->CLA_DATECREATION)->format('d/m/Y') : NULL;
                }
                $response = ['clientavia' => $clientavias];
            } else {
                $response = ['clientavia' => null];
            }
        }
        return response()->json($response);
    }



    public function listCostumerForUnsuscribe(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'AG_CODEAGCE' => 'required',
        ]);

        if ($validator->fails()) {
            $response = array();
        } else {
            $clientavias = Clientsavia::where('CLA_SOUSCRIPTION', '=', 'D')->where('AG_CODEAGCE', '=', $input['AG_CODEAGCE'])->where('SYNCHRONISATIONAVIA', '=', 'N')->get();
            if ($clientavias <> null) {
                //    foreach($clientavias as $clientavia){
                //         //var_dump($clientavia);
                //        $clientavia->CLA_DATESOUSCRIPAVIA =  ($clientavia->CLA_DATESOUSCRIPAVIA!=NULL)?Carbon::parse($clientavia->CLA_DATESOUSCRIPAVIA)->format('d/m/Y'):NULL;
                //        $clientavia->CLA_DATEDESOUSCRIPTION =  ($clientavia->CLA_DATEDESOUSCRIPTION!=NULL)?Carbon::parse($clientavia->CLA_DATEDESOUSCRIPTION)->format('d/m/Y'):NULL;
                //        $clientavia->CLA_DATESOUSCRIPAVIA =  ($clientavia->CLA_DATESOUSCRIPAVIA!=NULL)?Carbon::parse($clientavia->CLA_DATESOUSCRIPAVIA)->format('d/m/Y'):NULL;
                //        $clientavia->CLA_DATEMODIFICATION1 =  ($clientavia->CLA_DATEMODIFICATION!=NULL)?Carbon::parse($clientavia->CLA_DATEMODIFICATION)->format('d/m/Y'):NULL;
                //        $clientavia->CLA_DATECREATION1 =  ($clientavia->CLA_DATECREATION!=NULL)?Carbon::parse($clientavia->CLA_DATECREATION)->format('d/m/Y'):NULL;

                //        }
                $response = ['clientsavia' => $clientavias];
            } else {
                $response = ['clientsavia' => null];
            }
        }
        return response()->json($response);
    }

    public function SYNCHRONISATIONAVIALine(Request $request)
    {
        $input = $request->all();
        $clientsavia = Clientsavia::where('AG_CODEAGCE', '=', $input['AG_CODEAGCE'])->where('CL_CODECLIENT', '=', $input['CL_CODECLIENT'])
            ->where('CLA_REFERENCEWALLET', '=', $input['CLA_REFERENCEWALLET'])->where('CLA_REFERENCELINK', '=', $input['CLA_REFERENCELINK'])
            ->update(['SYNCHRONISATIONAVIA' => 'O']); //first();//
        $response = ['STATUT' => 'SUCCES', 'clientsavia' => $clientsavia];
        return response()->json($response);
    }

    public function validateSOUSCRIPTIONCostumer(Request $request)
    {
        $input = $request->all();
        $nb = count($input);
        $clientsavia = Clientsavia::where('AG_CODEAGCE', '=', $input['AG_CODEAGCE'])->where('CL_CODECLIENT', '=', $input['CL_CODECLIENT'])
            ->where('CLA_REFERENCEWALLET', '=', $input['CLA_REFERENCEWALLET'])->where('CLA_REFERENCELINK', '=', $input['CLA_REFERENCELINK'])
            ->update(['CLA_SOUSCRIPTION' => 'V']); //first();//
        $response = ['STATUT' => 'SUCCES', 'clientsavia' => $clientsavia];



        return response()->json($response);
    }

    //custumerBalance()


}
