<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Diagnosis;
use App\Models\Gejala;
use Illuminate\Http\Request;

class HistoriDiagnosisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'loginDuration' => $this->LoginDuration(),
            'diagnosis' => $this->getHistoryDiagnosis(),
        ];

        return view('admin.histori-diagnosis.histori-diagnosis', $data);
    }

    public function getHistoryDiagnosis()
    {
        $diagnosis = Diagnosis::with(['user' => function ($query) {
            $query->select('id', 'name');
        }, 'penyakit' => function ($query) {
            $query->select('id', 'name');
        }])->get(['id', 'user_id', 'penyakit_id', 'updated_at'])->map(function ($diagnosis) {
            $diagnosis['updated_at'] = $diagnosis['updated_at'];
            $diagnosis['user'] = $diagnosis['user']->toArray();
            $diagnosis['penyakit'] = $diagnosis['penyakit']->toArray();
            return [
                'id' => $diagnosis['id'],
                'updated_at' => $diagnosis['updated_at'],
                'user' => $diagnosis['user'],
                'penyakit' => $diagnosis['penyakit'],
            ];
        })->values()->toArray();

        return $diagnosis;
    }

    public function detail($id)
    {
        $diagnosis = Diagnosis::find($id, ['answer_log']);
        $answerLog = json_decode($diagnosis->answer_log, true);
        foreach ($answerLog as $key => $value) {
            $answerLog[$key] = $value == 1 ? 'Ya' : 'Tidak';
        }
        $gejala = Gejala::whereIn('id', array_keys($answerLog))->get(['id', 'name']);
        foreach ($gejala as $item) {
            $item->answer = $answerLog[$item->id];
        }
        $answerLog = $gejala->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'answer' => $item->answer,
            ];
        });

        $data = [
            'loginDuration' => $this->LoginDuration(),
            'detailDiagnosis' => $answerLog->toArray(),
        ];

        return view('admin.histori-diagnosis.detail', $data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
