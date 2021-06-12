<?php

namespace App\Http\Controllers;

use App\Imports\pasienImport;
use App\Models\Data as ModelsData;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use PDF;
use Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pasien = DB::table('pasien')
        ->select('*')
        ->get();
        return view('0264_Tampil' , ['pasien' => $pasien]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {  
        return view('/0264_Tambah');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
          //Menangkap Data Yang Diinput
          $nama = $request->get('nama');
          $alamat = $request->get('alamat');
          //Menyimpan data kedalam tabel
          $save_pasien = new \App\Models\Data;
          $save_pasien->nama = $nama;
          $save_pasien->alamat = $alamat;
          $save_pasien->save();
          return redirect('Tampil');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pasien = \App\Models\Data::find($id);
        return view('0264_Edit', ['buku' => $pasien]);
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
        $pasien = \App\Models\Data::find($id);
        $pasien->nama = $request->nama;
        $pasien->alamat = $request->alamat;
        $pasien->save();
        return redirect('Tampil');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pasien = \App\Models\Data::find($id);
        $pasien->delete();
        return redirect('Tampil');
    }
    public function generate()
    {
        $pasien = \App\Models\Data::All();
        $pdf = PDF::loadview('tampil_pdf',['pasien'=>$pasien]);
        return $pdf->stream();
    }
    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');

        // membuat nama file unik
        $nama_file = $file->hashName();

        //temporary file
        $path = $file->storeAs('public/excel/',$nama_file);

        // import data
        $import = Excel::import(new pasienImport(), storage_path('app/public/excel/'.$nama_file));

        $pasien = DB::table('pasien');
        if($import) {
            //redirect
            //return redirect()->route('pasien.index')->with(['success' => 'Data Berhasil Diimport!']);
            //return view('0264_Tampil' , ['pasien' => $pasien]); 
            return redirect('/Tampil');

        } else {
            //redirect
            //return redirect()->route('.index')->with(['error' => 'Data Gagal Diimport!']);
            //return view('0264_Tampil' , ['pasien' => $pasien]); 
            return redirect('/Tampil');

        }
    }

}

